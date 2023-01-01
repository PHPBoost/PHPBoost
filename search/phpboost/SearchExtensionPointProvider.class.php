<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 2.0 - 2008 07 07
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class SearchExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('search');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('search_mini.css');
		$module_css_files->adding_running_module_displayed_file('search.css');
		return $module_css_files;
	}

	public function menus()
	{
		return new ModuleMenus(array(new SearchModuleMiniMenu()));
	}

	public function scheduled_jobs()
	{
		return new SearchScheduledJobs();
	}

	public function tree_links()
	{
		return new SearchTreeLinks();
	}
}
?>
