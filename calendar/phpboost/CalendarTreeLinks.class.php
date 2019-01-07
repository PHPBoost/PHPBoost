<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 02 24
 * @since   	PHPBoost 4.0 - 2013 11 26
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

		$lang = LangLoader::get('common', 'calendar');
		$tree = new ModuleTreeLinks();

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), CalendarUrlBuilder::manage_categories(), CalendarAuthorizationsService::check_authorizations()->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), CalendarUrlBuilder::manage_categories(), CalendarAuthorizationsService::check_authorizations()->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), CalendarUrlBuilder::add_category(), CalendarAuthorizationsService::check_authorizations()->manage_categories()));
		$tree->add_link($manage_categories_link);

		$manage_events_link = new ModuleLink($lang['calendar.manage'], CalendarUrlBuilder::manage_events(), CalendarAuthorizationsService::check_authorizations()->moderation());
		$manage_events_link->add_sub_link(new ModuleLink($lang['calendar.manage'], CalendarUrlBuilder::manage_events(), CalendarAuthorizationsService::check_authorizations()->moderation()));
		$manage_events_link->add_sub_link(new ModuleLink($lang['calendar.titles.add_event'], CalendarUrlBuilder::add_event($year, $month, $day), CalendarAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link($manage_events_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), CalendarUrlBuilder::configuration()));

		if (!CalendarAuthorizationsService::check_authorizations()->moderation())
		{
			$tree->add_link(new ModuleLink($lang['calendar.titles.add_event'], CalendarUrlBuilder::add_event($year, $month, $day), CalendarAuthorizationsService::check_authorizations()->write() || CalendarAuthorizationsService::check_authorizations()->contribution()));
		}

		$tree->add_link(new ModuleLink($lang['calendar.events_list'], CalendarUrlBuilder::events_list($year, $month, $day), CalendarAuthorizationsService::check_authorizations()->read()));

		$tree->add_link(new ModuleLink($lang['calendar.pending'], CalendarUrlBuilder::display_pending_events(), CalendarAuthorizationsService::check_authorizations()->write() || CalendarAuthorizationsService::check_authorizations()->contribution() || CalendarAuthorizationsService::check_authorizations()->moderation()));

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('calendar')->get_configuration()->get_documentation(), CalendarAuthorizationsService::check_authorizations()->write() || CalendarAuthorizationsService::check_authorizations()->contribution() || CalendarAuthorizationsService::check_authorizations()->moderation()));

		return $tree;
	}
}
?>
