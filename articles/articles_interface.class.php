<?php
/*##################################################
 *                              articles_interface.class.php
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

define('ARTICLES_MAX_SEARCH_RESULTS', 100);

// Classe ForumInterface qui hérite de la classe ModuleInterface
class ArticlesInterface extends ModuleInterface
{
    ## Public Methods ##
    function ArticlesInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('articles');
    }
    
//     function get_search_request($args)
//     /**
//      *  Renvoie la requête de recherche
//      */
//     {
//         /*global $Sql;
//         require_once('../articles/articles_cats.class.php');
//         $Cats = new ArticlesCats();
//         $auth_cats = array();
//         $Cats->Build_children_id_list(0, $list);
//         
//         $auth_cats = !empty($auth_cats) ? " AND a.idcat IN (" . implode($auth_cats, ',') . ") " : '';
//         
//         $request = "SELECT " . $args['id_search'] . " AS `id_search`,
//             a.id AS `id_content`,
//             a.title AS `title`,
//             ( 2 * MATCH(a.title) AGAINST('" . $args['search'] . "') + MATCH(a.contents) AGAINST('" . $args['search'] . "') ) / 3 AS `relevance`, "
//             . $Sql->Sql_concat("'../articles/articles.php?id='","a.id","'&amp;cat='","a.idcat") . " AS `link`
//             FROM " . PREFIX . "articles a
//             WHERE ( MATCH(a.title) AGAINST('" . $args['search'] . "') OR MATCH(a.contents) AGAINST('" . $args['search'] . "') )" . $auth_cats . "
// 				AND visible = 1 AND ('" . time() . "' > start AND ( end = 0 OR '" . time() . "' < end ) )
//             ORDER BY `relevance` " . $Sql->Sql_limit(0, FAQ_MAX_SEARCH_RESULTS);
//         
//         return $request;*/
//         return array();
//     }
    
    function syndication_data($idcat = 0)
    {
        global $Cache, $Sql, $LANG, $CONFIG, $CONFIG_ARTICLES, $CAT_ARTICLES, $Member;
        require_once(PATH_TO_ROOT . '/articles/articles_constants.php');
        require_once(PATH_TO_ROOT . '/kernel/framework/syndication/feed_data.class.php');
        $data = new FeedData();
        
        if( $Member->Check_auth($CONFIG_ARTICLES['auth_root'], READ_CAT_ARTICLES) )
        {
            require_once(PATH_TO_ROOT . '/kernel/framework/util/date.class.php');
            $date = new Date();
            
            $data->set_title($LANG['xml_articles_desc']);
            $data->set_date($date);
            $data->set_link(trim(HOST, '/') . '/' . trim($CONFIG['server_path'], '/') . '/' . 'articles/syndication.php?idcat=' . $idcat);
            $data->set_host(HOST);
            $data->set_desc($LANG['xml_articles_desc']);
            $data->set_lang($LANG['xml_lang']);
            
            //Catégories non autorisées.
            $unauth_cats_sql = array();
            foreach($CAT_ARTICLES as $idcat => $key)
            {
                if( $CAT_ARTICLES[$idcat]['aprob'] == 1 )
                {
                    if( !$Member->Check_auth($CAT_ARTICLES[$idcat]['auth'], READ_CAT_ARTICLES) )
                    {
                        $clause_level = !empty($g_idcat) ? ($CAT_ARTICLES[$idcat]['level'] == ($CAT_ARTICLES[$g_idcat]['level'] + 1)) : ($CAT_ARTICLES[$idcat]['level'] == 0);
                        if( $clause_level )
                            $unauth_cats_sql[] = $idcat;
                    }
                }
            }
            $clause_unauth_cats = (count($unauth_cats_sql) > 0) ? " AND gc.id NOT IN (" . implode(', ', $unauth_cats_sql) . ")" : '';
            
            $result = $Sql->Query_while("SELECT a.id, a.idcat, a.title, a.contents, a.timestamp, a.icon
            FROM ".PREFIX."articles a
            LEFT JOIN ".PREFIX."articles_cats ac ON ac.id = a.idcat
            WHERE a.visible = 1 AND ((ac.aprob = 1 AND ac.auth LIKE '%s:3:\"r-1\";i:1;%') OR a.idcat = 0)
            ORDER BY a.timestamp DESC
            " . $Sql->Sql_limit(0, $CONFIG_ARTICLES['nbr_articles_max']), __LINE__, __FILE__);
            
            // Generation of the feed's items
            while ($row = $Sql->Sql_fetch_assoc($result))
            {
                $item = new FeedItem();
                // Rewriting
                if ( $CONFIG['rewrite'] == 1 )
                    $rewrited_title = '-' . $row['idcat'] . '-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php';
                else
                    $rewrited_title = '.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'];
                $link = HOST . DIR . '/articles/articles' . $rewrited_title;
                
                // XML text's protection
                $contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));
                
                $date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']);
                
                $item->set_title(htmlspecialchars(html_entity_decode($row['title'])));
                $item->set_link($link);
                $item->set_guid($link);
                $item->set_desc(( strlen($contents) > 500 ) ?  substr($contents, 0, 500) . '...[' . $LANG['next'] . ']' : $contents);
                $item->set_date($date);
                $item->set_image_url($row['icon']);
                
                $data->add_item($item);
            }
            $Sql->Close($result);
        }
        return $data;
    }
    
    function syndication_cache($cats = array(), $tpl = false)
    {
        $cats[] = 0;
        require_once(PATH_TO_ROOT . '/kernel/framework/syndication/feed.class.php');
        require_once(PATH_TO_ROOT . '/kernel/framework/template.class.php');
        $tpl = new Template('articles/framework/syndication/feed.tpl');
        global $LANG;
        load_module_lang('articles');
        $tpl->Assign_vars(array('L_READ' => $LANG['read_feed']));

        foreach( $cats as $cat )
            feeds_update_cache($this->id, $this->syndication_data($cat), $cat, $tpl);
    }
}

?>