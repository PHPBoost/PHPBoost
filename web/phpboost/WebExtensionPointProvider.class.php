<?php
/*##################################################
 *                               WebExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : August 21, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class WebExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('web');
	}
	
	public function comments()
	{
		return new CommentsTopics(array(new WebCommentsTopic()));
	}
	
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('web_mini.css');
		$module_css_files->adding_running_module_displayed_file('web.css');
		return $module_css_files;
	}
	
	public function feeds()
	{
		return new WebFeedProvider();
	}
	
	public function home_page()
	{
		return new WebHomePageExtensionPoint();
	}
	
	public function menus()
	{
		return new ModuleMenus(array(new WebModuleMiniMenu()));
	}
	
	public function scheduled_jobs()
	{
		return new WebScheduledJobs();
	}
	
	public function search()
	{
		return new WebSearchable();
	}
	
	public function sitemap()
	{
		return new WebSitemapExtensionPoint();
	}
	
	public function tree_links()
	{
		return new WebTreeLinks();
	}
	
	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/web/index.php')));
	}
}
?>
