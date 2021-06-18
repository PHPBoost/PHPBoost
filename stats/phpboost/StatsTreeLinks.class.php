<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 18
 * @since       PHPBoost 5.2 - 2019 12 15
*/

class StatsTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('form.configuration', 'form-lang'), new Url('/stats/admin_stats.php')));
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('form.documentation', 'form-lang'), ModulesManager::get_module('stats')->get_configuration()->get_documentation()));

		return $tree;
	}
}
?>
