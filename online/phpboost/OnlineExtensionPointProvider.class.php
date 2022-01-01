<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 27
 * @since       PHPBoost 3.0 - 2011 09 19
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class OnlineExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('online');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('online.css');
		$module_css_files->adding_always_displayed_file('online_mini.css');
		return $module_css_files;
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), OnlineHomeController::get_view());
	}

	public function menus()
	{
		return new ModuleMenus(array(new OnlineModuleMiniMenu()));
	}

	public function tree_links()
	{
		return new OnlineTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/online/index.php')));
	}
}
?>
