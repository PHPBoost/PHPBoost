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

	/**
	 * @desc sets the language locale
	 * @param string $locale the locale
	 */
	public static function set_locale($locale)
	{
		self::$locale = $locale;
	}

	/**
	 * @desc Retrieves the language file <code>$filename</code> in
	 * <code>/$module/lang/$locale/$filename.php</code>
	 * If module is empty, the kernel lang folder will be used
	 * @param string $filename the language filename
	 * @param string $module the module to look for languages files in
	 * @return string[string] the lang array which keys are languages identifiers and values the
	 * translated messages
	 */
	public static function get($filename, $module = '')
	{
		$module_name = trim($module, '/');
		return self::get_raw($module_name, $filename);
	}

    /**
     * @desc Retrieves the language file associated to the <code>$class_file</code>
     * If module is empty, the kernel lang folder will be used
     * <p>Usage:
     * LangLoader::get_class(__FILE__, $module); // if called inside the class
     * </p>
     * @param string $class_file the class file that you want to load associated languages messages
     * @param string $module the module to look for languages files in
     * @return string[string] the lang array which keys are languages identifiers and values the
     * translated messages
     */
	public static function get_class($class_file, $module = '')
	{
		$package = Path::get_package($class_file);
		$classname = Path::get_classname($class_file);
		$lang_file = str_replace('/', '-', $package) . '-' . $classname;
		return self::get($lang_file, $module);
	}

	private static function get_raw($folder, $filename)
	{
		$langfile = $folder . '/' . $filename;
		if (!isset(self::$langs[$langfile]))
		{
			self::load($langfile, $folder, $filename);
		}
		return self::$langs[$langfile];
	}

	private static function load($langfile, $folder, $filename)
	{
		include self::get_real_lang_path($folder, $filename);
		self::$langs[$langfile] = $lang;
	}

	/**
	 * @desc returns the real language file path, trying first to load the localized language file
	 * and if it's not possible, use the default locale one.
	 * @param string $folder the folder to look in
	 * @param string $filename the language filename
	 * @return string the real language file path
	 */
	private static function get_real_lang_path($folder, $filename)
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

    /**
     * @desc clear the lang cache (for unit test only)
     */
    public static function clear_lang_cache()
    {
        self::$langs = array();
    }
}
?>