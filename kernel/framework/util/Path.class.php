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

	public static function phpboost_path()
	{
		if (empty(self::$fs_root_directory))
		{
			self::$fs_root_directory = preg_replace('`^(.+)/kernel/framework/util/?$`i', '$1',
			self::uniformize_path(dirname(__FILE__)));
		}
		return self::$fs_root_directory;
	}

	public static function get_package($class_file)
	{
		return ltrim(self::get_path_from_root(dirname($class_file)), '/');
	}

	public static function get_path_from_root($path)
	{
		return '/' . trim(str_replace(self::phpboost_path(), '', self::uniformize_path($path)), '/');
	}

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

	public static function uniformize_path($path)
	{
		return str_replace('\\', '/', $path);
	}
}

?>
