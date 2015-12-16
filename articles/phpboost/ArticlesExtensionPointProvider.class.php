<?php
/*##################################################
 *                        ArticlesExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : March 19, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
class ArticlesExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('articles');
	}
	
	public function home_page()
	{
		return new ArticlesHomePageExtensionPoint();
	}
	
	public function comments()
	{
		return new CommentsTopics(array(new ArticlesCommentsTopic()));
	}
	
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('articles.css');
		return $module_css_files;
	}
	
	public function feeds()
	{
		return new ArticlesFeedProvider();
	}
	
	public function scheduled_jobs()
	{
		return new ArticlesScheduledJobs();
	}
	
	public function search()
	{
		return new ArticlesSearchable();
	}

	public function sitemap()
	{
		return new ArticlesSitemapExtensionPoint();
	}
	
	public function tree_links()
	{
		return new ArticlesTreeLinks();
	}
	
	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/articles/index.php')));
	}
}
?>