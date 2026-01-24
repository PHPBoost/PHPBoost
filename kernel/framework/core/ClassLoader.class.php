<?php
/**
 * @package     Core
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 01 24
 * @since       PHPBoost 3.0 - 2009 10 21
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ClassLoader
{
    /**
     * @var string The cache file path
     */
    protected static string $cache_file = '/cache/autoload.php';

    /**
     * @var array The autoload class list
     */
    protected static array $autoload = [];

    /**
     * @var array The namespace to path map
     */
    protected static array $namespace_map = [];

    /**
     * @var bool Whether the autoload has already been reloaded
     */
    protected static bool $already_reloaded = false;

    /**
     * @var array The paths to exclude from autoload
     */
    protected static array $exclude_paths = [
        '/cache', '/images', '/lang', '/upload', '/templates',
        '/kernel/data', '/kernel/lib/js', '/kernel/lib/php/geshi',
        '/kernel/framework/io/db/dbms/Doctrine'
    ];

    /**
     * @var array The folder names to exclude from autoload
     */
    protected static array $exclude_folders_names = ['templates', 'lang'];

    /**
     * Initializes the autoload class list.
     */
    public static function init_autoload(): void
    {
        spl_autoload_register([self::class, 'autoload']);
        if (!self::inc(PATH_TO_ROOT . self::$cache_file))
        {
            self::generate_classlist();
        }
    }

    /**
     * Tries to autoload the given class name, else a fatal error is raised.
     *
     * @param string $classname The name of the class to load
     */
    public static function autoload(string $classname): void
    {
        // Check if the class is namespaced
        if (strpos($classname, '\\') !== false)
        {
            self::load_namespaced_class($classname);
            return;
        }

        // Legacy class loading
        if (!isset(self::$autoload[$classname]) || !self::inc(PATH_TO_ROOT . self::$autoload[$classname]))
        {
            self::generate_classlist();
            if (isset(self::$autoload[$classname]))
            {
                require_once PATH_TO_ROOT . self::$autoload[$classname];
            }
        }
        self::call_static_initializer($classname);
    }

    /**
     * Loads a namespaced class.
     *
     * @param string $classname The fully qualified class name
     */
    protected static function load_namespaced_class(string $classname): void
    {
        $namespace_parts = explode('\\', $classname);
        $class = array_pop($namespace_parts);
        $namespace = implode('\\', $namespace_parts);

        if (isset(self::$namespace_map[$namespace]))
        {
            $file_path = self::$namespace_map[$namespace] . '/' . $class . '.php';
            if (file_exists(PATH_TO_ROOT . $file_path))
            {
                require_once PATH_TO_ROOT . $file_path;
                return;
            }
        }

        // Fallback: Try to find the class in the namespace map
        foreach (self::$namespace_map as $ns => $path)
        {
            $file_path = $path . '/' . str_replace('\\', '/', $namespace) . '/' . $class . '.php';
            if (file_exists(PATH_TO_ROOT . $file_path))
            {
                require_once PATH_TO_ROOT . $file_path;
                return;
            }
        }

        throw new \RuntimeException("Class {$classname} not found.");
    }

    /**
     * Checks if a class is registered and valid.
     *
     * @param string $classname The name of the class to check
     * @return bool True if the class is registered and valid, false otherwise
     */
    public static function is_class_registered_and_valid(string $classname): bool
    {
        return self::is_class_registered($classname) && file_exists(PATH_TO_ROOT . self::$autoload[$classname]);
    }

    /**
     * Gets the module ID from a class name.
     *
     * @param string $class_name The class name
     * @return string The module ID
     */
    public static function get_module_id_from_class_name(string $class_name): string
    {
        return isset(self::$autoload[$class_name]) ? current(explode('/', ltrim(dirname(self::$autoload[$class_name]), '/'))) : '';
    }

    /**
     * Generates the autoload cache file by exploring PHPBoost folders.
     *
     * @param bool $force Whether to force regeneration
     */
    public static function generate_classlist(bool $force = false): void
    {
        if (!self::$already_reloaded || $force)
        {
            self::$already_reloaded = true;
            self::$autoload = [];
            self::$namespace_map = [];

            include_once(PATH_TO_ROOT . '/kernel/framework/io/filesystem/FileSystemElement.class.php');
            include_once(PATH_TO_ROOT . '/kernel/framework/io/filesystem/Folder.class.php');
            include_once(PATH_TO_ROOT . '/kernel/framework/io/filesystem/File.class.php');
            include_once(PATH_TO_ROOT . '/kernel/framework/io/IOException.class.php');
            include_once(PATH_TO_ROOT . '/kernel/framework/util/Path.class.php');

            $phpboost_classfile_pattern = '`\.php$`';
            $paths = ['/', '/kernel/framework/core/lang'];

            foreach ($paths as $path)
            {
                self::add_classes(Path::phpboost_path() . $path, $phpboost_classfile_pattern);
            }
            self::add_classes(Path::phpboost_path() . '/kernel/framework/io/db/dbms/Doctrine/', $phpboost_classfile_pattern);
            self::generate_autoload_cache();
        }
    }

    /**
     * Clears the autoload cache.
     */
    public static function clear_cache(): void
    {
        $file = new File(PATH_TO_ROOT . self::$cache_file);
        $file->delete();
        self::$already_reloaded = false;
    }

    /**
     * Checks if a class is registered.
     *
     * @param string $classname The name of the class to check
     * @return bool True if the class is registered, false otherwise
     */
    protected static function is_class_registered(string $classname): bool
    {
        return array_key_exists($classname, self::$autoload);
    }

    /**
     * Adds classes from a directory to the autoload list.
     *
     * @param string $directory The directory to scan
     * @param string $pattern The pattern to match class files
     * @param bool $recursive Whether to scan recursively
     */
    protected static function add_classes(string $directory, string $pattern, bool $recursive = true): void
    {
        $folder = new Folder($directory);
        $relative_path = Path::get_path_from_root($folder->get_path());

        $files = $folder->get_files($pattern);
        foreach ($files as $file)
        {
            $filename = $file->get_name();
            $file_path = $file->get_path();
            $content = file_get_contents($file_path);

            // Check for namespace declaration
            if (preg_match('~namespace\s+([^;]+)~', $content, $matches))
            {
                $namespace = trim($matches[1]);
                $classname = $file->get_name_without_extension();
                self::$namespace_map[$namespace] = $relative_path;
                self::$autoload[$namespace . '\\' . $classname] = $relative_path . '/' . $filename;
            }
            else
            {
                $classname = $file->get_name_without_extension();
                self::$autoload[$classname] = $relative_path . '/' . $filename;
            }
        }

        if ($recursive)
        {
            $folders = $folder->get_folders('`^[a-z]{1}.*$`iu');
            foreach ($folders as $a_folder)
            {
                if (!in_array($a_folder->get_path_from_root(), self::$exclude_paths)
                && !in_array($a_folder->get_name(), self::$exclude_folders_names))
                {
                    self::add_classes($a_folder->get_path(), $pattern);
                }
            }
        }
    }

    /**
     * Generates the autoload cache file.
     */
    protected static function generate_autoload_cache(): void
    {
        $file = new File(PATH_TO_ROOT . self::$cache_file);
        try
        {
            $file->write('<?php self::$autoload = ' . var_export(self::$autoload, true) . '; self::$namespace_map = ' . var_export(self::$namespace_map, true) . '; ?>');
            $file->close();
        }
        catch (IOException $ex)
        {
            die('The cache folder is not writable, please set CHMOD to 755');
        }
    }

    /**
     * Includes a file if it exists.
     *
     * @param string $file The file to include
     * @return bool True if the file was included, false otherwise
     */
    protected static function inc(string $file): bool
    {
        if (!file_exists($file))
        {
            return false;
        }
        include_once $file;
        return true;
    }

    /**
     * Calls the static initializer of a class if it exists.
     *
     * @param string $classname The name of the class
     */
    protected static function call_static_initializer(string $classname): void
    {
        if (method_exists($classname, '__static'))
        {
            call_user_func([$classname, '__static']);
        }
    }
}
?>
