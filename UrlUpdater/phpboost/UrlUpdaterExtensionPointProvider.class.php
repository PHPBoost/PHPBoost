<?php
/*##################################################
 *                    UrlUpdaterExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : July 15, 2014
 *   copyright            : (C) 2014 Kevin MASSY
 *   email                : reidlos@phpboost.com
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

class UrlUpdaterExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('UrlUpdater');
	}

	public function url_mappings()
	{
		$urls_mappings = array();
		
		//Articles
		if (class_exists('ArticlesService'))
		{
			$urls_mappings[] = new UrlMapping('^articles/articles.php$', '/articles/', 'L,R=301');
	
			$categories = ArticlesService::get_categories_manager()->get_categories_cache()->get_categories();
			foreach ($categories as $id => $category)
			{
				$urls_mappings[] = new UrlMapping('^articles/articles-'.$id.'\+([^.]*).php$', '/articles/'.$id.'-'.$category->get_rewrited_name().'/', 'L,R=301');
				$urls_mappings[] = new UrlMapping('^articles/articles-'.$id.'-([0-9]*)\+([^.]*).php$', '/articles/'.$id.'-'.$category->get_rewrited_name().'/$1-$2/', 'L,R=301');
			}
		}
		//News
		$urls_mappings[] = new UrlMapping('^news/news.php$', '/news/', 'L,R=301');

		$categories = NewsService::get_categories_manager()->get_categories_cache()->get_categories();
		foreach ($categories as $id => $category)
		{
			$urls_mappings[] = new UrlMapping('^news/news-'.$id.'\+([^.]*).php$', '/news/'.$id.'-'.$category->get_rewrited_name().'/', 'L,R=301');
			$urls_mappings[] = new UrlMapping('^news/news-'.$id.'-([0-9]*)\+([^.]*).php$', '/news/'.$id.'-'.$category->get_rewrited_name().'/$1-$2/', 'L,R=301');
		}
		
		//Calendar
		$urls_mappings[] = new UrlMapping('^calendar/calendar$', '/calendar/', 'L,R=301');
		$urls_mappings[] = new UrlMapping('^calendar/calendar-([0-9]+)-([0-9]+)-([0-9]+)-?([0-9]*).php$', '/calendar/$3-$2-$1/', 'L,R=301');
		
		//Guestbook
		$urls_mappings[] = new UrlMapping('^guestbook/guestbook.php$', '/guestbook/', 'L,R=301');
		
		return new UrlMappings($urls_mappings);
	}
}
?>
