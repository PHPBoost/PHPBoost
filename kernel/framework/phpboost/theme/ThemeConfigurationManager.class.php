<?php
/**
 * @package     PHPBoost
 * @subpackage  Theme
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 02 02
 * @since       PHPBoost 3.0 - 2011 04 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ThemeConfigurationManager
{
	private static $cache_manager = null;

	public static function get($theme_id)
	{
		$cache_manager = self::get_cache_manager();
		if (!$cache_manager->contains($theme_id))
		{
			$theme_configuration = self::get_theme_configuration($theme_id);
			$cache_manager->store($theme_id, $theme_configuration);
		}
		return $cache_manager->get($theme_id);
	}

	private static function get_cache_manager()
	{
		if (self::$cache_manager === null)
		{
			self::$cache_manager = DataStoreFactory::get_ram_store(__CLASS__);
		}
		return self::$cache_manager;
	}

	private static function get_theme_configuration($theme_id)
	{
		$config_ini_file = PATH_TO_ROOT . '/templates/' . $theme_id . '/config.ini';
		$desc_ini_file = self::find_desc_ini_file($theme_id);
		return new ThemeConfiguration($config_ini_file, $desc_ini_file);
	}

	private static function find_desc_ini_file($theme_id)
	{
		$desc_ini_file = PATH_TO_ROOT . '/lang/' . AppContext::get_current_user()->get_locale() . '/templates/' . $theme_id . '/desc.ini';
		if (file_exists($desc_ini_file))
		{
			return $desc_ini_file;
		}

		$desc_ini_folder = PATH_TO_ROOT . '/templates/' . $theme_id . '/lang/';

		$desc_ini_file = $desc_ini_folder . AppContext::get_current_user()->get_locale() . '/desc.ini';
		if (file_exists($desc_ini_file))
		{
			return $desc_ini_file;
		}

		$folder = new Folder($desc_ini_folder);
		$folders = $folder->get_folders();
		foreach ($folders as $lang_folder)
		{
			$desc_ini_file = $lang_folder->get_path() . '/desc.ini';
			if (file_exists($desc_ini_file))
			{
				return $desc_ini_file;
			}
		}
		throw new IOException('Theme "' . $theme_id . '" desc.ini not found in' . '/' . $theme_id . '/lang/');
	}
}
?>
