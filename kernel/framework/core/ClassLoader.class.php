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
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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
 * @package core
 */
class ClassLoader
{
	private static $cache_file = '/cache/autoload.php';
	private static $autoload;
	private static $already_reloaded = false;
	private static $exclude_paths = array(
		'cache', 'images', 'lang', 'upload', 'templates',
		'tinymce', //kernel
		'.svn' //Dev
	);

    /**
     * @desc initializes the autoload class list
     */
	public static function init_autoload()
	{
		if (!self::inc(PATH_TO_ROOT . self::$cache_file))
		{
			self::generate_classlist();
		}
		spl_autoload_register(array(get_class(), 'autoload'));
	}

	/**
	 * @desc tries to autoload the given <code>$classname</code> else, a fatal error is raised
	 * @param string $classname the name of the class to load
	 */
	public static function autoload($classname)
	{
		if (!(isset(self::$autoload[$classname]) && self::inc(PATH_TO_ROOT . self::$autoload[$classname])))
		{
			self::generate_classlist();
			if (isset(self::$autoload[$classname]))
			{
				require_once PATH_TO_ROOT . self::$autoload[$classname];
			}
		}
	}

	/**
	 * @desc Generates the autoload cache file by exploring phpboost folders
	 */
	public static function generate_classlist()
	{
		if (!self::$already_reloaded)
		{
			import('io/filesystem/FileSystemElement');
			import('io/filesystem/Folder');
			import('io/filesystem/File');
			import('util/Path');

			$phpboost_classfile_pattern = '`^.+\.class\.php$`';
			$paths = array(
				'/',
				'/kernel/framework/phpboost/cache',
				'/kernel/framework/io/cache',
				'/kernel/framework/core/lang',
			);

			foreach ($paths as $path)
			{
				self::add_classes(Path::phpboost_path() . $path, $phpboost_classfile_pattern, true);
			}
			self::add_classes(Path::phpboost_path() . '/kernel/framework/io/db/dbms/Doctrine/', '`^.+\.php$`', true);

			self::generate_autoload_cache();
			self::$already_reloaded = true;
		}
		return self::$autoload;
	}

	private static function add_classes($directory, $pattern, $recursive = false)
	{
		$files = array();
		$folder = new Folder($directory);
		$relative_path = Path::get_path_from_root($folder->get_name(true));
		foreach ($folder->get_files($pattern) as $file)
		{
			$filename = $file->get_name(false, false);
			$classname = substr($filename, 0, strpos($filename, '.'));
			self::$autoload[$classname] = $relative_path . '/' . $filename;
		}

		if ($recursive)
		{
			foreach ($folder->get_folders('`^[a-z]{1}.*$`i') as $folder)
			{
				if (!in_array($folder->get_name(), self::$exclude_paths))
				{
					self::add_classes($folder->get_name(true), $pattern, true);
				}
			}
		}
	}

	private static function generate_autoload_cache()
	{
		$file = new File(PATH_TO_ROOT . self::$cache_file, File::WRITE);
		$file->write('<?php self::$autoload = ' . var_export(self::$autoload, true) . '; ?>');
		$file->close();
	}

	private static function inc($file)
	{
		return file_exists($file) && include_once $file;
	}
}
?>