<?php
/*##################################################
 *                              wikiExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : Februar 24, 2008
 *   copyright            : (C) 2008 Loïc ROUCHON
 *   email                : loic.rouchon@phpboost.com
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

class WikiExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('wiki');
	}
	
	public function comments()
	{
		return new CommentsTopics(array(new WikiCommentsTopic()));
	}
	
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('wiki.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new WikiFeedProvider();
	}

	public function home_page()
	{
		return new WikiHomePageExtensionPoint();
	}
	
	public function search()
	{
		return new WikiSearchable();
	}

	public function sitemap()
	{
		return new WikiSitemapExtensionPoint();
	}
	
	public function tree_links()
	{
		return new WikiTreeLinks();
	}
}
?>