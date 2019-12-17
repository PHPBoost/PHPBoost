<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 02 24
 * @since       PHPBoost 3.0 - 2013 11 25
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class BugtrackerTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'bugtracker');
		$tree = new ModuleTreeLinks();

		$tree->add_link(new ModuleLink($lang['actions.add'], BugtrackerUrlBuilder::add(), BugtrackerAuthorizationsService::check_authorizations()->write()));

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), BugtrackerUrlBuilder::configuration()));
		$tree->add_link(new AdminModuleLink($lang['titles.admin.authorizations.manage'], BugtrackerUrlBuilder::authorizations()));
		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('bugtracker')->get_configuration()->get_documentation(), BugtrackerAuthorizationsService::check_authorizations()->write()));

		return $tree;
	}
}
?>
