<?php
/*##################################################
/**
 *                        ThemeConfigurationManager.class.php
 *                            -------------------
 *   begin                : April 10, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
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
		$config_ini_file = PATH_TO_ROOT . '/templates/' . $theme_id . '/config.ini';
		$desc_ini_file = self::find_desc_ini_file($theme_id);
		return new ThemeConfiguration($config_ini_file, $desc_ini_file);
	}

	private static function find_desc_ini_file($theme_id)
	{
		$desc_ini_folder = PATH_TO_ROOT . '/templates/' . $theme_id . '/lang/';

		$desc_ini_file = $desc_ini_folder . AppContext::get_current_user()->get_locale() . '/desc.ini';
		if (file_exists($desc_ini_file))
		{
			return $desc_ini_file;
		}
		throw new IOException('Theme "' . $theme_id . '" desc.ini not found in' .
			    '/' . $theme_id . '/lang/');
	}
}
?>