<?php
/*##################################################
 *		                         MediaTreeLinks.class.php
 *                            -------------------
 *   begin                : November 24, 2013
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
class MediaTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'media');
		$tree = new ModuleTreeLinks();
		
		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), MediaUrlBuilder::manage_categories(), MediaAuthorizationsService::check_authorizations()->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), MediaUrlBuilder::manage_categories(), MediaAuthorizationsService::check_authorizations()->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), MediaUrlBuilder::add_category(), MediaAuthorizationsService::check_authorizations()->manage_categories()));
		$tree->add_link($manage_categories_link);
		
		$manage_media_link = new ModuleLink($lang['media.manage'], MediaUrlBuilder::manage(), MediaAuthorizationsService::check_authorizations()->moderation());
		$manage_media_link->add_sub_link(new ModuleLink($lang['media.manage'], MediaUrlBuilder::manage(), MediaAuthorizationsService::check_authorizations()->moderation()));
		$manage_media_link->add_sub_link(new ModuleLink($lang['media.actions.add'], MediaUrlBuilder::add(), MediaAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link($manage_media_link);
		
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), MediaUrlBuilder::configuration()));
		
		if (!MediaAuthorizationsService::check_authorizations()->moderation())
		{
			$tree->add_link(new ModuleLink($lang['media.actions.add'], MediaUrlBuilder::add(), MediaAuthorizationsService::check_authorizations()->write() || MediaAuthorizationsService::check_authorizations()->contribution()));
		}
		
		return $tree;
	}
}
?>