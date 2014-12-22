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
	const DEFAULT_SORT_FIELD = 'date';
	const DEFAULT_SORT_MODE = 'desc';
	
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
	public static function manage_events($sort_field = self::DEFAULT_SORT_FIELD, $sort_mode = self::DEFAULT_SORT_MODE, $page = 1)
	{
		$page = $page !== 1 ? $page . '/': '';
		$sort_field = $sort_field !== self::DEFAULT_SORT_FIELD ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== self::DEFAULT_SORT_MODE ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/admin/manage/' . $sort_field . $sort_mode . $page);
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
		return DispatchManager::get_url(self::$dispatcher, '/' . $category_id . '-' . $rewrited_name_category . '/' . $event_id . '-' . $rewrited_title, true);
	}
	
	/**
	 * @return Url
	 */
	public static function display_pending_events($page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/pending/' . $page);
	}
	
	/**
	 * @return Url
	 */
	public static function add_event($year = null, $month = null, $day = null)
	{
		$year = $year !== null ? $year . '/' : '';
		$month = $month !== null ? $month . '/' : '';
		$day = $day !== null ? $day . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $year . $month . $day);
	}
	
	/**
	 * @return Url
	 */
	public static function edit_event($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/edit/' . $id);
	}
	
	/**
	 * @return Url
	 */
	public static function delete_event($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/delete/' . $id . '/?token=' . AppContext::get_session()->get_token());
	}
	
	/**
	 * @return Url
	 */
	public static function suscribe_event($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/suscribe/' . $id);
	}
	
	/**
	 * @return Url
	 */
	public static function unsuscribe_event($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/unsuscribe/' . $id);
	}
	
	/**
	 * @return Url
	 */
	public static function ajax_month_calendar()
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax_month_calendar/');
	}
	
	/**
	 * @return Url
	 */
	public static function ajax_month_events()
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax_month_events/');
	}
	
	/**
	 * @return Url
	 */
	public static function home($year = null, $month = null, $day = null, $calendar_anchor = false)
	{
		$year = $year !== null ? $year . '/' : '';
		$month = $month !== null ? $month . '/' : '';
		$day = $day !== null ? $day . '/' : '';
		$calendar_anchor = $calendar_anchor !== false ? '#calendar' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $year . $month . $day . $calendar_anchor);
	}
}
?>
