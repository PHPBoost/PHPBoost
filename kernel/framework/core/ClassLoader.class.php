<?php
/**
 * @package     Core
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 20
 * @since       PHPBoost 3.0 - 2009 10 21
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ClassLoader
{
	protected static $cache_file = '/cache/autoload.php';
	protected static $autoload;
	protected static $already_reloaded = false;
	protected static $exclude_paths = array(
		'/cache', '/images', '/lang', '/upload', '/templates',
		'/kernel/data', '/kernel/lib/js', '/kernel/lib/flash', '/kernel/lib/css', '/kernel/lib/php/geshi',
		'/kernel/framework/io/db/dbms/Doctrine', '/test/PHPUnit',
	);

	protected static $exclude_folders_names = array('templates', 'lang');

	/**
	 * initializes the autoload class list
	 */
	public static function init_autoload()
	{
		spl_autoload_register(array(get_class(), 'autoload'));
		if (!self::inc(PATH_TO_ROOT . self::$cache_file))
			self::generate_classlist();
	}

	/**
	 * tries to autoload the given <code>$classname</code> else, a fatal error is raised
	 * @param string $classname the name of the class to load
	 */
	public static function autoload($classname)
	{
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

	public static function is_class_registered_and_valid($classname)
	{
		return self::is_class_registered($classname) && file_exists(PATH_TO_ROOT . self::$autoload[$classname]);
	}

	/**
	 * Get module id from a class name
	 */
	public static function get_module_id_from_class_name($class_name)
	{
		return isset(self::$autoload[$class_name]) ? current(explode('/', ltrim(dirname(self::$autoload[$class_name]), '/'))) : '';
	}

	/**
	 * Generates the autoload cache file by exploring phpboost folders
	 */
	public static function generate_classlist($force = false)
	{
		if (!self::$already_reloaded || $force)
		{
			self::$already_reloaded = true;
			self::$autoload = array();

			include_once(PATH_TO_ROOT . '/kernel/framework/io/filesystem/FileSystemElement.class.php');
			include_once(PATH_TO_ROOT . '/kernel/framework/io/filesystem/Folder.class.php');
			include_once(PATH_TO_ROOT . '/kernel/framework/io/filesystem/File.class.php');
			include_once(PATH_TO_ROOT . '/kernel/framework/io/IOException.class.php');
			include_once(PATH_TO_ROOT . '/kernel/framework/util/Path.class.php');

			$phpboost_classfile_pattern = '`\.class\.php$`';
			$paths = array('/', '/kernel/framework/core/lang');

			foreach ($paths as $path)
			{
				self::add_classes(Path::phpboost_path() . $path, $phpboost_classfile_pattern);
			}
			self::add_classes(Path::phpboost_path() . '/kernel/framework/io/db/dbms/Doctrine/', '`\.php$`');
			self::generate_autoload_cache();
		}
	}

	public static function clear_cache()
	{
		$file = new File(PATH_TO_ROOT . self::$cache_file);
		$file->delete();
		self::$already_reloaded = false;
	}

	protected static function is_class_registered($classname)
	{
		return array_key_exists($classname, self::$autoload);
	}

	protected static function add_classes($directory, $pattern, $recursive = true)
	{
		$files = array();
		$folder = new Folder($directory);
		$relative_path = Path::get_path_from_root($folder->get_path());
		
		$files = $folder->get_files($pattern);
		foreach ($files as $file)
		{
			$filename = $file->get_name();
			$classname = $file->get_name_without_extension();
			self::$autoload[$classname] = $relative_path . '/' . $filename;
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

	protected static function generate_autoload_cache()
	{
		$file = new File(PATH_TO_ROOT . self::$cache_file);
		try
		{
			$file->write('<?php self::$autoload = ' . var_export(self::$autoload, true) . '; ?>');
			$file->close();
		}
		catch (IOException $ex)
		{
			die('The cache folder is not writeable, please set CHMOD to 755');
		}
	}

	protected static function inc($file)
	{
		return file_exists($file) && @include_once $file;
	}

	protected static function call_static_initializer($classname)
	{
		if (method_exists($classname, '__static'))
		{
			call_user_func(array($classname, '__static'));
		}
	}
}
?>
