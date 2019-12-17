<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 11 26
*/

class DatabaseTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'database');
		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink($lang['database.actions.database_management'], DatabaseUrlBuilder::database_management()));
		$tree->add_link(new AdminModuleLink($lang['database.actions.db_sql_queries'], DatabaseUrlBuilder::db_sql_queries()));
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), DatabaseUrlBuilder::configuration()));

		return $tree;
	}
}
?>
