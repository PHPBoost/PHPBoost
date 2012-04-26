<?php
/*##################################################
 *                           HomePagePluginsService.class.php
 *                            -------------------
 *   begin                : March 17, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class HomePagePluginsService
{
	private static $db_querier;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}
	
	public static function add(Array $columns)
	{
		$result = self::$db_querier->insert(HomePageSetup::$home_page_table, $columns);
		return $result->get_last_inserted_id();
	}
	
	public static function get_next_position($block)
	{
		$column = 'MAX(position) + 1 AS position';
		$condition = 'WHERE block=:block';
		$parameters = array('block' => $block);
		return (int) self::$db_querier->get_column_value(DB_TABLE_MENUS, $column, $condition, $parameters);
	}
	
	public static function delete($condition, Array $parameters)
	{
		self::$db_querier->delete(HomePageSetup::$home_page_table, $condition, $parameters);
	}
	
	public static function get_installed_plugins()
	{
		return HomePagePluginsCache::load()->get_plugins();
	}
}
?>