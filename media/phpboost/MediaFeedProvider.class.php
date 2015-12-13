<?php
/*##################################################
 *                          MediaFeedProvider.class.php
 *                            -------------------
 *   begin                : February 07, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class MediaFeedProvider implements FeedProvider
{
	function get_feeds_list()
	{
		return MediaService::get_categories_manager()->get_feeds_categories_module()->get_feed_list();
	}
	
	function get_feed_data_struct($idcat = 0, $name = '')
	{
		if (MediaService::get_categories_manager()->get_categories_cache()->category_exists($idcat))
		{
			require_once(PATH_TO_ROOT . '/media/media_constant.php');
			
			$category = MediaService::get_categories_manager()->get_categories_cache()->get_category($idcat);
			
			$site_name = GeneralConfig::load()->get_site_name();
			$site_name = $idcat != Category::ROOT_CATEGORY ? $site_name . ' : ' . $category->get_name() : $site_name;
			
			$feed_module_name = LangLoader::get_message('module_title', 'common', 'media');
			$data = new FeedData();
			$data->set_title($feed_module_name . ' - ' . $site_name);
			$data->set_date(new Date());
			$data->set_link(SyndicationUrlBuilder::rss('media', $idcat));
			$data->set_host(HOST);
			$data->set_desc($feed_module_name . ' - ' . $site_name);
			$data->set_lang(LangLoader::get_message('xml_lang', 'main'));
			$data->set_auth_bit(Category::READ_AUTHORIZATIONS);
			
			$categories = MediaService::get_categories_manager()->get_children($idcat, new SearchCategoryChildrensOptions(), true);
			$ids_categories = array_keys($categories);
			
			$results = PersistenceContext::get_querier()->select('SELECT media.*, cat.image
				FROM ' . MediaSetup::$media_table . ' media
				LEFT JOIN '. MediaSetup::$media_cats_table .' cat ON cat.id = media.idcat
				WHERE media.idcat IN :ids_categories
				AND infos = :status_approved
				ORDER BY timestamp DESC', array(
					'ids_categories' => $ids_categories,
					'status_approved' => MEDIA_STATUS_APROBED
			));
			
			foreach ($results as $row)
			{
				// Rewriting
				$link = new Url('/media/media' . url(
					'.php?id=' . $row['id'],
					'-' . $row['id'] . '+' . Url::encode_rewrite($row['name']) . '.php'
				));
				
				$item = new FeedItem();
				$item->set_title($row['name']);
				$item->set_link($link);
				$item->set_guid($link);
				$item->set_desc(FormatingHelper::second_parse($row['contents']));
				$item->set_date(new Date($row['timestamp'], Timezone::SERVER_TIMEZONE));
				$item->set_image_url($row['image']);
				$item->set_auth(MediaService::get_categories_manager()->get_heritated_authorizations($row['idcat'], Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY));
				
				$enclosure = new FeedItemEnclosure();
				$enclosure->set_lenght(@filesize($row['url']));
				$enclosure->set_type($row['mime_type']);
				$enclosure->set_url($row['url']);
				$item->set_enclosure($enclosure);
				
				$data->add_item($item);
			}
			$results->dispose();
			
			return $data;
		}
	}
}
?>