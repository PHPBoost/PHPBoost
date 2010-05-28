<?php
/*##################################################
 *                          ArticlesFeedProvider.class.php
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

class ArticlesFeedProvider implements FeedProvider
{
	function get_feeds_list()
	{
		$articles_cats = new ArticlesCats();
		return $articles_cats->get_feeds_list();
	}

	function get_feed_data_struct($idcat = 0, $name = '')
	{
		$querier = PersistenceContext::get_querier();
		global $Cache, $LANG, $CONFIG, $CONFIG_ARTICLES, $ARTICLES_CAT,$ARTICLES_LANG;

		$Cache->load('articles');

		require_once(PATH_TO_ROOT . '/articles/articles_constants.php');
		$data = new FeedData();

		$data->set_title($ARTICLES_LANG['xml_articles_desc']);
		$data->set_date(new Date());
		$data->set_link(new Url('/syndication.php?m=articles&amp;cat=' . $idcat));
		$data->set_host(HOST);
		$data->set_desc($ARTICLES_LANG['xml_articles_desc']);
		$data->set_lang($LANG['xml_lang']);
		$data->set_auth_bit(AUTH_ARTICLES_READ);

		$cat_clause = !empty($idcat) ? ' AND a.idcat = :idcat'  : '';
		$results = $querier->select('SELECT a.id, a.idcat, a.title, a.contents, a.timestamp, a.icon, ac.auth
        FROM ' . DB_TABLE_ARTICLES . ' a
        LEFT JOIN ' . DB_TABLE_ARTICLES_CAT . ' ac ON ac.id = a.idcat
        WHERE a.visible = 1 AND (ac.visible = 1 OR a.idcat = 0) ' . $cat_clause . '
        ORDER BY a.timestamp DESC LIMIT :limit OFFSET 0', array(
            'idcat' => $idcat,
            'limit' => 2 * $CONFIG_ARTICLES['nbr_articles_max']));

		// Generation of the feed's items
		foreach ($results as $row)
		{
			$item = new FeedItem();

			$link = new Url('/articles/articles' . url(
                '.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'],
                '-' . $row['idcat'] . '-' . $row['id'] .  '+' . Url::encode_rewrite($row['title']) . '.php'
                ));

                $item->set_title($row['title']);
                $item->set_link($link);
                $item->set_guid($link);
                $item->set_desc(preg_replace('`\[page\](.+)\[/page\]`U', '<br /><strong>$1</strong><hr />', FormatingHelper::second_parse($row['contents'])));
                $item->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']));
                $item->set_image_url($row['icon']);
                $item->set_auth($row['idcat'] == 0 ? $CONFIG_ARTICLES['global_auth'] : unserialize($row['auth']));

                $data->add_item($item);
		}
		$results->dispose();

		return $data;
	}
}
?>
