<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 04 25
 * @since       PHPBoost 3.0 - 2011 10 08
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class StatsExtensionPointProvider extends ModuleExtensionPointProvider
{
	public function scheduled_jobs()
	{
		return new StatsScheduledJobs();
	}

	public function js_files()
	{
		$js_file = new ModuleJsFiles();
		$js_file->adding_running_module_displayed_file('chart.min.js');
		return $js_file;
	}
}
?>
