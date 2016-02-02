<?php
/*##################################################
 *                          ForumFeedProvider.class.php
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

class ForumFeedProvider implements FeedProvider
{
	public function get_feeds_list()
	{
		return ForumService::get_categories_manager()->get_feeds_categories_module()->get_feed_list();
	}

	public function get_feed_data_struct($idcat = 0, $name = '')
	{
		if (ForumService::get_categories_manager()->get_categories_cache()->category_exists($idcat))
		{
			$config = ForumConfig::load();
			$category = ForumService::get_categories_manager()->get_categories_cache()->get_category($idcat);

			$data = new FeedData();
			$data->set_title(LangLoader::get_message('xml_forum_desc', 'common', 'forum') . ' ' . $category->get_name());
			$data->set_date(new Date());
			$data->set_link(DispatchManager::get_url('/syndication', '/rss/forum/'. $idcat . '/'));
			$data->set_host(HOST);
			$data->set_desc(LangLoader::get_message('xml_forum_desc', 'common', 'forum'));
			$data->set_lang(LangLoader::get_message('xml_lang', 'main'));
			$data->set_auth_bit(Category::READ_AUTHORIZATIONS);
			
			$categories = ForumService::get_categories_manager()->get_children($idcat, new SearchCategoryChildrensOptions(), true);
			$ids_categories = array_keys($categories);
			
			$results = PersistenceContext::get_querier()->select('SELECT t.id, t.idcat, t.title, t.last_timestamp, t.last_msg_id, t.display_msg, t.nbr_msg AS t_nbr_msg, msg.id mid, msg.contents
				FROM ' . PREFIX . 'forum_topics t
				LEFT JOIN ' . PREFIX . 'forum_msg msg ON msg.id = t.last_msg_id
				WHERE t.idcat IN :ids_categories
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

				$link = new Url('/forum/topic' . url(
						'.php?' . $last_page .  'id=' . $row['id'],
						'-' . $row['id'] . $last_page_rewrite . '+' . Url::encode_rewrite($row['title'])  . '.php'
						) . '#m' .  $row['last_msg_id']
						);
				$item->set_title(
					(($config->is_message_before_topic_title_displayed() && !empty($row['display_msg'])) ?
					TextHelper::html_entity_decode($config->get_message_before_topic_title(), ENT_NOQUOTES) . ' ' : '') .
					stripslashes($row['title'])
				);
				$item->set_link($link);
				$item->set_guid($link);
				$item->set_desc(FormatingHelper::second_parse(stripslashes($row['contents'])));
				$item->set_date(new Date($row['last_timestamp'], Timezone::SERVER_TIMEZONE));
				$item->set_auth(ForumService::get_categories_manager()->get_heritated_authorizations($row['idcat'], Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY));

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
