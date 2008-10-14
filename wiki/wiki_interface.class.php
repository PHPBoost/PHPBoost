<?php
/*##################################################
 *                              wiki_interface.class.php
 *                            -------------------
 *   begin                : Februar 24, 2008
 *   copyright            : (C) 2008 Loïc ROUCHON
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

// Classe WikiInterface qui hérite de la classe ModuleInterface
class WikiInterface extends ModuleInterface
{
    ## Public Methods ##
    function WikiInterface() //Constructeur de la classe WikiInterface
    {
        parent::ModuleInterface('wiki');
    }
    
	//Récupération du cache.
	function get_cache()
	{
		global $Sql;
		
		//Catégories du wiki
		$config = 'global $_WIKI_CATS;' . "\n";
		$config .= '$_WIKI_CATS = array();' . "\n";
		$result = $Sql->query_while("SELECT c.id, c.id_parent, c.article_id, a.title
			FROM ".PREFIX."wiki_cats c
			LEFT JOIN ".PREFIX."wiki_articles a ON a.id = c.article_id 
			ORDER BY a.title", __LINE__, __FILE__);
		while( $row = $Sql->fetch_assoc($result) )
		{
			$config .= '$_WIKI_CATS[\'' . $row['id'] . '\'] = array(\'id_parent\' => ' . ( !empty($row['id_parent']) ? $row['id_parent'] : '0') . ', \'name\' => ' . var_export($row['title'], true) . ');' . "\n";
		}

		//Configuration du wiki
		$code = 'global $_WIKI_CONFIG;' . "\n" . '$_WIKI_CONFIG = array();' . "\n";
		$CONFIG_WIKI = unserialize($Sql->query("SELECT value FROM ".PREFIX."configs WHERE name = 'wiki'", __LINE__, __FILE__));
		
		$CONFIG_WIKI = is_array($CONFIG_WIKI) ? $CONFIG_WIKI : array();
		$CONFIG_WIKI['auth'] = unserialize($CONFIG_WIKI['auth']);
		
		$code .= '$_WIKI_CONFIG = ' . var_export($CONFIG_WIKI, true) . ';' . "\n";
		
		return $config . "\n\r" . $code;
	}

	//Actions journalière.
	/*
	function on_changeday()
	{
	}
	*/
	
    // Recherche
    function get_search_form($args=null)
    /**
     *  Renvoie le formulaire de recherche du wiki
     */
    {
        require_once(PATH_TO_ROOT . '/kernel/begin.php');
        load_module_lang('wiki');
        global $CONFIG, $LANG;
        
        $Tpl = new Template('wiki/wiki_search_form.tpl');
        
        if ( !isset($args['WikiWhere']) || !in_array($args['WikiWhere'], explode(',','title,contents,all')) )
            $args['WikiWhere'] = 'title';

        $Tpl->assign_vars(Array(
            'L_WHERE' => $LANG['wiki_search_where'],
            'IS_TITLE_SELECTED' => $args['WikiWhere'] == 'title'? ' selected="selected"': '',
            'IS_CONTENTS_SELECTED' => $args['WikiWhere'] == 'contents'? ' selected="selected"': '',
            'IS_ALL_SELECTED' => $args['WikiWhere'] == 'all'? ' selected="selected"': '',
            'L_TITLE' => $LANG['wiki_search_where_title'],
            'L_CONTENTS' => $LANG['wiki_search_where_contents']
        ));
        
        return $Tpl->parse(TEMPLATE_STRING_MODE);
    }
    
    function get_search_args()
    /**
     *  Renvoie la liste des arguments de la méthode <GetSearchRequest>
     */
    {
        return Array('WikiWhere');
    }
    
    function get_search_request($args)
    /**
     *  Renvoie la requête de recherche dans le wiki
     */
    {
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
        if ( !isset($args['WikiWhere']) || !in_array($args['WikiWhere'], explode(',','title,contents,all')) )
            $args['WikiWhere'] = 'title';
        
        if ( $args['WikiWhere'] == 'all' )
            $req = "SELECT ".
                $args['id_search']." AS `id_search`,
                a.id AS `id_content`,
                a.title AS `title`,
                ( 4 * MATCH(a.title) AGAINST('".$args['search']."') + MATCH(c.content) AGAINST('".$args['search']."') ) / 5 * " . $weight . " AS `relevance`,
                CONCAT('" . PATH_TO_ROOT . "/wiki/wiki.php?title=',a.encoded_title) AS `link`
                FROM ".PREFIX."wiki_articles a
                LEFT JOIN ".PREFIX."wiki_contents c ON c.id_contents = a.id
                WHERE ( MATCH(a.title) AGAINST('".$args['search']."') OR MATCH(c.content) AGAINST('".$args['search']."') )";
        if ( $args['WikiWhere'] == 'contents' )
            $req = "SELECT ".
                $args['id_search']." AS `id_search`,
                a.id AS `id_content`,
                a.title AS `title`,
                MATCH(c.content) AGAINST('".$args['search']."') * " . $weight . " AS `relevance`,
                CONCAT('" . PATH_TO_ROOT . "/wiki/wiki.php?title=',a.encoded_title) AS `link`
                FROM ".PREFIX."wiki_articles a
                LEFT JOIN ".PREFIX."wiki_contents c ON c.id_contents = a.id
                WHERE MATCH(c.content) AGAINST('".$args['search']."')";
        else
            $req = "SELECT ".
                $args['id_search']." AS `id_search`,
                `id` AS `id_content`,
                `title` AS `title`,
                ((MATCH(title) AGAINST('".$args['search']."') )* " . $weight . ") AS `relevance`,
                CONCAT('" . PATH_TO_ROOT . "/wiki/wiki.php?title=',encoded_title) AS `link`
                FROM ".PREFIX."wiki_articles
                WHERE MATCH(title) AGAINST('".$args['search']."')";
        
        return $req;
    }
    
    function get_feed_data_struct($idcat = 0)
    {
        require_once(PATH_TO_ROOT . '/kernel/framework/content/syndication/feed_data.class.php');
        global $Cache, $Sql, $LANG, $CONFIG, $_WIKI_CATS, $_WIKI_CONFIG;
        
        load_module_lang('wiki');
        $Cache->load('wiki');
        
        if( ($idcat > 0) && array_key_exists($idcat, $_WIKI_CATS) )//Catégorie
        {
            $desc = sprintf($LANG['wiki_rss_cat'], html_entity_decode($_WIKI_CATS[$idcat]['name']));
            $where = "AND a.id_cat = '" . $idcat . "'";
        }
        else //Sinon derniers messages
        {
            $desc = sprintf($LANG['wiki_rss_last_articles'], (!empty($_WIKI_CONFIG['wiki_name']) ? html_entity_decode($_WIKI_CONFIG['wiki_name']) : $LANG['wiki']));
            $where = "";
        }
            //On convertit les accents en entitées normales, puis on remplace les caractères non supportés en xml.
//         $contents = htmlspecialchars(html_entity_decode(strip_tags($row['content'])));
//         $contents = preg_replace('`[\n\r]{1}[\-]{2,5}[\s]+(.+)[\s]+[\-]{2,5}(<br \/>|[\n\r]){1}`U', "\n" . '$1' . "\n", "\n" . $contents . "\n");
        
        $data = new FeedData();
        
        require_once(PATH_TO_ROOT . '/kernel/framework/util/date.class.php');
        $date = new Date();
        
        $data->set_title(!empty($_WIKI_CONFIG['wiki_name']) ? html_entity_decode($_WIKI_CONFIG['wiki_name']) : $LANG['wiki']);
        $data->set_date($date);
        $data->set_link(trim(HOST, '/') . '/' . trim($CONFIG['server_path'], '/') . '/' . 'wiki/syndication.php?idcat=' . $idcat);
        $data->set_host(HOST);
        $data->set_desc($desc);
        $data->set_lang($LANG['xml_lang']);
        
        // Load the new's config
        $Cache->load('wiki');
        
        // Last news
        $result = $Sql->query_while("SELECT a.title, a.encoded_title, c.content, c.timestamp 
            FROM ".PREFIX."wiki_articles a
            LEFT JOIN ".PREFIX."wiki_contents c ON c.id_contents = a.id_contents
            WHERE a.redirect = 0 " . $where . "
            ORDER BY c.timestamp DESC
            " . $Sql->limit(0, 2 * 10), __LINE__, __FILE__);
        
        // Generation of the feed's items
        while ($row = $Sql->fetch_assoc($result))
        {
            $item = new FeedItem();
            // Rewriting
            if ( $CONFIG['rewrite'] == 1 )
                $rewrited_title = url_encode_rewrite($row['title']);
            else
                $rewrited_title = 'wiki.php?title=' . url_encode_rewrite($row['title']);
            $link = HOST . DIR . '/wiki/' . $rewrited_title;
            
            // XML text's protection
            $contents = htmlspecialchars(html_entity_decode(strip_tags($row['content'])));
            
            $date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']);
            
            $item->set_title(htmlspecialchars(html_entity_decode($row['title'])));
            $item->set_link($link);
            $item->set_guid($link);
            $item->set_desc(( strlen($contents) > 500 ) ?  substr($contents, 0, 500) . '...[' . $LANG['next'] . ']' : $contents);
            $item->set_date($date);
            
            $data->add_item($item);
        }
        $Sql->query_close($result);
        
        return $data;
    }
}

?>
