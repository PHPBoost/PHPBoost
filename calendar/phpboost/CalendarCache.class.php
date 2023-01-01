<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 22
 * @since       PHPBoost 4.0 - 2013 08 24
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarCache implements CacheData
{
	private $items = array();

	public function synchronize()
	{
		$year = date('Y');
		$month = date('n');
		$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;
		$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
		$month_days = $array_month[$month - 1];

		$this->items = CalendarService::get_all_current_month_items($month, $year, $month_days);
	}

	public function get_items()
	{
		return $this->items;
	}

	/**
	 * Loads and returns current month items cached data.
	 * @return CalendarCurrentMonthEventsCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'calendar', 'currentmonthevents');
	}

	/**
	 * Invalidates the current Calendar month items cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('calendar', 'currentmonthevents');
	}
}
?>
