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

import('core/lang/LangNotFoundException');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc
 * @package core
 * @subpackage lang
 */
class LangLoader
{
	const DEFAULT_LOCALE = 'english';
	
	private static $locale = DEFAULT_LOCALE;
	private static $langs = array();

	public static function clear_lang_cache()
	{
		self::$langs = array();
	}
	
	public static function set_locale($locale)
	{
		self::$locale = $locale;
	}

	public static function get($langpath)
	{
		return self::get_raw(PATH_TO_ROOT . '/' . ltrim($langpath, '/'));
	}

	public static function get_file($filepath)
	{
		$slash_index = strrpos($filepath, '/');
		$folder = substr($filepath, 0, $slash_index);
		$file = substr($filepath, $slash_index + 1);
		$filename = substr($file, 0, strpos($file, '.'));
		return self::get_raw($folder . '/lang/' . $filename);
	}

	private static function get_raw($langpath)
	{
		if (!isset(self::$langs[$langpath]))
		{
			self::load($langpath);
		}
		return self::$langs[$langpath];
	}

	private static function load(&$langpath)
	{
		include self::get_real_lang_path($langpath);
		self::$langs[$langpath] = $lang;
	}

	private static function get_real_lang_path($langpath)
	{
		$real_lang_file = $langpath . '_' . self::$locale . '.php';

		if (file_exists($real_lang_file))
		{
			return $real_lang_file;
		}

		$real_lang_file = $langpath . '.php';
		if (file_exists($real_lang_file))
		{
			return $real_lang_file;
		}
		
		throw new LangNotFoundException($langpath);
	}
}
?>