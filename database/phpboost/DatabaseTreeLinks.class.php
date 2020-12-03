<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 03
 * @since       PHPBoost 4.0 - 2013 11 26
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DatabaseTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'database');
		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink($lang['database.management'], DatabaseUrlBuilder::database_management()));
		$tree->add_link(new AdminModuleLink($lang['database.sql.queries'], DatabaseUrlBuilder::db_sql_queries()));
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), DatabaseUrlBuilder::configuration()));
		if (ModulesManager::get_module('database')->get_configuration()->get_documentation())
			$tree->add_link(new AdminModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('database')->get_configuration()->get_documentation()));

		return $tree;
	}
}
?>
