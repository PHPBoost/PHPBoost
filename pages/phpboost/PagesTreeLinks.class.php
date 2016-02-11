<?php
/*##################################################
 *		                         PagesTreeLinks.class.php
 *                            -------------------
 *   begin                : November 25, 2013
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
		
		return $tree;
	}
}
?>