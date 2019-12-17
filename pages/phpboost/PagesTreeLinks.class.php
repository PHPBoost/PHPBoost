<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 02 24
 * @since       PHPBoost 4.0 - 2016 11 25
 * @contributor xela <xela@phpboost.com>
*/

class PagesTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		global $LANG;
		load_module_lang('pages'); //Chargement de la langue du module.
		require_once(PATH_TO_ROOT . '/pages/pages_defines.php');
		$current_user = AppContext::get_current_user();
		$config = PagesConfig::load();

		$tree = new ModuleTreeLinks();

		$manage_ranks_link = new AdminModuleLink($LANG['pages_manage'], new Url('/pages/pages.php'));
		$manage_ranks_link->add_sub_link(new AdminModuleLink($LANG['pages_manage'], new Url('/pages/pages.php')));
		$manage_ranks_link->add_sub_link(new AdminModuleLink($LANG['pages_create'], new Url('/pages/post.php')));
		$tree->add_link($manage_ranks_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), new Url('/pages/admin_pages.php')));

		if (!$current_user->check_level(User::ADMIN_LEVEL))
		{
			$tree->add_link(new ModuleLink($LANG['pages_create'], new Url('/pages/post.php'), $current_user->check_auth($config->get_authorizations(), EDIT_PAGE)));
		}

		$tree->add_link(new ModuleLink($LANG['pages_redirection_manage'], new Url('/pages/action.php'), $current_user->check_auth($config->get_authorizations(), EDIT_PAGE)));
		$tree->add_link(new ModuleLink($LANG['pages_explorer'], new Url('/pages/explorer.php'), $current_user->check_auth($config->get_authorizations(), EDIT_PAGE)));

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('pages')->get_configuration()->get_documentation(), $current_user->check_auth($config->get_authorizations(), EDIT_PAGE)));

		return $tree;
	}
}
?>
