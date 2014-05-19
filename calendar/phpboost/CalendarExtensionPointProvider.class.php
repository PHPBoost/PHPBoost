<?php
/*##################################################
 *                          CalendarExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : July 7, 2008
 *   copyright            : (C) 2008 Régis Viarre
 *   email                : crowkait@phpboost.com
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

class CalendarExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('calendar');
	}
	
	public function comments()
	{
		return new CommentsTopics(array(new CalendarCommentsTopic()));
	}
	
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('calendar.css');
		return $module_css_files;
	}
	
	public function feeds()
	{
		return new CalendarFeedProvider();
	}
	
	public function home_page()
	{
		return new CalendarHomePageExtensionPoint();
	}
	
	public function menus()
	{
		return new ModuleMenus(array(new CalendarModuleMiniMenu()));
	}
	
	public function scheduled_jobs()
	{
		return new CalendarScheduledJobs();
	}
	
	public function search()
	{
		return new CalendarSearchable();
	}
	
	public function sitemap()
	{
		return new CalendarSitemapExtensionPoint();
	}
	
	public function tree_links()
	{
		return new CalendarTreeLinks();
	}
	
	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/calendar/index.php')));
	}
}
?>
