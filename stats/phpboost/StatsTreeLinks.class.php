<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 21
 * @since       PHPBoost 5.2 - 2019 12 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class StatsTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('form.configuration', 'form-lang'), StatsUrlBuilder::configuration()));
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('form.documentation', 'form-lang'), ModulesManager::get_module('stats')->get_configuration()->get_documentation()));

		return $tree;
	}
}
?>
