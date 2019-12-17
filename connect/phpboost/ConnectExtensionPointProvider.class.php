<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 10 06
*/

class ConnectExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('connect');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('connect_mini.css');
		return $module_css_files;
	}

	public function menus()
	{
		return new ModuleMenus(array(
			new ConnectModuleMiniMenu()
		));
	}
}
?>
