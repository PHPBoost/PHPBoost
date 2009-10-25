<?php
/*##################################################
 *                             MenuUrlBuilder.class.php
 *                            -------------------
 *   begin                : October 25, 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

import('mvc/dispatcher/Dispatcher');
/**
 * @author Loïc Rouchon <loic.rouchon@phpboost.com>
 * @desc
 */
class MenuUrlBuilder extends BusinessObject
{
    private static $dispatcher = '/admin/menus/dispatcher.php';
    
    public static function menu_configuration_list()
	{
		return Dispatcher::get_url(self::$dispatcher, '/');
	}
    
	public static function menu_configuration_create()
	{
		return Dispatcher::get_url(self::$dispatcher, '/create/');
	}
	
	public static function menu_configuration_create_valid()
	{
		return Dispatcher::get_url(self::$dispatcher, '/create/valid/?token=' . $Session->get_token());
	}
	
	public static function menu_configuration_edit($id)
	{
		return Dispatcher::get_url(self::$dispatcher, $this->id . '/edit/');
	}
	
	public static function menu_configuration_edit_valid($id)
	{
		return Dispatcher::get_url(self::$dispatcher, $this->id . '/edit/valid/?token=' . $Session->get_token());
	}
	
	public static function menu_configuration_edit_delete($id)
	{
		return Dispatcher::get_url(self::$dispatcher, $this->id . '/delete/?token=' . $Session->get_token());
	}
	
	public static function menu_configuration_edit_add($id)
	{
		return Dispatcher::get_url(self::$dispatcher, $this->id . '/add/');
	}
}
?>