<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 07 20
 * @since       PHPBoost 4.0 - 2013 11 13
 * @contributor xela <xela@phpboost.com>
*/

class SearchTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		global $LANG;
		load_module_lang('search'); //Chargement de la langue du module.

		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink($LANG['search_config'], new Url('/search/admin_search.php')));
		$tree->add_link(new AdminModuleLink($LANG['weights.manage'], new Url('/search/admin_search.php?weighting=true')));
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('search')->get_configuration()->get_documentation()));

		return $tree;
	}
}
?>
