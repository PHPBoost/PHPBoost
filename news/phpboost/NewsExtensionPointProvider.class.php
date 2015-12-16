<?php
/*##################################################
 *                    NewsExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : February 13, 2012
 *   copyright            : (C) 2013 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class NewsExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('news');
	}
	
	public function comments()
	{
		return new CommentsTopics(array(new NewsCommentsTopic()));
	}
	
	public function feeds()
	{
		return new NewsFeedProvider();
	}
	
	public function home_page()
	{
		return new NewsHomePageExtensionPoint();
	}
	
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('news.css');
		return $module_css_files;
	}
	
	public function scheduled_jobs()
	{
		return new NewsScheduledJobs();
	}
	
	public function search()
	{
		return new NewsSearchable();
	}
	
	public function sitemap()
	{
		return new NewsSitemapExtensionPoint();
	}
	
	public function tree_links()
	{
		return new NewsTreeLinks();
	}
	
	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/news/index.php')));
	}
}
?>