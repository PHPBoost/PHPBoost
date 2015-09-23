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
	private $urls_mappings = array();
	
	public function __construct()
	{
		parent::__construct('UrlUpdater');
	}

	public function url_mappings()
	{
		$db_querier = PersistenceContext::get_querier();
		
		//Download
		if (class_exists('DownloadService'))
		{
			$this->urls_mappings[] = new UrlMapping('^download/download\.php$', '/download/', 'L,R=301');
			
			$categories = DownloadService::get_categories_manager()->get_categories_cache()->get_categories();
			
			$result = $db_querier->select_rows(PREFIX . 'download', array('id', 'id_category', 'rewrited_name'));
			while ($row = $result->fetch())
			{
				$category = $categories[$row['id_category']];
				if (!empty($category))
				{
					$this->urls_mappings[] = new UrlMapping('^download/download-' . $row['id'] . '(-?[^.]*)\.php$', '/download/' . $category->get_id() . '-' . $category->get_rewrited_name() . '/' . $row['id'] . '-' . $row['rewrited_name'], 'L,R=301');
					$this->urls_mappings[] = new UrlMapping('^download/file-' . $row['id'] . '(-?[^.]*)\.php$', '/download/' . $category->get_id() . '-' . $category->get_rewrited_name() . '/' . $row['id'] . '-' . $row['rewrited_name'], 'L,R=301');
				}
			}
			$result->dispose();
			
			foreach ($categories as $id => $category)
			{
				$this->urls_mappings[] = new UrlMapping('^download/category-' . $id . '(-?[^.]*)\.php$', '/download/' . $id . '-' . $category->get_rewrited_name() . '/', 'L,R=301');
			}
		}
		
		//FAQ
		if (class_exists('FaqService'))
		{
			$this->urls_mappings[] = new UrlMapping('^faq/faq\.php$', '/faq/', 'L,R=301');
			
			$categories = FaqService::get_categories_manager()->get_categories_cache()->get_categories();
			
			foreach ($categories as $id => $category)
			{
				$this->urls_mappings[] = new UrlMapping('^faq/faq-' . $category->get_id() . '(\+?[^.]*)\.php$', '/faq/' . $id . '-' . $category->get_rewrited_name() . '/', 'L,R=301');
			}
		}
		
		//FAQ
		if (class_exists('ShoutboxService'))
		{
			$this->urls_mappings[] = new UrlMapping('^shoutbox/shoutbox\.php$', '/shoutbox/', 'L,R=301');
		}
		
		//Web
		if (class_exists('WebService'))
		{
			$this->urls_mappings[] = new UrlMapping('^web/web\.php$', '/web/', 'L,R=301');
			
			$categories = WebService::get_categories_manager()->get_categories_cache()->get_categories();
			
			$result = $db_querier->select_rows(PREFIX . 'web', array('id', 'id_category', 'rewrited_name'));
			while ($row = $result->fetch())
			{
				$category = $categories[$row['id_category']];
				if (!empty($category))
				{
					$this->urls_mappings[] = new UrlMapping('^web/web-' . $category->get_id() . '-' . $row['id'] . '([^.]*)\.php$', '/web/' . $category->get_id() . '-' . $category->get_rewrited_name() . '/' . $row['id'] . '-' . $row['rewrited_name'], 'L,R=301');
				}
			}
			$result->dispose();
			
			foreach ($categories as $id => $category)
			{
				$this->urls_mappings[] = new UrlMapping('^web/web-' . $category->get_id() . '(-?[^.]*)\.php$', '/web/' . $id . '-' . $category->get_rewrited_name() . '/', 'L,R=301');
			}
		}
		
		return new UrlMappings($this->urls_mappings);
	}
}
?>
