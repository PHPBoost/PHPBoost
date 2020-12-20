<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 20
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
	public static function u_configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}

	/**
	 * @return Url
	 */
	public static function u_manage_items()
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}

	/**
	 * @return Url
	 */
	public static function u_item($category_id, $rewrited_name_category, $item_id, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $category_id . '-' . $rewrited_name_category . '/' . $item_id . '-' . $rewrited_title . '/');
	}

	/**
	 * @return Url
	 */
	public static function u_item_comments($category_id, $rewrited_name_category, $item_id, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $category_id . '-' . $rewrited_name_category . '/' . $item_id . '-' . $rewrited_title . '/#comments-list');
	}

	/**
	 * @return Url
	 */
	public static function u_pending_items($page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/pending/' . $page);
	}

	public static function u_member_items($page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/my_items/' . $page);
	}

	/**
	 * @return Url
	 */
	public static function u_add_item($year = null, $month = null, $day = null)
	{
		$year = $year !== null ? $year . '/' : '';
		$month = $month !== null ? $month . '/' : '';
		$day = $day !== null ? $day . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $year . $month . $day);
	}

	/**
	 * @return Url
	 */
	public static function u_edit_item($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/');
	}

	/**
	 * @return Url
	 */
	public static function u_delete_item($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?' . 'token=' . AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function u_suscribe_item($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/suscribe/');
	}

	/**
	 * @return Url
	 */
	public static function u_unsuscribe_item($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/unsuscribe/');
	}

	/**
	 * @return Url
	 */
	public static function u_ajax_month_calendar()
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax_month_calendar/');
	}

	/**
	 * @return Url
	 */
	public static function u_ajax_month_events()
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax_month_events/');
	}

	/**
	 * @return Url
	 */
	public static function u_items_list($year = null, $month = null, $day = null)
	{
		$now = new Date();
		$date = ($year !== null && $month !== null && $day !== null ? (sprintf("%04d", $year) . '-' . sprintf("%02d", $month) . '-' . sprintf("%02d", $day)) : ($now->get_year() . '-' . sprintf("%02d", $now->get_month()) . '-' . sprintf("%02d", $now->get_day())));
		return DispatchManager::get_url(self::$dispatcher, '/items_list/?table=,filters:{filter1:' . $date . '}');
	}

	/**
	 * @return Url
	 */
	public static function u_home($year = null, $month = null, $day = null, $calendar_anchor = false)
	{
		$year = $year !== null ? $year . '/' : '';
		$month = $month !== null ? $month . '/' : '';
		$day = $day !== null ? $day . '/' : '';
		$calendar_anchor = $calendar_anchor !== false ? '#calendar' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $year . $month . $day . $calendar_anchor);
	}
}
?>
