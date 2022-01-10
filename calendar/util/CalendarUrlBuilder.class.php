<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 01 10
 * @since       PHPBoost 3.0 - 2012 11 20
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
	public static function manage_items()
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}

	/**
	 * @return Url
	 */
	public static function display_category($id, $rewrited_name, $year = null, $month = null, $day = null)
	{
		$category = $id > 0 ? $id . '-' . $rewrited_name . '/' : '';
		$year = $year !== null ? $year . '/' : '';
		$month = $month !== null ? $month . '/' : '';
		$day = $day !== null ? $day . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $category . ($id > 0 ? $year . $month . $day : ''));
	}

	/**
	 * @return Url
	 */
	public static function display($id_category, $rewrited_name_category, $item_id, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $item_id . '-' . $rewrited_title . '/');
	}

	/**
	 * @return Url
	 */
	public static function display_item_comments($id_category, $rewrited_name_category, $item_id, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $item_id . '-' . $rewrited_title . '/#comments-list');
	}

	/**
	 * @return Url
	 */
	public static function display_pending_items($page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/pending/' . $page);
	}

	public static function display_member_items($user_id, $page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/member/' . $user_id . '/'  . $page);
	}

	/**
	 * @return Url
	 */
	public static function add_item($year = null, $month = null, $day = null)
	{
		$year = $year !== null ? $year . '/' : '';
		$month = $month !== null ? $month . '/' : '';
		$day = $day !== null ? $day . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $year . $month . $day);
	}

	/**
	 * @return Url
	 */
	public static function edit_item($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/');
	}

	/**
	 * @return Url
	 */
	public static function delete_item($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?' . 'token=' . AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function suscribe_item($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/suscribe/');
	}

	/**
	 * @return Url
	 */
	public static function unsuscribe_item($id)
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
	public static function display_items_list($year = null, $month = null, $day = null)
	{
		$now = new Date();
		$date = ($year !== null && $month !== null && $day !== null ? (sprintf("%04d", $year) . '-' . sprintf("%02d", $month) . '-' . sprintf("%02d", $day)) : ($now->get_year() . '-' . sprintf("%02d", $now->get_month()) . '-' . sprintf("%02d", $now->get_day())));
		return DispatchManager::get_url(self::$dispatcher, '/items_list/?items-list=,filters:{filter1:' . $date . '}');
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
