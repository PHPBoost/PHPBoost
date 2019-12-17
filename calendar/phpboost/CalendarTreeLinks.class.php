<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 11
 * @since       PHPBoost 4.0 - 2013 11 26
 * @contributor xela <xela@phpboost.com>
*/

class CalendarTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$request = AppContext::get_request();
		$year = $request->get_getint('year', date('Y'));
		$month = $request->get_getint('month', date('n'));
		$day = $request->get_getint('day', date('j'));

		$module_id = 'calendar';
		
		$lang = LangLoader::get('common', $module_id);
		$tree = new ModuleTreeLinks();

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), CategoriesUrlBuilder::manage_categories($module_id), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), CategoriesUrlBuilder::manage_categories($module_id), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), CategoriesUrlBuilder::add_category(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY), $module_id), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->manage_categories()));
		$tree->add_link($manage_categories_link);

		$manage_events_link = new ModuleLink($lang['calendar.manage'], CalendarUrlBuilder::manage_events(), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->moderation());
		$manage_events_link->add_sub_link(new ModuleLink($lang['calendar.manage'], CalendarUrlBuilder::manage_events(), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->moderation()));
		$manage_events_link->add_sub_link(new ModuleLink($lang['calendar.titles.add_event'], CalendarUrlBuilder::add_event($year, $month, $day), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->moderation()));
		$tree->add_link($manage_events_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), CalendarUrlBuilder::configuration()));

		if (!CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->moderation())
		{
			$tree->add_link(new ModuleLink($lang['calendar.titles.add_event'], CalendarUrlBuilder::add_event($year, $month, $day), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->write() || CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->contribution()));
		}

		$tree->add_link(new ModuleLink($lang['calendar.events_list'], CalendarUrlBuilder::events_list($year, $month, $day), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->read()));

		$tree->add_link(new ModuleLink($lang['calendar.pending'], CalendarUrlBuilder::display_pending_events(), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->write() || CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->contribution() || CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->moderation()));

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('calendar')->get_configuration()->get_documentation(), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->write() || CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->contribution() || CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->moderation()));

		return $tree;
	}
}
?>
