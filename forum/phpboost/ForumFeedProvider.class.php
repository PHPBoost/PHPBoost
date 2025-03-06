<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 10 03
 * @since       PHPBoost 3.0 - 2010 02 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ForumFeedProvider implements FeedProvider
{
	public function get_feeds_list()
	{
		return CategoriesService::get_categories_manager('forum')->get_feeds_categories_module()->get_feed_list();
	}

	public function get_feed_data_struct($id_category = 0, $name = '')
	{
		$module_id = 'forum';
		if (CategoriesService::get_categories_manager($module_id)->get_categories_cache()->category_exists($id_category))
		{
			$config = ForumConfig::load();
			$category = CategoriesService::get_categories_manager($module_id)->get_categories_cache()->get_category($id_category);

			$data = new FeedData();
			$data->set_title(LangLoader::get_message('forum.last.forum.topics', 'common', $module_id) . ' ' . $category->get_name());
			$data->set_date(new Date());
			$data->set_link(DispatchManager::get_url('/syndication', '/rss/forum/'. $id_category . '/'));
			$data->set_host(HOST);
			$data->set_desc(LangLoader::get_message('forum.last.forum.topics', 'common', $module_id));
			$data->set_lang(LangLoader::get_message('common.xml.lang', 'common-lang'));
			$data->set_auth_bit(Category::READ_AUTHORIZATIONS);

			$categories = CategoriesService::get_categories_manager($module_id)->get_children($id_category, new SearchCategoryChildrensOptions(), true);
			$ids_categories = array_keys($categories);

			$results = PersistenceContext::get_querier()->select('SELECT t.id, t.id_category, t.title, t.last_timestamp, t.last_msg_id, t.display_msg, t.nbr_msg AS t_nbr_msg, msg.id mid, msg.content
				FROM ' . PREFIX . 'forum_topics t
				LEFT JOIN ' . PREFIX . 'forum_msg msg ON msg.id = t.last_msg_id
				WHERE t.id_category IN :ids_categories
				ORDER BY t.last_timestamp DESC LIMIT :limit OFFSET 0', array(
					'ids_categories' => $ids_categories,
					'limit' => 2 * $config->get_number_messages_per_page()
			));

			foreach ($results as $row)
			{
				$item = new FeedItem();

				//Link
				$last_page = ceil($row['t_nbr_msg'] / $config->get_number_messages_per_page());
				$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
				$last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';

				$link = new Url('/forum/topic' . url('.php?' . $last_page .  'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . '-' . Url::encode_rewrite($row['title'])  . '.php') . '#m' .  $row['last_msg_id']);
				$item->set_title(
					(($config->is_message_before_topic_title_displayed() && !empty($row['display_msg'])) ?
					TextHelper::html_entity_decode($config->get_message_before_topic_title(), ENT_NOQUOTES) . ' ' : '') .
					stripslashes($row['title'])
				);
				$item->set_link($link);
				$item->set_guid($link);
				$item->set_desc(FormatingHelper::second_parse(stripslashes($row['content'])));
				$item->set_date(new Date($row['last_timestamp'], Timezone::SERVER_TIMEZONE));
				$item->set_auth(CategoriesService::get_categories_manager($module_id)->get_heritated_authorizations($row['id_category'], Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY));

				$data->add_item($item);
			}
			$results->dispose();

			return $data;
		}
	}

	private function feeds_add_category($cat_tree, $category)
	{
		$child = new FeedsCat('forum', $category['this']['id'], $category['this']['name']);
		foreach ($category['children'] as $sub_category)
		{
			$this->feeds_add_category($child, $sub_category);
		}
		$cat_tree->add_child($child);
	}
}
?>
