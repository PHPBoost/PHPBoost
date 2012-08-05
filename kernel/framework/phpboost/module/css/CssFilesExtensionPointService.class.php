<?php
/*##################################################
 *                           CssFilesExtensionPointService.class.php
 *                            -------------------
 *   begin                : October 06, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class CssFilesExtensionPointService
{
	public static function get_css_files_always_displayed()
	{
		$css_files = array();
		foreach (self::get_extension_points() as $module_name => $provider)
		{
			$css_files[$module_name] = $provider->get_css_files_always_displayed();
		}
		return $css_files;
	}
	
	public static function get_css_files_running_module_displayed()
	{
		$css_files = array();
		foreach (self::get_extension_points() as $module_name => $provider)
		{
			$css_files[$module_name] = $provider->get_css_files_running_module_displayed();
		}
		return $css_files;
	}

	public static function get_extension_points()
	{
		return AppContext::get_extension_provider_service()->get_extension_point(CssFilesExtensionPoint::EXTENSION_POINT);
	}
	
	private static function get_provider($module_id)
	{
		if (self::module_containing_extension_point($module_id))
		{
			$extension_point = self::get_extension_points();
			return $extension_point[$module_id];
		}
	}
}
?>