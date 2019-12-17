<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2011 09 19
 * @contributor Arnaud GENET <elenwii@phpboost.com>
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
		$module_css_files->adding_always_displayed_file('online.css');
		return $module_css_files;
	}

	public function home_page()
	{
		return new OnlineHomePageExtensionPoint();
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
