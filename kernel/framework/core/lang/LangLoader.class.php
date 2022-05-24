<?php
/**
 * @package     Core
 * @subpackage  Lang
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 24
 * @since       PHPBoost 3.0 - 2009 09 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class LangLoader
{
	private static $locale = '';

	/**
	 * @var CacheFactory
	 */
	private static $ram_cache = null;

	/**
	 * sets the language locale
	 * @param string $locale the locale
	 */
	public static function set_locale($locale)
	{
		self::$locale = in_array($locale, self::get_available_langs()) ? $locale : self::get_default_lang();
	}

	/**
	 * Returns the current language locale
	 * @return string the current language locale
	 */
	public static function get_locale($locale = '')
	{
		return in_array($locale, self::get_available_langs()) ? $locale : (self::$locale ? self::$locale : self::get_default_lang());
	}

	public static function get_available_langs()
	{
		$langs_folder = new Folder(PATH_TO_ROOT . '/lang');
		$langs_list = $langs_folder->get_folders();

		$available_langs = array();
		foreach ($langs_list as $lang)
		{
			$available_langs[] = $lang->get_name();
		}

		return $available_langs;
	}

	public static function get_default_lang()
	{
		$browser_lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)) : '';
		$browser_lang = !$browser_lang && isset($_SERVER['HTTP_X_COUNTRY_CODE']) ? strtolower($_SERVER['HTTP_X_COUNTRY_CODE']) : $browser_lang;
		$browser_lang = !$browser_lang ? strtolower(AppContext::get_request()->get_location_info_by_ip()) : $browser_lang;
		$langs = self::get_available_langs();
		
		if ($browser_lang)
		{
			foreach ($langs as $lang)
			{
				$lang_config = parse_ini_file(PATH_TO_ROOT . '/lang/' . $lang . '/config.ini');
				if (($lang == 'english' && $browser_lang == 'en') || (isset($lang_config['identifier']) && $lang_config['identifier'] == $browser_lang))
					return $lang;
			}
		}
		else
			return $langs[0];
	}

	/**
	 * @param string $message_id the language message identifier
	 * @param string $filename the language filename
	 * @param string $module the module to look for languages files in
	 * @return string the localized message
	 */
	public static function get_message($message_id, $filename, $module = '')
	{
		$lang = self::get($filename, $module);
		if (!isset($lang[$message_id]))
		{
			$lang = self::get($filename, $module, 'real_lang');
			if (!isset($lang[$message_id]))
				$lang = self::get($filename, $module, 'default');
		}
		return $lang[$message_id];
	}

	/**
	 * Check if the language file exists
	 * @param string $filename the language filename
	 * @param string $module the module to look for languages files in
	 * @return bool true if filename exists, false otherwise
	 */
	public static function filename_exists($filename, $module = '')
	{
		return self::get_real_lang_path($module, $filename, '', false);
	}

	/**
	 * Retrieves the language file <code>$filename</code> in
	 * <code>/$module/lang/$locale/$filename.php</code>
	 * If module is empty, the kernel lang folder will be used
	 * @param string $filename the language filename
	 * @param string $module the module to look for languages files in
	 * @param string $forced_file the language filename to return inevitably
	 * @return string[string] the lang array which keys are languages identifiers and values the
	 * translated messages
	 */
	public static function get($filename, $module = '', $forced_file = '')
	{
		$module_name = trim($module, '/');
		return self::get_raw($module_name, $filename, $forced_file);
	}

	private static function get_raw($folder, $filename, $forced_file = '')
	{
		$lang_id = $folder . '/' . $filename;
		$ram_cache = self::get_ram_cache();
		if (!$ram_cache->contains($lang_id))
		{
			self::load($lang_id, $folder, $filename, $forced_file);
		}
		return $ram_cache->get($lang_id);
	}

	private static function load($lang_id, $folder, $filename, $forced_file = '')
	{
		$lang = array();
		include self::get_real_lang_path($folder, $filename, $forced_file);
		if (empty($lang) && !empty($LANG) && is_array($LANG))
		{
			$lang = $LANG;
		}
		self::get_ram_cache()->store($lang_id, $lang);
	}

	/**
	 * returns the real language file path, trying first to load the localized language file
	 * and if it's not possible, use the default locale one.
	 * @param string $folder the folder to look in
	 * @param string $filename the language filename
	 * @param string $forced_file the language filename to return inevitably
	 * @param bool $throw_exception_on_failure tell if throw an exception on failure. If set to false, false will be returned
	 * @return string the real language file path
	 */
	private static function get_real_lang_path($folder, $filename, $forced_file = '', $throw_exception_on_failure = true)
	{
		$real_folder = PATH_TO_ROOT . (!empty($folder) ? '/' . $folder : '') . '/lang/';
		$filename_with_extension = '/' . $filename . '.php';

		if (!empty($folder) && empty($forced_file))
		{
			// Module - Langs priority order
			//      /lang/$lang/modules/$module/$file.php
			//      /$module/lang/$lang/$file.php
			$real_lang_file = PATH_TO_ROOT . '/lang/' . self::$locale . '/modules/' . $folder . $filename_with_extension;
			if (file_exists($real_lang_file))
			{
				return $real_lang_file;
			}
		}

		if (empty($forced_file) || $forced_file = 'real_lang')
		{
			$real_lang_file = $real_folder . self::$locale . $filename_with_extension;
			if (file_exists($real_lang_file))
			{
				return $real_lang_file;
			}
		}

		// Get default lang file if nothing else is found
		if (empty($forced_file) || $forced_file = 'default')
		{
			$real_lang_file = $real_folder . self::get_default_lang() . $filename_with_extension;
			if (file_exists($real_lang_file))
			{
				return $real_lang_file;
			}
		}

		if ($throw_exception_on_failure)
			throw new LangNotFoundException($folder, $filename);
		else
			return false;
	}

	public static function get_kernel_langs($locale = '')
	{
		if ($locale)
			self::set_locale($locale);
		
		$lang_directory = new Folder(PATH_TO_ROOT . '/lang/' . self::get_locale($locale));
		$files = $lang_directory->get_files();
		$langloader = array();
		foreach($files as $file)
		{
			$filename = $file->get_name_without_extension();
			if (!in_array($filename, array('config')))
			{
				foreach(self::get($filename) as $var => $desc)
				{
					$langloader[$var] = $desc;
				}
			}
		}
		return $langloader;
	}

	public static function get_theme_langs($locale = '')
	{
		if ($locale)
			self::set_locale($locale);
		
		$current_theme = AppContext::get_current_user()->get_theme();
		$theme_lang_directory = new Folder(PATH_TO_ROOT . '/templates/' . $current_theme . '/lang/' . self::get_locale($locale));
		$files = $theme_lang_directory->get_files();
		$theme_langloader = array();
		foreach($files as $file)
		{
			$filename = $file->get_name_without_extension();
			if (!in_array($filename, array('desc')))
			{
				foreach(self::get($filename, '/templates/' . $current_theme) as $var => $desc)
				{
					$theme_langloader[$var] = $desc;
				}
			}
		}
		return $theme_langloader;
	}

	public static function get_module_langs($module_id, $locale = '')
    {
		if ($locale)
			self::set_locale($locale);
		
		$module_lang_directory = new Folder(PATH_TO_ROOT . '/' . $module_id . '/lang/' . self::get_locale($locale));
		$files = $module_lang_directory->get_files();
		$module_langloader = array();
		foreach($files as $file)
		{
			$filename = $file->get_name_without_extension();
			if (!in_array($filename, array('desc', 'install')))
			{
				foreach(self::get($filename, $module_id) as $var => $desc)
				{
					$module_langloader[$var] = $desc;
				}
			}
		}
		return $module_langloader;
    }

	public static function get_all_langs($module_id = '', $locale = '')
	{
		if(!empty($module_id) && !in_array($module_id, array('admin', 'cache', 'images', 'kernel', 'lang', 'repository', 'syndication', 'templates', 'upload', 'user')))
			return array_merge(self::get_kernel_langs($locale), self::get_module_langs($module_id, $locale), self::get_theme_langs($locale));
		else
			return array_merge(self::get_kernel_langs($locale), self::get_theme_langs($locale));
	}

	/**
	 * clear the lang cache (for unit test only)
	 */
	public static function clear_lang_cache()
	{
		self::$ram_cache = null;
		self::get_ram_cache();
	}

	/**
	 * @return RAMDataStore
	 */
	private static function get_ram_cache()
	{
		if (self::$ram_cache === null)
		{
			self::$ram_cache = new RAMDataStore('lang');
		}
		return self::$ram_cache;
	}
}
?>
