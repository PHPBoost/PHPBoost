<?php
/*##################################################
 *                          NewsFeedProvider.class.php
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

class NewsFeedProvider implements FeedProvider
{
	/**
	 * @desc Return the list of the feeds available for this module.
	 * @return FeedsList The list
	 */
	public function get_feeds_list()
	{
		$news_cats = new NewsCats();
		return $news_cats->get_feeds_list();
	}

	public function get_feed_data_struct($idcat = 0, $name = '')
	{
		$querier = PersistenceContext::get_querier();
		global $Cache, $LANG, $CONFIG, $NEWS_CONFIG, $NEWS_CAT, $NEWS_LANG;

		// Load the new's config
		$Cache->load('news');

		$idcat = !empty($NEWS_CAT[$idcat]) ? $idcat : 0;

		$now = new Date(DATE_NOW, TIMEZONE_AUTO);

		load_module_lang('news');

		$site_name = $idcat > 0 ? $CONFIG['site_name'] . ' : ' . $NEWS_CAT[$idcat]['name'] : $CONFIG['site_name'];

		$data = new FeedData();

		$data->set_title($NEWS_LANG['xml_news_desc'] . $site_name);
		$data->set_date(new Date());
		$data->set_link(new Url('/syndication.php?m=news&amp;cat=' . $idcat));
		$data->set_host(HOST);
		$data->set_desc($NEWS_LANG['xml_news_desc'] . $site_name);
		$data->set_lang($LANG['xml_lang']);
		$data->set_auth_bit(AUTH_NEWS_READ);

		$news_cat = new NewsCats();

		// Build array with the children categories.
		$array_cat = array();
		$news_cat->build_children_id_list($idcat, $array_cat, RECURSIVE_EXPLORATION, ($idcat == 0 ? DO_NOT_ADD_THIS_CATEGORY_IN_LIST : ADD_THIS_CATEGORY_IN_LIST));

		if (!empty($array_cat))
		{
			// Last news
			$results = $querier->select('SELECT id, idcat, title, contents, extend_contents, timestamp, img
                 FROM ' . DB_TABLE_NEWS . '
                 WHERE start <= :timestamp AND visible = 1 AND idcat IN :cats_ids
                 ORDER BY timestamp DESC LIMIT :limit OFFSET 0', array(
                     'timestamp' => $now->get_timestamp(),
			         'cats_ids' => $array_cat,
			         'limit' => 2 * $NEWS_CONFIG['pagination_news']));

			foreach ($results as $row)
			{
				// Rewriting
				$link = new Url('/news/news' . url('.php?id=' . $row['id'], '-0-' . $row['id'] .  '+' . Url::encode_rewrite($row['title']) . '.php'));

				$item = new FeedItem();
				$item->set_title($row['title']);
				$item->set_link($link);
				$item->set_guid($link);
				$item->set_desc($row['contents'] . (!empty($row['extend_contents']) ? '<br /><br /><a href="' . $link->absolute() . '">' . $NEWS_LANG['extend_contents'] . '</a><br /><br />' : ''));
				$item->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']));
				$item->set_image_url($row['img']);
				$item->set_auth($news_cat->compute_heritated_auth($row['idcat'], AUTH_NEWS_READ, Authorizations::AUTH_PARENT_PRIORITY));

				$data->add_item($item);
			}
			$results->dispose();
		}

		return $data;
	}
}
?>
