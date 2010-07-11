<?php
/*##################################################
 *                           ClassLoader.class.php
 *                            -------------------
 *   begin                : October 21, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc
 * @package {@package}
 */
class ClassLoader
{
	private static $cache_file = '/cache/autoload.php';
	private static $autoload;
	private static $already_reloaded = false;
	private static $exclude_paths = array(
		'/cache', '/images', '/lang', '/upload', '/templates',
		'/kernel/data', '/kernel/lib/js', '/kernel/framework/content/tinymce',
		'/kernel/framework/content/geshi', '/kernel/framework/io/db/dbms/Doctrine',
	    '/test/PHPUnit',
	);

	private static $exclude_folders_names = array('.svn', 'templates', 'lang');

	/**
	 * @desc initializes the autoload class list
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
	 * @desc tries to autoload the given <code>$classname</code> else, a fatal error is raised
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
	 * @desc Generates the autoload cache file by exploring phpboost folders
	 */
	public static function generate_classlist()
	{
		if (!self::$already_reloaded)
		{
			self::$already_reloaded = true;
			self::$autoload = array();
			import('io/filesystem/FileSystemElement');
			import('io/filesystem/Folder');
			import('io/filesystem/File');
			import('io/IOException');
			import('util/Path');

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
		foreach ($folder->get_files($pattern) as $file)
		{
			$filename = $file->get_name();
			$classname = $file->get_name_without_extension();
			self::$autoload[$classname] = $relative_path . '/' . $filename;
		}

		if ($recursive)
		{
			foreach ($folder->get_folders('`^[a-z]{1}.*$`i') as $a_folder)
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
		$file->write('<?php self::$autoload = ' . var_export(self::$autoload, true) . '; ?>');
	}

	private static function inc($file)
	{
		return file_exists($file) && include_once $file;
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