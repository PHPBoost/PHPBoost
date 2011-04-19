<?php
/*##################################################
/**
 *                         ThemeManager.class.php
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
class ThemeManager
{
	private static $errors = null;
	
	public static function get_installed_themes_map()
	{
		return ThemesConfig::load()->get_themes();
	}
	
	public static function get_activated_themes_map()
	{
		$activated_themes = array();
		foreach (ThemesConfig::load()->get_themes() as $theme) {
			if ($theme->is_activated()) {
				$activated_themes[$theme->get_id()] = $theme;
			}
		}
		return $activated_themes;
	}
	
	public static function get_theme($theme_id)
	{
		return ThemesConfig::load()->get_theme($theme_id);
	}
	
	public static function get_theme_existed($theme_id)
	{
		if (ThemesConfig::load()->get_theme($theme_id) !== null)
		{
			return true;
		}
		return false;
	}
	
	public static function install($theme_id, $authorizations = array(), $enable_theme = true)
	{
		if (!empty($theme_id) && !self::get_theme_existed($theme_id))
		{
			$theme = new Theme($theme_id, $authorizations, $enable_theme);
			$configuration = $theme->get_configuration();
			$theme->set_columns_disabled($configuration->get_columns_disabled());
			
			$phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
			if (version_compare($phpboost_version, $configuration->get_compatibility(), '>='))
			{
				ThemesConfig::load()->add_theme($theme);
				ThemesConfig::save();
				
				self::regenerate_cache();
			}
			else
			{
				self::$errors = 'Not compatible !';
			}
		}
		else
		{
			self::$errors = 'e_theme_already_exist';
		}
	}
	
	public static function uninstall($theme_id, $drop_files = false)
	{
		if (!empty($theme_id) && self::get_theme_existed($theme_id))
		{
			if (self::get_theme($theme_id)->get_id() !== UserAccountsConfig::load()->get_default_theme())
			{
				// TODO change user theme in Database
				
				ThemesConfig::load()->remove_theme_by_id($theme_id);
				ThemesConfig::save();
				
				if ($drop_files)
				{
					$folder = new Folder(PATH_TO_ROOT . '/templates/' . $theme_id);
					$folder->delete();
				}
				self::regenerate_cache();
			}
			self::$errors = 'e_incomplete';
		}
		else
		{
			self::$errors = 'e_theme_already_exist';
		}
	}
	
	public static function change_visibility($theme_id, $visibility)
	{
		if (!empty($theme_id) && self::get_theme_existed($theme_id))
		{
			$theme = ThemesConfig::load()->get_theme($theme_id);
			$theme->set_activated($visibility);
			ThemesConfig::load()->update($theme);
			ThemesConfig::save();
			
			self::regenerate_cache();
		}
	}
	
	public static function change_authorizations($theme_id, Array $authorizations)
	{
		if (!empty($theme_id) && self::get_theme_existed($theme_id))
		{
			$theme = ThemesConfig::load()->get_theme($theme_id);
			$theme->set_authorizations($authorizations);
			ThemesConfig::load()->update($theme);
			ThemesConfig::save();
		}
	}
	
	public static function change_informations($theme_id, $visibility, Array $authorizations = array(), $columns_disabled = null)
	{
		if (!empty($theme_id) && self::get_theme_existed($theme_id))
		{
			$theme = ThemesConfig::load()->get_theme($theme_id);
			$theme->set_activated($visibility);
			
			if (!empty($authorizations))
			{
				$theme->set_authorizations($authorizations);
			}
			
			if ($columns_disabled !== null)
			{
				$theme->set_columns_disabled($columns_disabled);
			}
			
			ThemesConfig::load()->update($theme);
			ThemesConfig::save();
			
			self::regenerate_cache();
		}
	}
	
	public static function change_columns_disabled($theme_id, ColumnsDisabled $columns_disabled)
	{
		if (!empty($theme_id) && self::get_theme_existed($theme_id))
		{
			$theme = ThemesConfig::load()->get_theme($theme_id);
			$theme->set_columns_disabled($columns_disabled);
			ThemesConfig::load()->update($theme);
			ThemesConfig::save();
			
			self::regenerate_cache();
		}
	}
	
	public static function get_errors()
	{
		return self::$errors;
	}
	
	private static function regenerate_cache()
	{
    	ModulesCssFilesCache::invalidate();
	}
}
?>
