<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 17
 * @since       PHPBoost 6.0 - 2021 10 22
*/

class HistoryScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	/**
	 * {@inheritDoc}
	 */
	public function on_changeday(Date $yesterday, Date $today)
	{
		$config = HistoryConfig::load();

		if ($config->get_log_retention_period())
		{
			$now = new Date();
			HistoryManager::delete('WHERE creation_date < :timestamp', array('timestamp' => ($now->get_timestamp() - $config->get_log_retention_period())));
		}
	}
}
?>
