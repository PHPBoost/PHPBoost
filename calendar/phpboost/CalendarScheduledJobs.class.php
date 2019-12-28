<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 28
 * @since       PHPBoost 4.0 - 2013 08 23
*/

class CalendarScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	/**
	 * {@inheritDoc}
	 */
	public function on_changeday(Date $yesterday, Date $today)
	{
		if ($today->get_day() == 1)
			CalendarCache::invalidate();
	}
}
?>
