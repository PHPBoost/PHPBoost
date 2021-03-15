<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 4.0 - 2013 12 04
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GalleryTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$module_id = 'gallery';

		$lang = LangLoader::get('common', $module_id);
		$tree = new ModuleTreeLinks();

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), CategoriesUrlBuilder::manage($module_id), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->manage());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), CategoriesUrlBuilder::manage($module_id), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->manage()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), CategoriesUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY), $module_id), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->manage()));
		$tree->add_link($manage_categories_link);

		$manage_gallery_link = new AdminModuleLink($lang['gallery.manage'], GalleryUrlBuilder::manage());
		$manage_gallery_link->add_sub_link(new AdminModuleLink($lang['gallery.manage'], GalleryUrlBuilder::manage()));
		$manage_gallery_link->add_sub_link(new AdminModuleLink($lang['gallery.actions.add'], GalleryUrlBuilder::admin_add(AppContext::get_request()->get_getstring('id_category', 0))));
		$tree->add_link($manage_gallery_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), GalleryUrlBuilder::configuration()));

		if (!AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$tree->add_link(new ModuleLink($lang['gallery.actions.add'], GalleryUrlBuilder::add(AppContext::get_request()->get_getstring('id_category', 0)), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->write()));
		}

		if (ModulesManager::get_module($module_id)->get_configuration()->get_documentation())
			$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('gallery')->get_configuration()->get_documentation(), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->write()));

		return $tree;
	}
}
?>
