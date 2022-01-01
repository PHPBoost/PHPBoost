<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 11
 * @since       PHPBoost 4.0 - 2013 12 04
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class GalleryTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$module_id = 'gallery';

		$lang = LangLoader::get_all_langs($module_id);
		$tree = new ModuleTreeLinks();

		$tree->add_link(new ModuleLink($lang['category.categories.management'], CategoriesUrlBuilder::manage($module_id), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->manage()));
		$tree->add_link(new ModuleLink($lang['category.add'], CategoriesUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY), $module_id), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->manage()));

		$tree->add_link(new AdminModuleLink($lang['gallery.management'], GalleryUrlBuilder::manage()));
		$tree->add_link(new AdminModuleLink($lang['gallery.add.items'], GalleryUrlBuilder::admin_add(AppContext::get_request()->get_getstring('id_category', 0))));

		$tree->add_link(new AdminModuleLink($lang['form.configuration'], GalleryUrlBuilder::configuration()));

		if (!AppContext::get_current_user()->check_level(User::ADMINISTRATOR_LEVEL))
		{
			$tree->add_link(new ModuleLink($lang['gallery.add.items'], GalleryUrlBuilder::add(AppContext::get_request()->get_getstring('id_category', 0)), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->write()));
		}

		if (ModulesManager::get_module($module_id)->get_configuration()->get_documentation())
			$tree->add_link(new ModuleLink($lang['form.documentation'], ModulesManager::get_module('gallery')->get_configuration()->get_documentation(), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->write()));

		return $tree;
	}
}
?>
