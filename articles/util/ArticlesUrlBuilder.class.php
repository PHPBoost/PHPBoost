<?php
/*##################################################
 *                       ArticlesUrlBuilder.class.php
 *                            -------------------
 *   begin                : March 04, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
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

/**
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
class ArticlesUrlBuilder
{
	private static $dispatcher = '/articles';
	
	public static function manage_categories()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/');
	}
	
	public static function add_category()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/add/');
	}
	
	public static function edit_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/'. $id .'/edit/');
	}
	
	public static function delete_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/'. $id .'/delete/');
	}
	
	public static function display_category($id, $rewrited_name)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '-' . $rewrited_name . '/');
	}
	
	public static function print_article($title = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/print/' . $title);
	}
	
	public static function articles_configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}
	
	public static function add_article()
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/');
	}
	
	public static function edit_article($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/');
	}
	
	public static function delete_article($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/');
	}
	
	public static function display_article($id_category, $rewrited_name_category, $id_article, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id_article . '-' .$rewrited_title . '/');
	}
	
	public static function display_pending_articles()
	{
		return DispatchManager::get_url(self::$dispatcher, '/pending/');
	}
	
	public static function home($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $param);
	}
}
?>