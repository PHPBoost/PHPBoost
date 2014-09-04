<?php
/*##################################################
 *                             MenuUrlBuilder.class.php
 *                            -------------------
 *   begin                : October 25, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

/**
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc
 */
class MenuUrlBuilder
{
    private static $dispatcher = '/admin/menus/dispatcher.php';
    
	/**
	 * @return Url
	 */
    public static function menu_configuration_list()
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/list/');
	}
    
	/**
	 * @return Url
	 */
	public static function menu_configuration_create()
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/create/');
	}
	
	/**
	 * @return Url
	 */
	public static function menu_configuration_create_valid()
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/create/valid/?token=' .
		AppContext::get_session()->get_token());
	}
	
	/**
	 * @return Url
	 */
	public static function menu_configuration_edit($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/' . $id . '/edit/');
	}
	
	/**
	 * @return Url
	 */
	public static function menu_configuration_edit_valid($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/' . $id . '/edit/valid/?token=' .
		AppContext::get_session()->get_token());
	}
	
	/**
	 * @return Url
	 */
	public static function menu_configuration_edit_delete($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/' . $id . '/delete/?token=' .
		AppContext::get_session()->get_token());
	}
	
	/**
	 * @return Url
	 */
	public static function menu_configuration_configure($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/' . $id . '/configure/');
	}
	
	/**
	 * @return Url
	 */
	public static function menu_configuration_configure_valid($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/' . $id . '/configure/valid/');
	}
	
	/**
	 * @return Url
	 */
	public static function menu_list()
	{
		return DispatchManager::get_url(self::$dispatcher, '/menus/list/');
	}
}
?>