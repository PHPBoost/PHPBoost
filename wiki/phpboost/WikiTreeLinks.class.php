<?php
/*##################################################
 *		                         WikiTreeLinks.class.php
 *                            -------------------
 *   begin                : December 3, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
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
		
		return $tree;
	}
}
?>