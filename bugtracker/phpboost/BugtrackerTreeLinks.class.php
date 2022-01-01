<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 05
 * @since       PHPBoost 3.0 - 2013 11 25
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BugtrackerTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get_all_langs('bugtracker');
		$tree = new ModuleTreeLinks();

		$tree->add_link(new ModuleLink($lang['bugtracker.add.item'], BugtrackerUrlBuilder::add(), BugtrackerAuthorizationsService::check_authorizations()->write()));

		$tree->add_link(new AdminModuleLink($lang['form.configuration'], BugtrackerUrlBuilder::configuration()));
		$tree->add_link(new AdminModuleLink($lang['form.authorizations'], BugtrackerUrlBuilder::authorizations()));

		if (ModulesManager::get_module('bugtracker')->get_configuration()->get_documentation())
			$tree->add_link(new ModuleLink($lang['form.documentation'], ModulesManager::get_module('bugtracker')->get_configuration()->get_documentation(), BugtrackerAuthorizationsService::check_authorizations()->write()));

		return $tree;
	}
}
?>
