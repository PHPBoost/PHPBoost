<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 11 23
 * @since       PHPBoost 3.0 - 2010 10 16
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ShoutboxScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	/**
	 * {@inheritDoc}
	 */
	public function on_changeday(Date $yesterday, Date $today)
	{
		$config = ShoutboxConfig::load();

		if ($config->is_max_messages_number_enabled())
		{
			ShoutboxService::delete('WHERE id NOT IN (SELECT * FROM (SELECT id FROM ' . ShoutboxSetup::$shoutbox_table . ' ORDER BY id DESC LIMIT ' . $config->get_max_messages_number() . ') AS temp)');
		}
	}
}
?>
