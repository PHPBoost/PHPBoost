<?php
/*##################################################
 *                          CalendarUrlBuilder.class.php
 *                            -------------------
 *   begin                : November 20, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 * @desc Url builder of the calendar module
 */
class CalendarUrlBuilder
{
	private static $dispatcher = '/calendar';
	
	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}
	
	/**
	 * @return Url
	 */
	public static function configuration_success()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/success');
	}
	
	/**
	 * @return Url
	 */
	public static function manage_events($sort_field = null, $sort_mode = null, $page = null)
	{
		$page = $page !== null ? $page . '/': '';
		$sort = $sort_field !== null ? $sort_field . '/' . $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/admin/manage/' . $sort . $page);
	}
	
	/**
	 * @return Url
	 */
	public static function add_category()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/add/');
	}
	
	/**
	 * @return Url
	 */
	public static function edit_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/'. $id .'/edit/');
	}
	
	/**
	 * @return Url
	 */
	public static function delete_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/'. $id .'/delete/');
	}
	
	/**
	 * @return Url
	 */
	public static function manage_categories()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/');
	}
	
	/**
	 * @return Url
	 */
	public static function display_event($category_id, $rewrited_name_category, $event_id, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $category_id . '-' . $rewrited_name_category . '/' . $event_id . '-' . $rewrited_title . '/');
	}
	
	/**
	 * @return Url
	 */
	public static function display_event_comments($category_id, $rewrited_name_category, $event_id, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $category_id . '-' . $rewrited_name_category . '/' . $event_id . '-' . $rewrited_title . '#comments_list');
	}
	
	/**
	 * @return Url
	 */
	public static function display_category($id, $rewrited_name)
	{
		return DispatchManager::get_url(self::$dispatcher, '/category/' . $id . '-' . $rewrited_name .'/');
	}
	
	/**
	 * @return Url
	 */
	public static function add_event($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function edit_event($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/edit/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function delete_event($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/delete/' . $param . '/?token=' . AppContext::get_session()->get_token());
	}
	
	/**
	 * @return Url
	 */
	public static function ajax_month_calendar($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax_month_calendar/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function ajax_change_participation($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax_change_participation/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function home($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $param);
	}
	
	/**
	 * @return Url
	 */
	public static function error($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/error/' . $param);
	}
}
?>
