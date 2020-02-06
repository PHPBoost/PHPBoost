<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 06
 * @since       PHPBoost 3.0 - 2012 11 20
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
		$date = ($year !== null && $month !== null && $day !== null ? (sprintf("%04d", $year) . '-' . sprintf("%02d", $month) . '-' . sprintf("%02d", $day)) : ($now->get_year() . '-' . sprintf("%02d", $now->get_month()) . '-' . sprintf("%02d", $now->get_day())));
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
