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
	function get_feeds_list()
    {
        global $LANG;
        $feed = array();
        $forum = new Forum();
        $categories = $forum->get_cats_tree();
        $cat_tree = new FeedsCat('forum', 0, $LANG['root']);

        ForumInterface::_feeds_add_category($cat_tree, $categories);

        $children = $cat_tree->get_children();
        $feeds = new FeedsList();
        if (count($children) > 0)
        {
            $feeds->add_feed($children[0], Feed::DEFAULT_FEED_NAME);
        }
        return $feeds;
    }

    function get_feed_data_struct($idcat = 0, $name = '')
    {
    	$querier = PersistenceContext::get_querier();
        global $Cache, $LANG, $CONFIG_FORUM, $CAT_FORUM, $User;

        $_idcat = $idcat;
        require_once(PATH_TO_ROOT . '/forum/forum_init_auth_cats.php');
        $idcat = $_idcat;   // Because <$idcat> is overwritten in /forum/forum_init_auth_cats.php

        $data = new FeedData();
        $data->set_title($LANG['xml_forum_desc']);
        $data->set_date(new Date());
        $data->set_link(new Url('/syndication.php?m=forum&amp;cat=' . $_idcat));
        $data->set_host(HOST);
        $data->set_desc($LANG['xml_forum_desc']);
        $data->set_lang($LANG['xml_lang']);
        $data->set_auth_bit(READ_CAT_FORUM);

        $req_cats = (($idcat > 0) && isset($CAT_FORUM[$idcat])) ? ' AND c.id_left >= :forum_cats_left AND id_right <= :forum_cats_right' : '';
        $parameters = array('limit' => 2 * $CONFIG_FORUM['pagination_msg']);
        if ($idcat > 0)
        {
        	$parameters['forum_cats_left'] = $CAT_FORUM[$idcat]['id_left'];
            $parameters['forum_cats_right'] = $CAT_FORUM[$idcat]['id_right'];
        }
        $req = 'SELECT t.id, t.title, t.last_timestamp, t.last_msg_id, t.display_msg, t.nbr_msg AS t_nbr_msg, msg.id mid, msg.contents, c.auth
        FROM ' . PREFIX . 'forum_topics t
        LEFT JOIN ' . PREFIX . 'forum_cats c ON c.id = t.idcat
        LEFT JOIN ' . PREFIX . 'forum_msg msg ON msg.id = t.last_msg_id
        WHERE c.level != 0 AND c.aprob = 1 ' . $req_cats . '
        ORDER BY t.last_timestamp DESC LIMIT :limit OFFSET 0';
        $results = $querier->select($req, $parameters);

        foreach ($results as $row)
        {
            $item = new FeedItem();

            //Link
            $last_page = ceil($row['t_nbr_msg'] / $CONFIG_FORUM['pagination_msg']);
            $last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
            $last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';

            $link = new Url('/forum/topic' . url(
                    '.php?' . $last_page .  'id=' . $row['id'],
                    '-' . $row['id'] . $last_page_rewrite . '+' . Url::encode_rewrite($row['title'])  . '.php'
                    ) . '#m' .  $row['last_msg_id']
                    );
            $item->set_title(
                (($CONFIG_FORUM['activ_display_msg'] && !empty($row['display_msg'])) ?
                html_entity_decode($CONFIG_FORUM['display_msg'], ENT_NOQUOTES) . ' ' : '') .
                ucfirst($row['title'])
            );
            $item->set_link($link);
            $item->set_guid($link);
            $item->set_desc(FormatingHelper::second_parse($row['contents']));
            $item->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['last_timestamp']));
            $item->set_auth(unserialize($row['auth']));

            $data->add_item($item);
        }
        $results->dispose();

        return $data;
    }
}
?>
