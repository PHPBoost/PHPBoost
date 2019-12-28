<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Alain091 <alain091@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 29
 * @since       PHPBoost 3.0 - 2011 08 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class GalleryFeedProvider implements FeedProvider
{
	function get_feeds_list()
	{
		return CategoriesService::get_categories_manager('gallery')->get_feeds_categories_module()->get_feed_list();
	}

	function get_feed_data_struct($id_category = 0, $name = '')
	{
		if (CategoriesService::get_categories_manager('gallery')->get_categories_cache()->category_exists($id_category))
		{
			$category = CategoriesService::get_categories_manager('gallery')->get_categories_cache()->get_category($id_category);
			$config = GalleryConfig::load();

			$site_name = GeneralConfig::load()->get_site_name();
			$site_name = $id_category != Category::ROOT_CATEGORY ? $site_name . ' : ' . $category->get_name() : $site_name;

			$feed_module_name = LangLoader::get_message('module_title', 'common', 'gallery');
			$data = new FeedData();
			$data->set_title($feed_module_name . ' - ' . $site_name);
			$data->set_date(new Date());
			$data->set_link(SyndicationUrlBuilder::rss('gallery', $id_category));
			$data->set_host(HOST);
			$data->set_desc($feed_module_name . ' - ' . $site_name);
			$data->set_lang(LangLoader::get_message('xml_lang', 'main'));
			$data->set_auth_bit(Category::READ_AUTHORIZATIONS);

			$categories = CategoriesService::get_categories_manager('gallery')->get_children($id_category, new SearchCategoryChildrensOptions(), true);
			$ids_categories = array_keys($categories);

			$results = PersistenceContext::get_querier()->select('SELECT *
				FROM ' . GallerySetup::$gallery_table . '
				WHERE id_category IN :ids_categories
				ORDER BY timestamp DESC
				LIMIT :pics_number_per_page', array(
					'ids_categories' => $ids_categories,
					'pics_number_per_page' => $config->get_pics_number_per_page()
			));

			foreach ($results as $row)
			{
				$link = TextHelper::htmlspecialchars(GalleryUrlBuilder::get_link_item($row['id_category'], $row['id']));

				$item = new FeedItem();
				$item->set_title($row['name']);
				$item->set_link($link);
				$item->set_guid($link);
				$item->set_date(new Date($row['timestamp'], Timezone::SERVER_TIMEZONE));
				$item->set_image_url(Url::to_rel('/gallery/pics/' . $row['path']));
				$item->set_auth(CategoriesService::get_categories_manager('gallery')->get_heritated_authorizations($row['id_category'], Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY));

				$data->add_item($item);
			}
			$results->dispose();

			return $data;
		}
	}
}
?>
