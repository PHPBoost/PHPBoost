<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 09
 * @since       PHPBoost 3.0 - 2012 02 22
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class LangsSwitcherExtensionPointProvider extends ExtensionPointProvider
{
    function __construct()
    {
        parent::__construct('LangsSwitcher');
    }

	public function menus()
	{
		return new ModuleMenus(array(
			new LangsSwitcherModuleMiniMenu()
		));
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('langsswitcher_mini.css');
		return $module_css_files;
	}

}
?>
