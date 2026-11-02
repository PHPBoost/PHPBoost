<?php
/**
 * @package     Core
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 02
 * @since       PHPBoost 3.0 - 2010 10 16
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractScheduledJobExtensionPoint implements ScheduledJobExtensionPoint
{
	/**
	 * {@inheritDoc}
	 */
	public function on_changeday(Date $yesterday, Date $today): void { }

	public function on_changepage(): void { }

	public function on_new_session(mixed $new_visitor, mixed $is_robot): void { }
}
?>
