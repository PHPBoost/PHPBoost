<?php
/**
 * @package     Core
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 10 16
*/

interface ScheduledJobExtensionPoint extends ExtensionPoint
{
	const EXTENSION_POINT = 'scheduled_jobs';

	/**
	 * Execute daily commands. This extension point is called by the first request of the day.
	 * @param Date $yesterday date representing yesterday
	 * @param Date $today date representing today
	 */
	function on_changeday(Date $yesterday, Date $today);

	/**
	 * Execute commands to change page.
	 */
	function on_changepage();

	/**
	 * Execute commands to new session start.
	 * @param bool $new_visitor true if the new session start with a new visitor
	 * @param bool $is_robot true if the new session start with a robot visitor
	 */
	function on_new_session($new_visitor, $is_robot);
}
?>
