<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 02 24
 * @since       PHPBoost 3.0 - 2013 12 03
 * @contributor xela <xela@phpboost.com>
*/

class WikiTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		global $LANG;
		load_module_lang('wiki'); //Chargement de la langue du module.
		require_once(PATH_TO_ROOT . '/wiki/wiki_auth.php');
		$id_cat = AppContext::get_request()->get_getstring('id_cat', 0);
		$current_user = AppContext::get_current_user();
		$config = WikiConfig::load();

		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), new Url('/wiki/admin_wiki.php')));
		$tree->add_link(new AdminModuleLink($LANG['authorizations'], new Url('/wiki/admin_wiki_groups.php')));

		$tree->add_link(new ModuleLink($LANG['wiki_create_article'], new Url('/wiki/post.php' . ($id_cat > 0 ? '?id_parent=' . $id_cat : '')), $current_user->check_auth($config->get_authorizations(), WIKI_CREATE_ARTICLE)));
		$tree->add_link(new ModuleLink($LANG['wiki_create_cat'], new Url('/wiki/post.php?type=cat' . ($id_cat > 0 ? '&amp;id_parent=' . $id_cat : '')), $current_user->check_auth($config->get_authorizations(), WIKI_CREATE_CAT)));

		if ($current_user->check_level(User::MEMBER_LEVEL))
		{
			$tree->add_link(new ModuleLink($LANG['wiki_followed_articles'], new Url('/wiki/favorites.php')));
		}

		$tree->add_link(new ModuleLink($LANG['wiki_explorer_short'], new Url('/wiki/explorer.php')));
		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('wiki')->get_configuration()->get_documentation(), $current_user->check_auth($config->get_authorizations(), WIKI_CREATE_CAT) || $current_user->check_auth($config->get_authorizations(), WIKI_CREATE_CAT)));

		return $tree;
	}
}
?>
