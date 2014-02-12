<?php
/*##################################################
 *		                         CalendarTreeLinks.class.php
 *                            -------------------
 *   begin                : November 26, 2013
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
		
		$manage_categories_link = new AdminModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), CalendarUrlBuilder::manage_categories());
		$manage_categories_link->add_sub_link(new AdminModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), CalendarUrlBuilder::manage_categories()));
		$manage_categories_link->add_sub_link(new AdminModuleLink(LangLoader::get_message('category.add', 'categories-common'), CalendarUrlBuilder::add_category()));
		$tree->add_link($manage_categories_link);
		
		$manage_events_link = new AdminModuleLink($lang['calendar.manage'], CalendarUrlBuilder::manage_events());
		$manage_events_link->add_sub_link(new AdminModuleLink($lang['calendar.manage'], CalendarUrlBuilder::manage_events()));
		$manage_events_link->add_sub_link(new AdminModuleLink($lang['calendar.titles.add_event'], CalendarUrlBuilder::add_event($year . '/' . $month . '/' . $day)));
		$tree->add_link($manage_events_link);
		
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), CalendarUrlBuilder::configuration()));
		
		if (!AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$tree->add_link(new ModuleLink($lang['calendar.titles.add_event'], CalendarUrlBuilder::add_event($year . '/' . $month . '/' . $day), CalendarAuthorizationsService::check_authorizations()->write() || CalendarAuthorizationsService::check_authorizations()->contribution()));
		}
		
		$tree->add_link(new ModuleLink($lang['calendar.pending'], CalendarUrlBuilder::display_pending_events(), CalendarAuthorizationsService::check_authorizations()->write() || CalendarAuthorizationsService::check_authorizations()->contribution() || CalendarAuthorizationsService::check_authorizations()->moderation()));
		
		return $tree;
	}
}
?>