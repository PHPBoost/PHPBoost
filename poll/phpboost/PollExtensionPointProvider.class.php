<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 11 24
 * @since       PHPBoost 2.0 - 2008 07 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class PollExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('poll');
	}

	public function home_page()
	{
		return new PollHomePageExtensionPoint();
	}

	public function menus()
	{
		return new ModuleMenus(array(new PollModuleMiniMenu()));
	}

	public function tree_links()
	{
		return new PollTreeLinks();
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('poll_mini.css');
		$module_css_files->adding_running_module_displayed_file('poll.css');
		return $module_css_files;
	}

}
?>
