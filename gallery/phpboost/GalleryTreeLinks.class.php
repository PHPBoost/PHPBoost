<?php
/*##################################################
 *		                         GalleryTreeLinks.class.php
 *                            -------------------
 *   begin                : December 4, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */
class GalleryTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		global $LANG, $Cache, $CAT_GALLERY;
		load_module_lang('gallery'); //Chargement de la langue du module.
		$Cache->load('gallery');
		$id_cat = AppContext::get_request()->get_getstring('cat', 0);
		$current_user = AppContext::get_current_user();
		
		$tree = new ModuleTreeLinks();
		
		$manage_categories_link = new AdminModuleLink($LANG['admin.categories.manage'], new Url('/gallery/admin_gallery_cat.php'));
		$manage_categories_link->add_sub_link(new AdminModuleLink($LANG['admin.categories.manage'], new Url('/gallery/admin_gallery_cat.php')));
		$manage_categories_link->add_sub_link(new AdminModuleLink($LANG['gallery_cats_add'], new Url('/gallery/admin_gallery_cat_add.php')));
		$tree->add_link($manage_categories_link);
		
		$manage_gallery_link = new AdminModuleLink($LANG['gallery.manage'], new Url('/gallery/admin_gallery.php'));
		$manage_gallery_link->add_sub_link(new AdminModuleLink($LANG['gallery.manage'], new Url('/gallery/admin_gallery.php')));
		$manage_gallery_link->add_sub_link(new AdminModuleLink($LANG['add_pic'], new Url('/gallery/admin_gallery_add.php')));
		$tree->add_link($manage_gallery_link);
		
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), new Url('/gallery/admin_gallery_config.php')));
		
		if (!AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$tree->add_link(new ModuleLink($LANG['add_pic'], GalleryUrlBuilder::get_link_cat_add($id_cat), $current_user->check_auth($CAT_GALLERY[$id_cat]['auth'], GalleryAuthorizationsService::WRITE_AUTHORIZATIONS)));
		}
		
		return $tree;
	}
}
?>