<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 02 24
 * @since   	PHPBoost 4.0 - 2013 12 04
 * @contributor xela <xela@phpboost.com>
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

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('gallery')->get_configuration()->get_documentation(), GalleryAuthorizationsService::check_authorizations()->write()));

		return $tree;
	}
}
?>
