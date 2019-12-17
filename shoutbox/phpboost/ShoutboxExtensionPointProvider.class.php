<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 2.0 - 2008 07 07
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ShoutboxExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('shoutbox');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('shoutbox.css');
		$module_css_files->adding_always_displayed_file('shoutbox_mini.css');
		return $module_css_files;
	}

	public function home_page()
	{
		return new ShoutboxHomePageExtensionPoint();
	}

	public function menus()
	{
		return new ModuleMenus(array(new ShoutboxModuleMiniMenu()));
	}

	public function scheduled_jobs()
	{
		return new ShoutboxScheduledJobs();
	}

	public function tree_links()
	{
		return new ShoutboxTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/shoutbox/index.php')));
	}
}
?>
