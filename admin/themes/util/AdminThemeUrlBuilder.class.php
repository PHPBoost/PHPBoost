<?php
/*##################################################
 *                       AdminThemeUrlBuilder.class.php
 *                            -------------------
 *   begin                : September 20, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

class AdminThemeUrlBuilder
{
	private static $dispatcher = '/admin/themes';
	
	/*
	 * @ return Url
	 */
	public static function list_installed_theme()
	{
		return DispatchManager::get_url(self::$dispatcher, '/installed/');
	}
	
	/*
	 * @ return Url
	 */
	public static function add_theme()
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/');
	}
		
	/*
	 * @ return Url
	 */
	public static function delete_theme($theme_id)
	{
		return DispatchManager::get_url(self::$dispatcher, $theme_id . '/delete/');
	}
}
?>