<?php
/*##################################################
 *                           ModulesCssFilesService.class.php
 *                            -------------------
 *   begin                : October 06, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
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
 ###################################################*/

class ModulesCssFilesService
{
	private static $modules_css_files = array();
	
	public static function __static()
	{
		$extension_points = AppContext::get_extension_provider_service()->get_extension_point(CssFilesExtensionPoint::EXTENSION_POINT);
		foreach ($extension_points as $module_id => $provider)
		{
			self::$modules_css_files[$module_id] = $provider;
		}
	}
		
	public static function get_css_files_always_displayed()
	{
		$theme_id = AppContext::get_current_user()->get_theme();
		$css_files = array();
		foreach (self::$modules_css_files as $module_id => $module_css_files)
		{
			$module_css_files_always_displayed = $module_css_files->get_css_files_always_displayed();
			foreach ($module_css_files_always_displayed as $css_file)
			{
				$css_files[] = self::get_real_path_css_file($theme_id, $module_id, $css_file);
			}
		}
		return $css_files;
	}
	
	public static function get_css_files_running_module_displayed()
	{
		$module_id = Environment::get_running_module_name();
		if (array_key_exists($module_id, self::$modules_css_files))
		{
			$module_css_files = self::$modules_css_files[$module_id];
			$module_css_files_running_module_displayed = $module_css_files->get_css_files_running_module_displayed();
			if (!empty($module_css_files_running_module_displayed))
			{
				$theme_id = AppContext::get_current_user()->get_theme();
				$css_files = array();
				foreach ($module_css_files_running_module_displayed as $css_file)
				{
					$css_files[] = self::get_real_path_css_file($theme_id, $module_id, $css_file);
				}
				return $css_files;
			}
			return array();
		}
		return array();
	}
		
	private static function get_real_path_css_file($theme_id, $module_id, $css_file)
	{
		if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/' . $module_id . '/' . $css_file))
		{
			return '/templates/' . $theme_id . '/modules/' . $module_id . '/' . $css_file;
		}
		return '/' . $module_id . '/templates/' . $css_file;
	}
}
?>