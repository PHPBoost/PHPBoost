<?php
/*##################################################
 *   GalleryFeedProvider.class.php
 *   -----------------------------
 *   begin                : August 07, 2011
 *   copyright            : (C) 2011 Alain091
 *   email                : alain091@gmail.com
 *
 *
 *###################################################
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
 *###################################################
 */

class GalleryFeedProvider implements FeedProvider
{
	function get_feeds_list()
	{
		return GalleryService::get_categories_manager()->get_feeds_categories_module()->get_feed_list();
	}

	function get_feed_data_struct($idcat = 0, $name = '')
	{
		if (GalleryService::get_categories_manager()->get_categories_cache()->category_exists($idcat))
		{
			$category = GalleryService::get_categories_manager()->get_categories_cache()->get_category($idcat);
			$config = GalleryConfig::load();
			
			$site_name = GeneralConfig::load()->get_site_name();
			$site_name = $idcat != Category::ROOT_CATEGORY ? $site_name . ' : ' . $category->get_name() : $site_name;
			
			$feed_module_name = LangLoader::get_message('module_title', 'common', 'gallery');
			$data = new FeedData();
			$data->set_title($feed_module_name . ' - ' . $site_name);
			$data->set_date(new Date());
			$data->set_link(SyndicationUrlBuilder::rss('gallery', $idcat));
			$data->set_host(HOST);
			$data->set_desc($feed_module_name . ' - ' . $site_name);
			$data->set_lang(LangLoader::get_message('xml_lang', 'main'));
			$data->set_auth_bit(Category::READ_AUTHORIZATIONS);
			
			$categories = GalleryService::get_categories_manager()->get_children($idcat, new SearchCategoryChildrensOptions(), true);
			$ids_categories = array_keys($categories);
			
			$results = PersistenceContext::get_querier()->select('SELECT *
				FROM ' . GallerySetup::$gallery_table . '
				WHERE idcat IN :ids_categories
				ORDER BY timestamp DESC
				LIMIT :pics_number_per_page', array(
					'ids_categories' => $ids_categories,
					'pics_number_per_page' => $config->get_pics_number_per_page()
			));
			
			foreach ($results as $row)
			{
				$link = TextHelper::htmlentities(GalleryUrlBuilder::get_link_item($row['idcat'], $row['id']));
				
				$item = new FeedItem();
				$item->set_title($row['name']);
				$item->set_link($link);
				$item->set_guid($link);
				$item->set_date(new Date($row['timestamp'], Timezone::SERVER_TIMEZONE));
				$item->set_image_url(Url::to_rel('/gallery/pics/' . $row['path']));
				$item->set_auth(GalleryService::get_categories_manager()->get_heritated_authorizations($row['idcat'], Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY));
				
				$data->add_item($item);
			}
			$results->dispose();
			
			return $data;
		}
	}
}
?>