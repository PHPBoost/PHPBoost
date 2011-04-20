<?php
/*##################################################
/**
 *                        ThemeConfigurationManager.class.php
 *                            -------------------
 *   begin                : April 10, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
 *
 *
 *###################################################
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
 *###################################################
 */

 /**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
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
		$config_ini_file = self::find_config_ini_file($theme_id);
		return new ThemeConfiguration($config_ini_file);
	}

	private static function find_config_ini_file($theme_id)
	{
		$config_ini_folder = PATH_TO_ROOT . '/templates/' . $theme_id . '/config/';

		$config_ini_file = $config_ini_folder . get_ulang() . '/config.ini';
		if (file_exists($config_ini_file))
		{
			return $config_ini_file;
		}

		$folder = new Folder($config_ini_folder);
		foreach ($folder->get_folders() as $lang_folder)
		{
			$config_ini_file = $lang_folder->get_path() . '/config.ini';
			if (file_exists($config_ini_file))
			{
				return $config_ini_file;
			}
		}
		throw new Exception('Theme "' . $theme_id . '" config.ini not found in' .
			    '/' . $theme_id . '/lang/');
	}
}
?>
