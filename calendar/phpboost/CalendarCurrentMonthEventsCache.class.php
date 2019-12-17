<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 08 24
*/

class CalendarCurrentMonthEventsCache implements CacheData
{
	private $events = array();

	public function synchronize()
	{
		$year = date('Y');
		$month = date('n');
		$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;
		$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
		$month_days = $array_month[$month - 1];

		$result = CalendarService::get_all_current_month_events($month, $year, $month_days);
		while ($row = $result->fetch())
		{
			$this->events[] = $row;
		}
	}

	public function get_events()
	{
		return $this->events;
	}

	/**
	 * Loads and returns current month events cached data.
	 * @return CalendarCurrentMonthEventsCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'calendar', 'currentmonthevents');
	}

	/**
	 * Invalidates the current Calendar month events cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('calendar', 'currentmonthevents');
	}
}
?>
