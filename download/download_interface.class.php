<?php
/*##################################################
 *                          download_interface.class.php
 *                            -------------------
 *   begin                : June 22, 2008
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
class DownloadInterface extends ModuleInterface
{
    ## Public Methods ##
    function DownloadInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('download');
    }
    
//     function GetSearchRequest($args)
//     /**
//      *  Renvoie la requÃªte de recherche
//      */
//     {
//         global $Sql;
//         
//         $request = "SELECT " . $args['id_search'] . " AS `id_search`,
//             n.id AS `id_content`,
//             n.title AS `title`,
//             ( 2 * MATCH(n.title) AGAINST('" . $args['search'] . "') + (MATCH(n.contents) AGAINST('" . $args['search'] . "') + MATCH(n.extend_contents) AGAINST('" . $args['search'] . "')) / 2 ) / 3 AS `relevance`, "
//             . $Sql->Sql_concat(PATH_TO_ROOT . "/news/news.php?id='","n.id") . " AS `link`
//             FROM " . PREFIX . "news n
//             WHERE ( MATCH(n.title) AGAINST('" . $args['search'] . "') OR MATCH(n.contents) AGAINST('" . $args['search'] . "') OR MATCH(n.extend_contents) AGAINST('" . $args['search'] . "') )
//                 AND visible = 1 AND ('" . time() . "' > start AND ( end = 0 OR '" . time() . "' < end ) )
//             ORDER BY `relevance` " . $Sql->Sql_limit(0, NEWS_MAX_SEARCH_RESULTS);
//         
//         return $request;
//     }
    
    function syndication_data($idcat = 0)
    {
        require_once(PATH_TO_ROOT . '/kernel/framework/syndication/feed_data.class.php');
        global $Cache, $Sql, $LANG, $DOWNLOAD_LANG, $CONFIG, $CONFIG_DOWNLOAD, $DOWNLOAD_CATS;
        load_module_lang('download');
        $Cache->Load_file('download');
        
        $visible_cats = array();
        include_once(PATH_TO_ROOT . '/download/download_auth.php');
        $this->_check_cats_auth($idcat, $visible_cats);
        
        $data = new FeedData();
        
        require_once(PATH_TO_ROOT . '/kernel/framework/util/date.class.php');
        $date = new Date();
        
        $data->set_title($DOWNLOAD_LANG['xml_download_desc']);
        $data->set_date($date);
        $data->set_link(trim(HOST, '/') . '/' . trim($CONFIG['server_path'], '/') . '/' . 'download/syndication.php');
        $data->set_host(HOST);
        $data->set_desc($DOWNLOAD_LANG['xml_download_desc']);
        $data->set_lang($LANG['xml_lang']);
        
        // Last files
        if( count($visible_cats) )
        {
            $req = "SELECT id, title, contents, timestamp, image
            FROM ".PREFIX."download
            WHERE visible = 1 AND idcat IN (" . implode($visible_cats, ', ') . ")
            ORDER BY timestamp DESC" . $Sql->Sql_limit(0, $CONFIG_DOWNLOAD['nbr_file_max']);
            
            $result = $Sql->Query_while($req, __LINE__, __FILE__);
        
//             echo $req;
            // Generation of the feed's items
            while ($row = $Sql->Sql_fetch_assoc($result))
            {
                $item = new FeedItem();
                // Rewriting
                if ( $CONFIG['rewrite'] == 1 )
                    $rewrited_title = '-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php';
                else
                    $rewrited_title = '.php?id=' . $row['id'];
                $link = HOST . DIR . '/download/download' . $rewrited_title;
                
                // XML text's protection
                $contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));
                
                $date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']);
                
                $item->set_title(htmlspecialchars(html_entity_decode($row['title'])));
                $item->set_link($link);
                $item->set_guid($link);
                $item->set_desc(( strlen($contents) > 500 ) ?  substr($contents, 0, 500) . '...[' . $LANG['next'] . ']' : $contents);
                $item->set_date($date);
                $item->set_image_url($row['image']);
                
                $data->add_item($item);
            }
            $Sql->Close($result);
        }
        return $data;
    }
    
    function syndication_cache($cats = array(), $tpl = false)
    {
        $cats = array(0, 23, 24);
        //$cats = array(23, 24);
        require_once('../kernel/framework/syndication/feed.class.php');
        require_once('../kernel/framework/template.class.php');
        $tpl = new Template('framework/syndication/feed_with_images.tpl');
        $tpl->Assign_vars(array('L_READ' => 'Téléchager'));
        
        foreach( $cats as $cat )
            feeds_update_cache($this->id, $this->syndication_data($cat), $cat, $tpl);
    }
    
    ## Private ##
    function _check_cats_auth($id_cat, &$list)
    {
        global $DOWNLOAD_CATS, $CONFIG_DOWNLOAD;
        //echo $id_cat . '<pre>'; print_r($DOWNLOAD_CATS); echo '</pre><hr />';
        if( $id_cat == 0 )
        {
            if( array_key_exists('r-1', $CONFIG_DOWNLOAD['global_auth']) && $CONFIG_DOWNLOAD['global_auth']['r-1'] & READ_CAT_DOWNLOAD )
                $list[] = 0;
            else
                return;
        }
        else
        {
            if( array_key_exists($id_cat, $DOWNLOAD_CATS) && (($DOWNLOAD_CATS[$id_cat]['auth'] === false) || (isset($DOWNLOAD_CATS[$id_cat]['auth']['r-1']) && ($DOWNLOAD_CATS[$id_cat]['auth']['r-1'] & READ_CAT_DOWNLOAD)) ) )
                $list[] = $id_cat;
            else
                return;
        }
        
        $keys = array_keys($DOWNLOAD_CATS);
        $num_cats = count($DOWNLOAD_CATS);
        
        $properties = array();
        for( $j = 0; $j < $num_cats; $j++)
        {
            $id = $keys[$j];
            $properties = $DOWNLOAD_CATS[$id];
            
            if( $properties['id_parent'] == $id_cat )
            {
                $this_auth = is_array($properties['auth']) ? array_key_exists('r-1', $properties['auth']) && $properties['auth']['r-1'] & READ_CAT_DOWNLOAD : array_key_exists('r-1', $CONFIG_DOWNLOAD['global_auth']) && $CONFIG_DOWNLOAD['global_auth']['r-1'] & READ_CAT_DOWNLOAD;
                
                if( $this_auth )
                {
                    $list[] = $id;
                    $this->_check_cats_auth($id, $list);
                }
            }
        }
    }
}

?>