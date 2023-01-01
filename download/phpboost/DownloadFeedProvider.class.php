<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 23
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadFeedProvider implements FeedProvider
{
	public function get_feeds_list()
	{
		return CategoriesService::get_categories_manager('download')->get_feeds_categories_module()->get_feed_list();
	}

	public function get_feed_data_struct($idcat = 0, $name = '')
	{
		$module_id = 'download';
		if (CategoriesService::get_categories_manager($module_id)->get_categories_cache()->category_exists($idcat))
		{
			$querier = PersistenceContext::get_querier();
			$category = CategoriesService::get_categories_manager($module_id)->get_categories_cache()->get_category($idcat);

			$site_name = GeneralConfig::load()->get_site_name();
			$site_name = $idcat != Category::ROOT_CATEGORY ? $site_name . ' : ' . $category->get_name() : $site_name;

			$feed_module_name = LangLoader::get_message('download.module.title', 'common', 'download');
			$data = new FeedData();
			$data->set_title($feed_module_name . ' - ' . $site_name);
			$data->set_date(new Date());
			$data->set_link(SyndicationUrlBuilder::rss('download', $idcat));
			$data->set_host(HOST);
			$data->set_desc($feed_module_name . ' - ' . $site_name);
			$data->set_lang(LangLoader::get_message('common.xml.lang', 'common-lang'));
			$data->set_auth_bit(Category::READ_AUTHORIZATIONS);

			$categories = CategoriesService::get_categories_manager($module_id)->get_children($idcat, new SearchCategoryChildrensOptions(), true);
			$ids_categories = array_keys($categories);

			$now = new Date();
			$results = $querier->select('SELECT download.id, download.id_category, download.title, download.rewrited_title, download.content, download.update_date, download.thumbnail, cat.rewrited_name AS rewrited_name_cat
				FROM ' . DownloadSetup::$download_table . ' download
				LEFT JOIN '. DownloadSetup::$download_cats_table .' cat ON cat.id = download.id_category
				WHERE download.id_category IN :ids_categories
				AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
				ORDER BY download.update_date DESC', array(
					'ids_categories' => $ids_categories,
					'timestamp_now' => $now->get_timestamp()
			));

			foreach ($results as $row)
			{
				$row['rewrited_name_cat'] = !empty($row['id_category']) ? $row['rewrited_name_cat'] : 'root';
				$link = DownloadUrlBuilder::display($row['id_category'], $row['rewrited_name_cat'], $row['id'], $row['rewrited_title']);

				$item = new FeedItem();
				$item->set_title($row['title']);
				$item->set_link($link);
				$item->set_guid($link);
				$item->set_desc(FormatingHelper::second_parse($row['content']));
				$item->set_date(new Date($row['update_date'], Timezone::SERVER_TIMEZONE));
				$item->set_image_url(Url::to_rel($row['thumbnail']));
				$item->set_auth(CategoriesService::get_categories_manager($module_id)->get_heritated_authorizations($row['id_category'], Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY));
				$data->add_item($item);
			}
			$results->dispose();

			return $data;
		}
	}
}
?>
