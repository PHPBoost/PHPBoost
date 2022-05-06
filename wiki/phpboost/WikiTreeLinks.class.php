<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 06
 * @since       PHPBoost 3.0 - 2013 12 03
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WikiTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get_all_langs('wiki');
		require_once(PATH_TO_ROOT . '/wiki/wiki_auth.php');
		$id_cat = AppContext::get_request()->get_getstring('id_cat', 0);
		$current_user = AppContext::get_current_user();
		$config = WikiConfig::load();

		$tree = new ModuleTreeLinks();

		$tree->add_link(new ModuleLink($lang['wiki.explorer.short'], new Url('/wiki/explorer.php')));

		$tree->add_link(new ModuleLink($lang['wiki.category.add'], new Url('/wiki/post.php?type=cat' . ($id_cat > 0 ? '&amp;id_parent=' . $id_cat : '')), $current_user->check_auth($config->get_authorizations(), WIKI_CREATE_CAT)));
		$tree->add_link(new ModuleLink($lang['wiki.item.add'], new Url('/wiki/post.php' . ($id_cat > 0 ? '?id_parent=' . $id_cat : '')), $current_user->check_auth($config->get_authorizations(), WIKI_CREATE_ARTICLE)));

		if ($current_user->check_level(User::MEMBER_LEVEL))
		{
			$tree->add_link(new ModuleLink($lang['wiki.tracked.items'], new Url('/wiki/favorites.php')));
		}

		$tree->add_link(new AdminModuleLink($lang['form.configuration'], new Url('/wiki/admin_wiki.php')));
		$tree->add_link(new AdminModuleLink($lang['form.authorizations'], new Url('/wiki/admin_wiki_groups.php')));

		if (ModulesManager::get_module('wiki')->get_configuration()->get_documentation())
			$tree->add_link(new ModuleLink($lang['form.documentation'], ModulesManager::get_module('wiki')->get_configuration()->get_documentation(), $current_user->check_auth($config->get_authorizations(), WIKI_CREATE_CAT) || $current_user->check_auth($config->get_authorizations(), WIKI_CREATE_CAT)));

		return $tree;
	}
}
?>
