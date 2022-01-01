<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 11 25
 * @since       PHPBoost 3.0 - 2013 02 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class SandboxExtensionPointProvider extends ModuleExtensionPointProvider
{
	public function css_files()
	{
		$module_css_files = parent::css_files();
		$module_css_files->adding_always_displayed_file('/templates/__default__/theme/icoboost/icoboost.css');

		if (ModulesManager::is_module_installed('wiki') && ModulesManager::is_module_activated('wiki'))
			$module_css_files->adding_running_module_displayed_file('wiki.css', 'wiki');

		return $module_css_files;
	}
}
?>
