<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 07
 * @since       PHPBoost 3.0 - 2010 02 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class MediaFeedProvider implements FeedProvider
{
	function get_feeds_list()
	{
		return CategoriesService::get_categories_manager('media', 'idcat')->get_feeds_categories_module()->get_feed_list();
	}

	function get_feed_data_struct($idcat = 0, $name = '')
	{
		if (CategoriesService::get_categories_manager('media', 'idcat')->get_categories_cache()->category_exists($idcat))
		{
			require_once(PATH_TO_ROOT . '/media/media_constant.php');

			$category = CategoriesService::get_categories_manager('media', 'idcat')->get_categories_cache()->get_category($idcat);

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

			$categories = CategoriesService::get_categories_manager('media', 'idcat')->get_children($idcat, new SearchCategoryChildrensOptions(), true);
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
				$item->set_auth(CategoriesService::get_categories_manager('media', 'idcat')->get_heritated_authorizations($row['idcat'], Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY));

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
