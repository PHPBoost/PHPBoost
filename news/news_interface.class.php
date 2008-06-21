<?php
/*##################################################
 *                              news_interface.class.php
 *                            -------------------
 *   begin                : April 9, 2008
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
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

// Inclusion du fichier contenant la classe ModuleInterface
require_once(PATH_TO_ROOT . '/kernel/framework/modules/module_interface.class.php');

define('NEWS_MAX_SEARCH_RESULTS', 100);

// Classe ForumInterface qui hérite de la classe ModuleInterface
class NewsInterface extends ModuleInterface
{
    ## Public Methods ##
    function NewsInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('news');
    }
    
    function GetSearchRequest($args)
    /**
     *  Renvoie la requête de recherche
     */
    {
        global $Sql;
        
        $request = "SELECT " . $args['id_search'] . " AS `id_search`,
            n.id AS `id_content`,
            n.title AS `title`,
            ( 2 * MATCH(n.title) AGAINST('" . $args['search'] . "') + (MATCH(n.contents) AGAINST('" . $args['search'] . "') + MATCH(n.extend_contents) AGAINST('" . $args['search'] . "')) / 2 ) / 3 AS `relevance`, "
            . $Sql->Sql_concat(PATH_TO_ROOT . "/news/news.php?id='","n.id") . " AS `link`
            FROM " . PREFIX . "news n
            WHERE ( MATCH(n.title) AGAINST('" . $args['search'] . "') OR MATCH(n.contents) AGAINST('" . $args['search'] . "') OR MATCH(n.extend_contents) AGAINST('" . $args['search'] . "') )
                AND visible = 1 AND ('" . time() . "' > start AND ( end = 0 OR '" . time() . "' < end ) )
            ORDER BY `relevance` " . $Sql->Sql_limit(0, NEWS_MAX_SEARCH_RESULTS);
        
        return $request;
    }
    
    function syndication_data()
    {
        require_once(PATH_TO_ROOT . '/kernel/framework/syndication/feed_data.class.php');
        global $Cache, $Sql, $LANG, $CONFIG, $CONFIG_NEWS;
        load_module_lang('news');
        
        $data = new FeedData();
        
        require_once(PATH_TO_ROOT . '/kernel/framework/util/date.class.php');
        $date = new Date();
        
        $data->set_title($LANG['xml_news_desc'] . ' ' . $CONFIG['server_name']);
        $data->set_date($date->format_date(DATE_FORMAT_TINY, TIMEZONE_USER));
        $data->set_date_rfc822($date->format_date(DATE_RFC822_F));
        $data->set_date_rfc3339($date->format_date(DATE_RFC3339_F));
        $data->set_link(trim(HOST, '/') . '/' . trim($CONFIG['server_path'], '/') . '/' . 'news/syndication.php');
        $data->set_host(HOST);
        $data->set_desc($LANG['xml_news_desc'] . ' ' . $CONFIG['server_name']);
        $data->set_lang($LANG['xml_lang']);
        
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
            $item = new FeedItem();
            // Rewriting
            if ( $CONFIG['rewrite'] == 1 )
                $rewrited_title = '-0-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php';
            else
                $rewrited_title = '.php?id=' . $row['id'];
            $link = HOST . DIR . '/news/news' . $rewrited_title;
            
            // XML text's protection
            $contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));
            
            $date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']);
            
            $item->set_title(htmlspecialchars(html_entity_decode($row['title'])));
            $item->set_link($link);
            $item->set_guid($link);
            $item->set_desc(( strlen($contents) > 500 ) ?  substr($contents, 0, 500) . '...[' . $LANG['next'] . ']' : $contents);
            $item->set_date($date->format_date(DATE_FORMAT_TINY, TIMEZONE_USER));
            $item->set_date_rfc822($date->format_date(DATE_RFC822_F, TIMEZONE_SITE));
            $item->set_date_rfc3339($date->format_date(DATE_RFC3339_F, TIMEZONE_SITE));
            $item->set_image_url($row['img']);
            
            $data->add_item($item);
        }
        $Sql->Close($result);
        
        return $data;
    }
}

?>