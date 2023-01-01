<?php
/**
 * @package     Content
 * @subpackage  Category\bridges
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 03
 * @since       PHPBoost 6.0 - 2020 01 28
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DefaultCategoriesFeedProvider implements FeedProvider
{
	/**
	 * @var string the module identifier
	 */
	protected $module_id;

	const MAXIMUM_ITEMS_NUMBER = 100;

	public function __construct($module_id)
	{
		$this->module_id = $module_id;
	}

	public function get_feeds_list()
	{
		return CategoriesService::get_categories_manager($this->module_id)->get_feeds_categories_module()->get_feed_list();
	}

	public function get_feed_data_struct($id_category = 0, $name = '')
	{
		$categories_cache = CategoriesService::get_categories_manager($this->module_id)->get_categories_cache();
		if ($categories_cache->category_exists($id_category))
		{
			$module = ModulesManager::get_module($this->module_id);
			$category = $categories_cache->get_category($id_category);

			$site_name = GeneralConfig::load()->get_site_name();
			$site_name = $id_category != Category::ROOT_CATEGORY ? $site_name . ' : ' . $category->get_name() : $site_name;

			$items_lang = ItemsService::get_items_lang($this->module_id);
			$data = new FeedData();
			$data->set_title($name ? $name : $items_lang['last.items'] . ' - ' . $site_name);
			$data->set_date(new Date());
			$data->set_link(SyndicationUrlBuilder::rss($this->module_id, $id_category));
			$data->set_host(HOST);
			$data->set_desc($category->get_description());
			$data->set_lang(LangLoader::get_message('common.xml.lang', 'common-lang'));
			$data->set_auth_bit(Category::READ_AUTHORIZATIONS);

			$condition = 'WHERE id_category IN :categories_id
			AND (published = ' . Item::PUBLISHED . ($module->get_configuration()->feature_is_enabled('deferred_publication') ? ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND (publishing_start_date > :timestamp_now OR (publishing_end_date != 0 AND publishing_end_date < :timestamp_now)))' : '') . ')';

			$parameters = array(
				'categories_id' => array_keys(CategoriesService::get_categories_manager($this->module_id)->get_children($id_category, new SearchCategoryChildrensOptions(), true))
			);

			foreach (ItemsService::get_items_manager($this->module_id)->get_items($condition, $parameters, self::MAXIMUM_ITEMS_NUMBER, 0, 'creation_date', 'DESC') as $item)
			{
				$link = ItemsUrlBuilder::display($item->get_id_category(), ($item->get_id_category() != Category::ROOT_CATEGORY ? $categories_cache->get_category($item->get_id_category())->get_rewrited_name() : 'root'), $item->get_id(), $item->get_rewrited_title(), $this->module_id);
				$feed_item = new FeedItem();
				$feed_item->set_title($item->get_title());
				$feed_item->set_link($link);
				$feed_item->set_guid($link);
				$feed_item->set_desc(FormatingHelper::second_parse($item->get_content()));
				$feed_item->set_date($item->get_creation_date());
				$feed_item->set_auth(CategoriesService::get_categories_manager($this->module_id)->get_heritated_authorizations($item->get_id_category(), Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY));

				if ($module->get_configuration()->has_rich_items())
					$feed_item->set_image_url($item->get_thumbnail());

				$data->add_item($feed_item);
			}

			return $data;
		}
	}
}
?>
