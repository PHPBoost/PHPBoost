<?php
/*##################################################
/**
 *                         ThemesManager.class.php
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
class ThemesManager
{
	private static $error = null;
	
	public static function get_installed_themes_map()
	{
		$themes = array();
		foreach (ThemesConfig::load()->get_themes() as $theme) {
			$themes[$theme->get_id()] = $theme;
		}
		ksort($themes);
		return $themes;
	}
	
	public static function get_activated_themes_map()
	{
		$activated_themes = array();
		foreach (ThemesConfig::load()->get_themes() as $theme) {
			if ($theme->is_activated()) {
				$activated_themes[$theme->get_id()] = $theme;
			}
		}
		ksort($activated_themes);
		return $activated_themes;
	}
	
	public static function get_activated_and_authorized_themes_map()
	{
		$themes = array();
		foreach (ThemesConfig::load()->get_themes() as $theme) {
			if ($theme->is_activated() && $theme->check_auth()) {
				$themes[$theme->get_id()] = $theme;
			}
		}
		ksort($themes);
		return $themes;
	}
	
	public static function get_default_theme()
	{
		return UserAccountsConfig::load()->get_default_theme();
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
		if (!file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/config.ini'))
		{
			self::$error = LangLoader::get_message('misfit.phpboost', 'status-messages-common');
			$folder = new Folder(PATH_TO_ROOT . '/templates/' . $theme_id);
			$folder->delete();
		}
		else if (!empty($theme_id) && !self::get_theme_existed($theme_id))
		{
			$theme = new Theme($theme_id, $authorizations, $enable_theme);
			$configuration = $theme->get_configuration();
			$theme->set_columns_disabled($configuration->get_columns_disabled());
			
			$phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
			if (version_compare($phpboost_version, $configuration->get_compatibility(), '>'))
			{
				self::$error = LangLoader::get_message('misfit.phpboost', 'status-messages-common');
			}
			else
			{
				ThemesConfig::load()->add_theme($theme);
				ThemesConfig::save();
			}
		}
		else
		{
			self::$error = LangLoader::get_message('element.already_exists', 'status-messages-common');
		}
	}
	
	public static function uninstall($theme_id, $drop_files = false)
	{
		if (!empty($theme_id) && self::get_theme_existed($theme_id))
		{
			$default_theme = self::get_default_theme();
			if (self::get_theme($theme_id)->get_id() !== $default_theme)
			{
				PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('theme' => $default_theme), 
					'WHERE theme=:old_theme', array('old_theme' => $theme_id
				));
				
				ThemesConfig::load()->remove_theme_by_id($theme_id);
				ThemesConfig::save();
				
				if ($drop_files)
				{
					$folder = new Folder(PATH_TO_ROOT . '/templates/' . $theme_id);
					$folder->delete();
				}
				
				AppContext::get_cache_service()->clear_css_cache();
			}
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
		}
	}
	
	public static function change_customize_interface($theme_id, CustomizeInterface $customize_interface)
	{
		if (!empty($theme_id) && self::get_theme_existed($theme_id))
		{
			$theme = ThemesConfig::load()->get_theme($theme_id);
			$theme->set_customize_interface($customize_interface);
			ThemesConfig::load()->update($theme);
			ThemesConfig::save();
		}
	}
	
	public static function get_error()
	{
		return self::$error;
	}
}
?>