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
    
    //Récupération du cache.
	function get_cache()
	{
		global $Sql;

		$news_config = 'global $CONFIG_NEWS;' . "\n";
		
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_NEWS = unserialize($Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'news'", __LINE__, __FILE__));
		
		$news_config .= '$CONFIG_NEWS = ' . var_export($CONFIG_NEWS, true) . ';' . "\n";

		return $news_config;
	}

	//Actions journalière.
	function on_changeday()
	{
		global $Sql;
		
		//Publication des news en attente pour la date donnée.
		$result = $Sql->Query_while("SELECT id, start, end
		FROM ".PREFIX."news	
		WHERE visible != 0", __LINE__, __FILE__);
		while($row = $Sql->Sql_fetch_assoc($result) )
		{ 
			if( $row['start'] <= time() && $row['start'] != 0 )
				$Sql->Query_inject("UPDATE ".PREFIX."news SET visible = 1, start = 0 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			if( $row['end'] <= time() && $row['end'] != 0 )
				$Sql->Query_inject("UPDATE ".PREFIX."news SET visible = 0, start = 0, end = 0 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
		}
	}		
	
	function get_search_request($args)
    /**
     *  Renvoie la requête de recherche
     */
    {
        global $Sql;
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
        
        $request = "SELECT " . $args['id_search'] . " AS `id_search`,
            n.id AS `id_content`,
            n.title AS `title`,
            ( 2 * MATCH(n.title) AGAINST('" . $args['search'] . "') + (MATCH(n.contents) AGAINST('" . $args['search'] . "') + MATCH(n.extend_contents) AGAINST('" . $args['search'] . "')) / 2 ) / 3 * " . $weight . " AS `relevance`, "
            . $Sql->Sql_concat("'" . PATH_TO_ROOT . "/news/news.php?id='","n.id") . " AS `link`
            FROM " . PREFIX . "news n
            WHERE ( MATCH(n.title) AGAINST('" . $args['search'] . "') OR MATCH(n.contents) AGAINST('" . $args['search'] . "') OR MATCH(n.extend_contents) AGAINST('" . $args['search'] . "') )
                AND visible = 1 AND ('" . time() . "' > start AND ( end = 0 OR '" . time() . "' < end ) )
            ORDER BY `relevance` " . $Sql->Sql_limit(0, NEWS_MAX_SEARCH_RESULTS);
        
        return $request;
    }
    
    function syndication_data($idcat = 0)
    {
        require_once(PATH_TO_ROOT . '/kernel/framework/content/syndication/feed_data.class.php');
        global $Cache, $Sql, $LANG, $CONFIG, $CONFIG_NEWS;
        load_module_lang('news');
        
        $data = new FeedData();
        
        require_once(PATH_TO_ROOT . '/kernel/framework/util/date.class.php');
        $date = new Date();
        
        $data->set_title($LANG['xml_news_desc'] . ' ' . $CONFIG['server_name']);
        $data->set_date($date);
        $data->set_link(trim(HOST, '/') . '/' . trim($CONFIG['server_path'], '/') . '/' . 'news/syndication.php?idcat=' . $idcat);
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
        " . $Sql->Sql_limit(0, 2 * $CONFIG_NEWS['pagination_news']), __LINE__, __FILE__);
        
        // Generation of the feed's items
        while ($row = $Sql->Sql_fetch_assoc($result))
        {
            $item = new FeedItem();
            // Rewriting
            $link = HOST . DIR . '/news/news' . transid('.php?id=' . $row['id'], '-0-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php');
            
            // XML text's protection
            $contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));
            
            $date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']);
            
            $maxlength = 300;
            $length = strlen($contents) > $maxlength ?  $maxlength + strpos(substr($contents, $maxlength), ' ') : 0;
            $length = $length > ($maxlength * 1.1) ? $maxlength : $length;
            
            $item->set_title(htmlspecialchars(html_entity_decode($row['title'])));
            $item->set_link($link);
            $item->set_guid($link);
            $item->set_desc($length > 0 ? substr($contents, 0, $length) . ' <a href="' . $link . '" title="' . $LANG['next'] . '">...</a>' : $contents);
            $item->set_date($date);
            $item->set_image_url($row['img']);
            
            $data->add_item($item);
        }
        $Sql->Close($result);
        
        return $data;
    }
}

?>