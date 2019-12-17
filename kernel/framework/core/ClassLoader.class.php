<?php
/**
 * @package     Core
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 15
 * @since       PHPBoost 3.0 - 2009 10 21
 * @contributor mipel <mipel@phpboost.com>
*/

class ClassLoader
{
	private static $cache_file = '/cache/autoload.php';
	private static $autoload;
	private static $already_reloaded = false;
	private static $exclude_paths = array(
		'/cache', '/images', '/lang', '/upload', '/templates',
		'/kernel/data', '/kernel/lib/js', '/kernel/lib/flash', '/kernel/lib/css', '/kernel/lib/php/geshi',
		'/kernel/framework/io/db/dbms/Doctrine', '/test/PHPUnit',
	);

	private static $exclude_folders_names = array('templates', 'lang');

	/**
	 * initializes the autoload class list
	 */
	public static function init_autoload()
	{
		spl_autoload_register(array(get_class(), 'autoload'));
		if (!self::inc(PATH_TO_ROOT . self::$cache_file))
		{
			self::generate_classlist();
		}
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
		if (!self::is_class_registered($classname))
		{
			return false;
		}
		elseif (!file_exists(PATH_TO_ROOT . self::$autoload[$classname]))
		{
			self::generate_classlist();
			return self::is_class_registered($classname);
		}
		else
		{
			return true;
		}
	}

	/**
	 * Generates the autoload cache file by exploring phpboost folders
	 */
	public static function generate_classlist()
	{
		if (!self::$already_reloaded)
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

	private static function is_class_registered($classname)
	{
		return array_key_exists($classname, self::$autoload);
	}

	private static function add_classes($directory, $pattern, $recursive = true)
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

	private static function generate_autoload_cache()
	{
		$file = new File(PATH_TO_ROOT . self::$cache_file);
		try
		{
		 	$file->write('<?php self::$autoload = ' . var_export(self::$autoload, true) . '; ?>');
		 	$file->close();
		}
		catch (IOException $ex)
		{
			die('The cache folder is not writeable, please set CHMOD to 777');
		}
	}

	private static function inc($file)
	{
		return file_exists($file) && @include_once $file;
	}

	private static function call_static_initializer($classname)
	{
		if (method_exists($classname, '__static'))
		{
			call_user_func(array($classname, '__static'));
		}
	}
}
?>
