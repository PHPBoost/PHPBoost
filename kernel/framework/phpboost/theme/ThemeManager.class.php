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
	
	public static function install($theme_id, $authorizations = array(), $enable_theme = true)
	{
		if (!empty($theme_id))
		{
			$theme = new Theme($theme_id, $authorizations, $enable_theme);
			$configuration = $theme->get_configuration();
			$theme->set_columns_disabled($configuration->get_columns_disabled());
			
			
			$phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
			if (version_compare($phpboost_version, $configuration->get_compatibility(), 'lt'))
			{
				ThemesConfig::load()->add_theme($theme);
				ThemesConfig::save();
			}
			
			self::regenerate_cache();
		}
	}
	
	public static function uninstall($theme_id, $drop_files = false)
	{
		ThemesConfig::load()->remove_theme_by_id($theme_id);
		ThemesConfig::save();
		
		if ($drop_files)
		{
			$folder = new Folder(PATH_TO_ROOT . '/templates/' . $theme_id);
			$folder->delete();
		}

		self::regenerate_cache();
	}
	
	public static function change_visibility($theme_id, $visibility)
	{
		$theme = ThemesConfig::load()->get_theme($theme_id);
		$theme->set_activated($visibility);
		ThemesConfig::save($theme);
	}
	
	public static function change_authorizations($theme_id, Array $authorizations)
	{
		$theme = ThemesConfig::load()->get_theme($theme_id);
		$theme->set_authorizations($authorizations);
		ThemesConfig::save($theme);
	}
	
	public static function change_columns_disabled($theme_id, ColumnsDisabled $columns_disabled)
	{
		$theme = ThemesConfig::load()->get_theme($theme_id);
		$theme->set_columns_disabled($columns_disabled);
		ThemesConfig::save($theme);
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
