<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 26
 * @since       PHPBoost 3.0 - 2010 02 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MediaFeedProvider implements FeedProvider
{
	function get_feeds_list()
	{
		return CategoriesService::get_categories_manager('media')->get_feeds_categories_module()->get_feed_list();
	}

	function get_feed_data_struct($id_category = 0, $title = '')
	{
		if (CategoriesService::get_categories_manager('media')->get_categories_cache()->category_exists($id_category))
		{
			require_once(PATH_TO_ROOT . '/media/media_constant.php');

			$category = CategoriesService::get_categories_manager('media')->get_categories_cache()->get_category($id_category);

			$site_name = GeneralConfig::load()->get_site_name();
			$site_name = $id_category != Category::ROOT_CATEGORY ? $site_name . ' : ' . $category->get_name() : $site_name;

			$feed_module_name = LangLoader::get_message('media.module.title', 'common', 'media');
			$data = new FeedData();
			$data->set_title($feed_module_name . ' - ' . $site_name);
			$data->set_date(new Date());
			$data->set_link(SyndicationUrlBuilder::rss('media', $id_category));
			$data->set_host(HOST);
			$data->set_desc($feed_module_name . ' - ' . $site_name);
			$data->set_lang(LangLoader::get_message('common.xml.lang', 'common-lang'));
			$data->set_auth_bit(Category::READ_AUTHORIZATIONS);

			$categories = CategoriesService::get_categories_manager('media')->get_children($id_category, new SearchCategoryChildrensOptions(), true);
			$ids_categories = array_keys($categories);

			$results = PersistenceContext::get_querier()->select('SELECT media.*, cat.thumbnail
				FROM ' . MediaSetup::$media_table . ' media
				LEFT JOIN '. MediaSetup::$media_cats_table .' cat ON cat.id = media.id_category
				WHERE media.id_category IN :ids_categories
				AND published = :status_approved
				ORDER BY creation_date DESC', array(
					'ids_categories' => $ids_categories,
					'status_approved' => MEDIA_STATUS_APPROVED
			));

			foreach ($results as $row)
			{
				// Rewriting
				$link = new Url('/media/media' . url(
					'.php?id=' . $row['id'],
					'-' . $row['id'] . '+' . Url::encode_rewrite($row['title']) . '.php'
				));

				$item = new FeedItem();
				$item->set_title($row['title']);
				$item->set_link($link);
				$item->set_guid($link);
				$item->set_desc(FormatingHelper::second_parse($row['content']));
				$item->set_date(new Date($row['creation_date'], Timezone::SERVER_TIMEZONE));
				$item->set_image_url($row['thumbnail']);
				$item->set_auth(CategoriesService::get_categories_manager('media')->get_heritated_authorizations($row['id_category'], Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY));

				$enclosure = new FeedItemEnclosure();
				$enclosure->set_lenght(@filesize($row['file_url']));
				$enclosure->set_type($row['mime_type']);
				$enclosure->set_url($row['file_url']);
				$item->set_enclosure($enclosure);

				$data->add_item($item);
			}
			$results->dispose();

			return $data;
		}
	}
}
?>
