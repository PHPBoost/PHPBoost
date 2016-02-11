<?php
/*##################################################
 *                       AdminExtendedFieldsUrlBuilder.class.php
 *                            -------------------
 *   begin                : May 16, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
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

class AdminExtendedFieldsUrlBuilder
{
	private static $dispatcher = '/admin/member';
	
	/*
	 * @ return Url
	*/
	public static function fields_list($params = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/extended-fields/list/' . $params);
	}
	
	/*
	 * @ return Url
	 */
	public static function add()
	{
		return DispatchManager::get_url(self::$dispatcher, '/extended-fields/add/');
	}
	
	/*
	 * @ return Url
	 */
	public static function edit($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/extended-fields/' . $id. '/edit/');
	}
	
	/*
	 * @ return Url
	 */
	public static function delete()
	{
		return DispatchManager::get_url(self::$dispatcher, '/extended-fields/delete/');
	}
	
	/*
	 * @ return Url
	 */
	public static function change_display()
	{
		return DispatchManager::get_url(self::$dispatcher, '/extended-fields/change_display/');
	}
}
?>