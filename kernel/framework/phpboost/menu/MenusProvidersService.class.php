<?php
/*##################################################
 *                        MenusProvidersService.class.php
 *                            -------------------
 *   begin                : October 08, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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

class MenusProvidersService
{	
	public static function get_menus($module_id)
	{
		if (self::module_containing_extension_point($module_id))
		{
			$provider = self::get_provider($module_id);
			return $provider->get_menus($module_id);
		}
	}
	
	public static function module_containing_extension_point($module_id)
	{
		return in_array($module_id, self::get_extension_point_ids());
	}
	
	public static function get_extension_point_ids()
	{
		return array_keys(self::get_extension_point());
	}
	
	public static function get_extension_point()
	{
		return AppContext::get_extension_provider_service()->get_extension_point(MenusExtensionPoint::EXTENSION_POINT);
	}
	
	public static function get_provider($module_id)
	{
		if (self::module_containing_extension_point($module_id))
		{
			$extension_point = self::get_extension_point();
			return $extension_point[$module_id];
		}
	}
}
?>