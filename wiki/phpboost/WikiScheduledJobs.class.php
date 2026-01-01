<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 04 13
 * @since       PHPBoost 6.0 - 2022 11 18
 */

class WikiScheduledJobs extends AbstractScheduledJobExtensionPoint
{
    public function on_changepage()
    {
        $config = WikiConfig::load();
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
                WikiService::clear_cache();

                $config->set_deferred_operations($deferred_operations);
                WikiConfig::save();
            }
        }
    }
}
?>
