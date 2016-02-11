<?php
/*##################################################
 *                               DownloadTreeLinks.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class DownloadTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'download');
		$tree = new ModuleTreeLinks();
		
		$manage_categories_link = new AdminModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), DownloadUrlBuilder::manage_categories());
		$manage_categories_link->add_sub_link(new AdminModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), DownloadUrlBuilder::manage_categories()));
		$manage_categories_link->add_sub_link(new AdminModuleLink(LangLoader::get_message('category.add', 'categories-common'), DownloadUrlBuilder::add_category(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY))));
		$tree->add_link($manage_categories_link);
		
		$manage_link = new AdminModuleLink($lang['download.manage'], DownloadUrlBuilder::manage());
		$manage_link->add_sub_link(new AdminModuleLink($lang['download.manage'], DownloadUrlBuilder::manage()));
		$manage_link->add_sub_link(new AdminModuleLink($lang['download.actions.add'], DownloadUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY))));
		$tree->add_link($manage_link);
		
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), DownloadUrlBuilder::configuration()));
		
		if (!AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$tree->add_link(new ModuleLink($lang['download.actions.add'], DownloadUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), DownloadAuthorizationsService::check_authorizations()->write() || DownloadAuthorizationsService::check_authorizations()->contribution()));
		}
		
		$tree->add_link(new ModuleLink($lang['download.pending'], DownloadUrlBuilder::display_pending(), DownloadAuthorizationsService::check_authorizations()->write() || DownloadAuthorizationsService::check_authorizations()->contribution() || DownloadAuthorizationsService::check_authorizations()->moderation()));
		
		return $tree;
	}
}
?>
