<?php
/*##################################################
 *                           CalendarCurrentMonthEventsCache.class.php
 *                            -------------------
 *   begin                : August 24, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
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
