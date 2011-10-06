<?php
/*##################################################
 *                           ModulesCssFilesService.class.php
 *                            -------------------
 *   begin                : October 06, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
	private static $css_files_always_displayed = array();
	private static $css_files_running_module_displayed = array();
	private static $css_files = array();
	const CSS_FILES_ALWAYS_DISPLAYED = 'always_displayed';
	const CSS_FILES_RUNNING_MODULE_DISPLAYED = 'running_module_displayed';
	
	public static function __static()
	{
		self::$css_files_always_displayed = CssFilesExtensionPointService::get_css_files_always_displayed();
		self::$css_files_running_module_displayed = CssFilesExtensionPointService::get_css_files_running_module_displayed();
		self::build_array();
	}
		
	public static function get_css_files_always_displayed($theme_id)
	{
		return self::$css_files[$theme_id][self::CSS_FILES_ALWAYS_DISPLAYED];
	}
	
	public static function get_css_files_running_module_displayed($theme_id)
	{
		$css_files = self::$css_files[$theme_id][self::CSS_FILES_RUNNING_MODULE_DISPLAYED];
		
		$running_module_id = Environment::get_running_module_name();
		if (array_key_exists($running_module_id, $css_files))
		{
			return $css_files[$running_module_id];
		}
		return array();
	}
	
	private static function build_array()
	{
		foreach (ThemeManager::get_activated_themes_map() as $theme_id => $theme)
		{
			self::$css_files[$theme_id] = array(
				self::CSS_FILES_ALWAYS_DISPLAYED => self::get_modules_css_files_always_displayed($theme_id),
				self::CSS_FILES_RUNNING_MODULE_DISPLAYED => self::get_modules_css_files_running_module_displayed($theme_id)
			);
		}
	}
	
	private static function get_modules_css_files_always_displayed($theme_id)
	{
		$modules_css_files_always_displayed = array();
		foreach (self::$css_files_always_displayed as $module_id => $css_files)
		{
			foreach ($css_files as $css_file)
			{
				$modules_css_files_always_displayed[] = self::get_real_path_css_file($theme_id, $module_id, $css_file);
			}
		}
		return $modules_css_files_always_displayed;
	}
	
	private static function get_modules_css_files_running_module_displayed($theme_id)
	{
		$modules_css_files_running_module_displayed = array();
		foreach (self::$css_files_running_module_displayed as $module_id => $css_files)
		{
			$module_css_files_running_module_displayed = array();
			if (!empty($css_files))
			{
				foreach ($css_files as $css_file)
				{
					$module_css_files_running_module_displayed[] = self::get_real_path_css_file($theme_id, $module_id, $css_file);
				}
				$modules_css_files_running_module_displayed[$module_id] = $module_css_files_running_module_displayed;
			}
		}
		return $modules_css_files_running_module_displayed;
	}
	
	private static function get_real_path_css_file($theme_id, $module_id, $css_file)
	{
		if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/' . $module_id . '/' . $css_file))
		{
			return '/templates/' . $theme_id . '/modules/' . $module_id . '/' . $css_file;
		}
		else if (file_exists(PATH_TO_ROOT . '/' . $module_id . '/templates/' . $css_file))
		{
			return '/' . $module_id . '/templates/' . $css_file;
		}
	}
}
?>