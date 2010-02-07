<?php
/*##################################################
 *                          MediaFeedProvider.class.php
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

class MediaFeedProvider implements FeedProvider
{
    function get_feeds_list()
    {
        $media_cats = new MediaCats();
        return $media_cats->get_feeds_list();       
    }
    
    function get_feed_data_struct($idcat = 0)
    {
    	$querier = AppContext::get_sql_querier();
        global $Cache, $LANG, $MEDIA_LANG, $CONFIG, $MEDIA_CONFIG, $MEDIA_CATS;
        
        $Cache->load('media');
        load_module_lang('media');

        require_once(PATH_TO_ROOT . '/media/media_constant.php');
        
        $data = new FeedData();
        
        // Meta-informations generation
        $data->set_title($MEDIA_LANG['xml_media_desc']);
        $data->set_date(new Date());
        $data->set_link(new Url('/syndication.php?m=media&amp;cat=' . $idcat));
        $data->set_host(HOST);
        $data->set_desc($MEDIA_LANG['xml_media_desc']);
        $data->set_lang($LANG['xml_lang']);
        $data->set_auth_bit(MEDIA_AUTH_READ);

        // Building Categories to look in
        $cats = new MediaCats();
        $children_cats = array();
        $cats->build_children_id_list($idcat, $children_cats, RECURSIVE_EXPLORATION, ADD_THIS_CATEGORY_IN_LIST);

        $results = $querier->select('SELECT id, idcat, name, contents, timestamp FROM ' .
        PREFIX . 'media WHERE infos = :status_approved AND idcat IN :children ORDER BY timestamp DESC
        LIMIT :limit OFFSET 0', array(
            'status_approved' => MEDIA_STATUS_APROBED,
            'children' => $children_cats,
            'limit' => $MEDIA_CONFIG['pagin']));
        // Generation of the feed's items
        foreach ($results as $row)
        {
            $item = new FeedItem();
            
            // Rewriting
            $link = new Url('/media/media' . url(
                '.php?id=' . $row['id'],
                '-' . $row['id'] . '+' . Url::encode_rewrite($row['name']) . '.php'
            ));
            
            // Adding item's informations
            $item->set_title($row['name']);
            $item->set_link($link);
            $item->set_guid($link);
            $item->set_desc(FormatingHelper::second_parse($row['contents']));
            $item->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']));
            $item->set_image_url($MEDIA_CATS[$row['idcat']]['image']);
            $item->set_auth($cats->compute_heritated_auth($row['idcat'], MEDIA_AUTH_READ, Authorizations::AUTH_PARENT_PRIORITY));
            
            // Adding the item to the list
            $data->add_item($item);
        }
        $results->dispose();
        return $data;
    }
}
?>
