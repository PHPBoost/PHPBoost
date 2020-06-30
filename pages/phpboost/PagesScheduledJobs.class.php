<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 06 30
 * @since       PHPBoost 5.2 - 2020 06 15
*/

class PagesScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	public function on_changepage()
	{
		$config = PagesConfig::load();
		$deferred_operations = $config->get_deferred_operations();

		if (!empty($deferred_operations))
		{
			$now = new Date();
			$is_modified = false;

			foreach ($deferred_operations as $id => $timestamp)
			{
				if ($timestamp <= $now->get_timestamp())
				{
					unset($deferred_operations[$id]);
					$is_modified = true;
				}
			}

			if ($is_modified)
			{
				PagesService::clear_cache();

				$config->set_deferred_operations($deferred_operations);
				PagesConfig::save();
			}
		}
	}
}
?>
