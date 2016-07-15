<?php
/*##################################################
 *		                         GalleryTreeLinks.class.php
 *                            -------------------
 *   begin                : December 4, 2013
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
class GalleryTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'gallery');
		$tree = new ModuleTreeLinks();
		
		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), GalleryUrlBuilder::manage_categories(), GalleryAuthorizationsService::check_authorizations()->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), GalleryUrlBuilder::manage_categories(), GalleryAuthorizationsService::check_authorizations()->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), GalleryUrlBuilder::add_category(), GalleryAuthorizationsService::check_authorizations()->manage_categories()));
		$tree->add_link($manage_categories_link);
		
		$manage_gallery_link = new AdminModuleLink($lang['gallery.manage'], GalleryUrlBuilder::manage());
		$manage_gallery_link->add_sub_link(new AdminModuleLink($lang['gallery.manage'], GalleryUrlBuilder::manage()));
		$manage_gallery_link->add_sub_link(new AdminModuleLink($lang['gallery.actions.add'], GalleryUrlBuilder::admin_add(AppContext::get_request()->get_getstring('id_category', 0))));
		$tree->add_link($manage_gallery_link);
		
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), GalleryUrlBuilder::configuration()));
		
		if (!AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$tree->add_link(new ModuleLink($lang['gallery.actions.add'], GalleryUrlBuilder::add(AppContext::get_request()->get_getstring('id_category', 0)), GalleryAuthorizationsService::check_authorizations()->write()));
		}
		
		return $tree;
	}
}
?>
