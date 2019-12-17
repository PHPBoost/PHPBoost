<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 2.0 - 2008 07 07
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class GuestbookExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('guestbook');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('guestbook.css');
		return $module_css_files;
	}

	public function home_page()
	{
		return new GuestbookHomePageExtensionPoint();
	}

	public function menus()
	{
		return new ModuleMenus(array(new GuestbookModuleMiniMenu()));
	}

	public function tree_links()
	{
		return new GuestbookTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/guestbook/index.php')));
	}
}
?>
