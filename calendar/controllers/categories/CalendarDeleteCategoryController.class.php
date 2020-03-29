<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 11
 * @since       PHPBoost 3.0 - 2013 02 25
*/

class CalendarDeleteCategoryController extends DefaultDeleteCategoryController
{
	protected function clear_cache()
	{
		return CalendarCurrentMonthEventsCache::invalidate();
	}
}
?>
