<?php
/*##################################################
 *                       ContactUrlBuilder.class.php
 *                            -------------------
 *   begin                : March 1, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class ContactUrlBuilder
{
	private static $dispatcher = '/contact';
	
	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}
	
	public static function check_field_name()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/check_name/');
	}
	
	public static function add_field()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/add/');
	}
	
	public static function edit_field($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/'. $id .'/edit/');
	}
	
	public static function delete_field()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/delete/');
	}
	
	public static function change_display()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/change_display/');
	}
	
	public static function manage_fields()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/');
	}
	
	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
