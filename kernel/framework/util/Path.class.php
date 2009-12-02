<?php
/*##################################################
 *                                Path.class.php
 *                            -------------------
 *   begin                : November 8, 2006
 *   copyright            : (C) 2009 Loïc Rouchon
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
 * @author Loïc Rouchon <loic.rouchon@phpboost.com>
 * @package util
 */
class Path
{
	private static $fs_root_directory;

	/**
	 * @return string the phpboost home directory path 
	 */
	public static function phpboost_path()
	{
		if (empty(self::$fs_root_directory))
		{
			self::$fs_root_directory = preg_replace('`^(.+)/kernel/framework/util/?$`i', '$1',
			self::uniformize_path(dirname(__FILE__)));
		}
		return self::$fs_root_directory;
	}

	/**
	 * @desc Returns the class package
	 * @param string $class_file the class file
	 * @return string the class package
	 * The package is under the form </code>'package/subpackage/../sub..subpackage'</code>
	 */
	public static function get_package($class_file)
	{
		return ltrim(self::get_path_from_root(dirname($class_file)), '/');
	}

	/**
	 * @desc Returns the path from the phpboost root directory
	 * @param string $path the file path
	 * @return string the path from the phpboost root directory beginning by <code>'/'</code>
	 */
	public static function get_path_from_root($path)
	{
		return '/' . trim(str_replace(self::phpboost_path(), '', self::uniformize_path($path)), '/');
	}

	/**
	 * @desc Deduces the classname regarding the filename
	 * @param string $class_file the class file path or filename
	 * @return string the classname
	 */
	public static function get_classname($class_file)
	{
		if (($i = strpos($class_file, '.')) !== false)
		{
			$class_file = substr($class_file, 0, $i);
		}
		if (($i = strrpos($class_file, '/')) !== false)
		{
			$class_file = substr($class_file, $i + 1);
		}
		return $class_file;
	}

	/**
	 * @desc Uniformizes the path getting its real path (absolute one) and replacing all
	 * <code>'\'</code> by <code>'/'</code>
	 * @param string $path the path to uniformize
	 * @return string the absolute path
	 */
	public static function uniformize_path($path)
	{
		return str_replace('\\', '/', realpath($path));
	}
}

?>
