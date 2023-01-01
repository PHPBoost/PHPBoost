<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 22
 * @since       PHPBoost 6.0 - 2021 10 22
*/

class HistoryTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$tree = new ModuleTreeLinks();
		$lang = LangLoader::get('form-lang');
		$module_configuration = ModulesManager::get_module('history')->get_configuration();

		$tree->add_link(new AdminModuleLink($module_configuration->get_name(), HistoryUrlBuilder::display_history()));

		$tree->add_link(new AdminModuleLink($lang['form.configuration'], HistoryUrlBuilder::configuration()));

		if ($module_configuration->get_documentation())
			$tree->add_link(new AdminModuleLink($lang['form.documentation'], $module_configuration->get_documentation()));

		return $tree;
	}
}
?>
