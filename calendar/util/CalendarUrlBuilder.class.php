<?php
/*##################################################
 *                          CalendarUrlBuilder.class.php
 *                            -------------------
 *   begin                : November 20, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
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

/**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
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
	public static function manage_events()
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}
	
	/**
	 * @return Url
	 */
	public static function add_category()
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/add/');
	}
	
	/**
	 * @return Url
	 */
	public static function edit_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/'. $id .'/edit/');
	}
	
	/**
	 * @return Url
	 */
	public static function delete_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/'. $id .'/delete/');
	}
	
	/**
	 * @return Url
	 */
	public static function manage_categories()
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/');
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
		return DispatchManager::get_url(self::$dispatcher, '/' . $category_id . '-' . $rewrited_name_category . '/' . $event_id . '-' . $rewrited_title . '/#comments-list');
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
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/');
	}
	
	/**
	 * @return Url
	 */
	public static function delete_event($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?' . 'token=' . AppContext::get_session()->get_token());
	}
	
	/**
	 * @return Url
	 */
	public static function suscribe_event($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/suscribe/');
	}
	
	/**
	 * @return Url
	 */
	public static function unsuscribe_event($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/unsuscribe/');
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
	public static function events_list($year = null, $month = null, $day = null)
	{
		$now = new Date();
		$date = $year !== null && $month !== null && $day !== null ? sprintf("%04d", $year) . '-' . sprintf("%02d", $month) . '-' . sprintf("%02d", $day) : $now->format(Date::FORMAT_DAY_MONTH_YEAR);
		return DispatchManager::get_url(self::$dispatcher, '/events_list/?table=,filters:{filter1:' . $date . '}');
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
