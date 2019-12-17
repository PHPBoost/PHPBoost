<?php
/**
 * @package     Core
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 10 16
*/

abstract class AbstractScheduledJobExtensionPoint implements ScheduledJobExtensionPoint
{
	/**
	 * {@inheritDoc}
	 */
	public function on_changeday(Date $yesterday, Date $today) { }

	public function on_changepage() { }

	public function on_new_session($new_visitor, $is_robot) { }
}
?>
