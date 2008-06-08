<?php
/***************************************************************************
 *                              syndication.php
 *                            -------------------
 *   begin                : June 08, 2008
 *   copyright            : (C) 2008 Loïc Rouchon
 *   email                : horn@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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
 ###################################################
 * Regenerate the feed in the RSS 2.0 or ATOM 1.0 format
 ###################################################*/


require_once(PATH_TO_ROOT . '/news/news_begin.php');
require_once(PATH_TO_ROOT . '/kernel/framework/syndication/feeds.class.php');

function RegenerateSyndication($feedType = ALL_FEEDS)
/**
 *  Regenerate the feed after an update of the news
 */
{
    global $Sql, $Cache, $LANG, $CONFIG, $CONFIG_NEWS;
    
    if ( !(($feedType == ALL_FEEDS) || ($feedType == USE_RSS) || ($feedType == USE_ATOM)) )
        $feedType = ALL_FEEDS;
    
    // Generation of the feed's headers
    $feedInformations = array(
        'title' => $LANG['xml_news_desc'] . ' ' . $CONFIG['server_name'],
        'date' => gmdate_format('Y-m-d') . 'T' . gmdate_format('h:m:s') . 'Z',
        'link' => trim(HOST, '/') . '/' . trim($CONFIG['server_path'], '/') . '/' . 'news/syndication.php',
        'host' => HOST,
        'desc' => $LANG['xml_news_desc'] . ' ' . $CONFIG['server_name'],
        'lang' => $LANG['xml_lang'],
        'items' => array()
    );

    // Load the new's config
    $Cache->Load_file('news');
    // Last news
    $result = $Sql->Query_while("SELECT id, title, contents, timestamp, img
        FROM ".PREFIX."news
        WHERE visible = 1
        ORDER BY timestamp DESC
    " . $Sql->Sql_limit(0, $CONFIG_NEWS['pagination_news']), __LINE__, __FILE__);

    // Generation of the feed's items
    while ($row = $Sql->Sql_fetch_assoc($result))
    {
        // Rewriting
        if ( $CONFIG['rewrite'] == 1 )
            $rewrited_title = '-0-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php';
        else
            $rewrited_title = '.php?id=' . $row['id'];
        $link = HOST . DIR . '/news/news' . $rewrited_title;
//         echo $row['img'];
        // XML text's protection
        $contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));
        array_push($feedInformations['items'], array(
            'title' => htmlspecialchars(html_entity_decode($row['title'])),
            'link' => $link,
            'guid' => $link,
            'desc' => ( strlen($contents) > 500 ) ?  substr($contents, 0, 500) . '...[' . $LANG['next'] . ']' : $contents,
            'date' => gmdate_format('Y-m-d',$row['timestamp']) . 'T' . gmdate_format('h:m:s',$row['timestamp']) . 'Z',
            'img' => $row['img']
        ));
    }
    $Sql->Close($result);
    
    $Feed = null;
    $link = $feedInformations['link'];
    switch ( $feedType )
    {
        case ALL_FEEDS:
            $Feed = new Feeds('news', USE_ATOM);
            $feedInformations['link'] = $link . '?feed=atom';
            // Don't recreate the HTML cache, only the atom's one
            $Feed->Generate($feedInformations, DYNAMIC_MODE);
            
            $Feed = new Feeds('news', USE_RSS);
            $feedInformations['link'] = $link . '?feed=rss';
            // Recreate the HTML cache and the rss's one
            $Feed->Generate($feedInformations);
            break;
        case USE_ATOM:
            $Feed = new Feeds('news', USE_ATOM);
            $feedInformations['link'] = $link . '?feed=atom';
            // Recreate the HTML cache and the rss's one
            $Feed->Generate($feedInformations, DYNAMIC_MODE);
            break;
        case USE_RSS:
            $Feed = new Feeds('news', USE_RSS);
            $feedInformations['link'] = $link . '?feed=rss';
            // Recreate the HTML cache and the rss's one
            $Feed->Generate($feedInformations, DYNAMIC_MODE);
            break;
    }
    
    return $Feed;
}

?>