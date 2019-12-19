<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 09
 * @since       PHPBoost 4.1 - 2015 12 15
*/

class WebScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	public function on_changepage()
	{
		$config = WebConfig::load();
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
				Feed::clear_cache('web');
				WebCache::invalidate();
				WebCategoriesCache::invalidate();
				KeywordsCache::invalidate();

				$config->set_deferred_operations($deferred_operations);
				WebConfig::save();
			}
		}
	}
}
?>
