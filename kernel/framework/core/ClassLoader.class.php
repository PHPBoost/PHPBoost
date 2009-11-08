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
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

	public static function init_autoload()
	{
		if (!@include_once PATH_TO_ROOT . self::$cache_file)
		{
			self::generate_classlist();
		}
		spl_autoload_register(array(get_class(), 'autoload'));
	}

	public static function autoload($classname)
	{
		if (!(isset(self::$autoload[$classname]) && @include_once PATH_TO_ROOT . self::$autoload[$classname]))
		{
			self::generate_classlist();
			if (isset(self::$autoload[$classname]))
			{
				require_once PATH_TO_ROOT . self::$autoload[$classname];
			}
		}
	}

	public static function generate_classlist()
	{
		import('io/filesystem/FileSystemElement');
		import('io/filesystem/Folder');
		import('io/filesystem/File');
		import('util/Path');

		self::add_classes(Path::phpboost_path(), true);
		self::generate_autoload_cache();
		return self::$autoload;
	}

	private static function add_classes($directory, $recursive = false)
	{
		$files = array();
		$folder = new Folder($directory);
		$relative_path = Path::get_path_from_root($folder->get_name(true));
		foreach ($folder->get_files('`^.+\.class\.php$`') as $file)
		{
			$classname = preg_replace('`\.class\.php$`', '', $file->get_name(false, false));
			self::$autoload[$classname] = $relative_path . '/' . $classname . '.class.php';
		}

		if ($recursive)
		{
			foreach ($folder->get_folders() as $folder)
			{
				self::add_classes($folder->get_name(true), true);
			}
		}
	}

	private static function generate_autoload_cache()
	{
		$file = new File(PATH_TO_ROOT . self::$cache_file, WRITE);
		$file->write('<?php self::$autoload = ' . var_export(self::$autoload, true) . '; ?>');
		$file->close();
	}
}
?>