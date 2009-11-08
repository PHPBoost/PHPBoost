<?php
/*##################################################
 *                           LangLoader.class.php
 *                            -------------------
 *   begin                : October 29, 2009
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
 * @subpackage lang
 */
class LangLoader
{
	const DEFAULT_LOCALE = 'english';

	private static $locale = self::DEFAULT_LOCALE;
	private static $langs = array();

	public static function clear_lang_cache()
	{
		self::$langs = array();
	}

	public static function set_locale($locale)
	{
		self::$locale = $locale;
	}

	public static function get($filename, $module = '')
	{
		return self::get_raw(trim($module, '/'), $filename);
	}

	public static function get_class($class_file, $module = '')
	{
		$package = Path::get_package($class_file);
		$classname = Path::get_classname($class_file);
		$lang_file = str_replace('/', '-', $package) . '-' . $classname;
		return self::get($lang_file, $module);
	}

	private static function get_raw(&$folder, &$filename)
	{
		$langfile = $folder . '/' . $filename;
		if (!isset(self::$langs[$langfile]))
		{
			self::load($langfile, $folder, $filename);
		}
		return self::$langs[$langfile];
	}

	private static function load(&$langfile, &$folder, &$filename)
	{
		include self::get_real_lang_path($folder, $filename);
		self::$langs[$langfile] = $lang;
	}

	private static function get_real_lang_path(&$folder, &$filename)
	{
		$real_folder = PATH_TO_ROOT;
		if (!empty($folder))
		{
			$real_folder .= '/' . $folder;
		}
		$real_folder .= '/lang/';
		$filename_with_extension = '/' . $filename . '.php';

		$real_lang_file = $real_folder . self::$locale . $filename_with_extension;
		if (file_exists($real_lang_file))
		{
			return $real_lang_file;
		}

		$real_lang_file = $real_folder . self::DEFAULT_LOCALE . $filename_with_extension;
		if (file_exists($real_lang_file))
		{
			return $real_lang_file;
		}


		throw new LangNotFoundException($folder, $filename);
	}
}
?>