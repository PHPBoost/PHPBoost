<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 11 05
 * @since       PHPBoost 3.0 - 2011 10 08
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class StatsExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('stats');
	}

	public function home_page()
	{
		return new StatsHomePageExtensionPoint();
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('stats.css');
		return $module_css_files;
	}

	public function menus()
	{
		return new ModuleMenus(array(new StatsModuleMiniMenu()));
	}

	public function tree_links()
	{
		return new StatsTreeLinks();
	}

	public function scheduled_jobs()
	{
		return new StatsScheduledJobs();
	}
}
?>
