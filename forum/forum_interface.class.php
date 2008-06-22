<?php
/*##################################################
 *                              forum_interface.class.php
 *                            -------------------
 *   begin                : Februar 24, 2008
 *   copyright            : (C) 2007 Régis Viarre, Loïc Rouchon
 *   email                : crowkait@phpboost.com, horn@phpboost.com
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

define('FORUM_MAX_SEARCH_RESULTS', 100);

// Classe ForumInterface qui hérite de la classe ModuleInterface
class ForumInterface extends ModuleInterface
{
    ## Public Methods ##
    function ForumInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('forum');
    }
    
    //Récupère le lien vers la listes des messages du membre.
    function get_member_msg_link($memberId)
    {
        return PATH_TO_ROOT . '/forum/membermsg.php?id=' . $memberId[0];
    }
    
    //Récupère le nom associé au lien.
    function get_member_msg_name()
    {
        global $LANG;
        load_module_lang('forum'); //Chargement de la langue du module.
        
        return $LANG['forum'];
    }
    
    //Récupère l'image associé au lien.
    function get_member_msg_img()
    {
        return PATH_TO_ROOT . '/forum/forum_mini.png';
    }
    
    // Recherche
    function get_search_form($args=null)
    /**
     *  Renvoie le formulaire de recherche du forum
     */
    {
        global $Member, $MODULES, $Errorh, $CONFIG, $CONFIG_FORUM, $Cache, $CAT_FORUM, $LANG, $Sql;
        
        require_once(PATH_TO_ROOT . '/kernel/framework/template.class.php');
        $Tpl = new Template('forum/forum_search_form.tpl');
        
        //Autorisation sur le module.
        if( isset($MODULES['forum']) && $MODULES['forum']['activ'] == 1 )
        {
            if( !$Member->Check_auth($MODULES['forum']['auth'], ACCESS_MODULE) ) //Accès non autorisé!
                $Errorh->Error_handler('e_auth', E_USER_REDIRECT);
        }
        
        require_once(PATH_TO_ROOT . '/forum/forum_functions.php');
        require_once(PATH_TO_ROOT . '/forum/forum_defines.php');
        load_module_lang('forum'); //Chargement de la langue du module.
        $Cache->Load_file('forum');
        
        $search = $args['search'];
        $idcat = !empty($args['ForumIdcat']) ? numeric($args['ForumIdcat']) : -1;
        $time = !empty($args['ForumTime']) ? numeric($args['ForumTime']) : 0;
        $where = !empty($args['ForumWhere']) ? strprotect($args['ForumWhere']) : 'title';
        $colorate_result = !empty($args['ForumColorate_result']) ? true : false;
        
        $Tpl->Assign_vars(Array(
            'L_DATE' => $LANG['date'],
            'L_DAY' => $LANG['day'],
            'L_DAYS' => $LANG['day_s'],
            'L_MONTH' => $LANG['month'],
            'L_MONTHS' => $LANG['month'],
            'L_YEAR' => $LANG['year'],
            'IS_SELECTED_30000' => $time == 30000 ? ' selected="selected"' : '',
            'IS_SELECTED_1' => $time == 1 ? ' selected="selected"' : '',
            'IS_SELECTED_7' => $time == 7 ? ' selected="selected"' : '',
            'IS_SELECTED_15' => $time == 15 ? ' selected="selected"' : '',
            'IS_SELECTED_30' => $time == 30 ? ' selected="selected"' : '',
            'IS_SELECTED_180' => $time == 180 ? ' selected="selected"' : '',
            'IS_SELECTED_360' => $time == 360 ? ' selected="selected"' : '',
            'L_OPTIONS' => $LANG['options'],
            'L_TITLE' => $LANG['title'],
            'L_CONTENTS' => $LANG['contents'],
            'IS_TITLE_CHECKED' => $where == 'title' ? ' checked="checked"' : '' ,
            'IS_CONTENTS_CHECKED' => $where == 'contents' ? ' checked="checked"' : '' ,
            'IS_ALL_CHECKED' => $where == 'all' ? ' checked="checked"' : '' ,
            'L_COLORATE_RESULTS' => $LANG['colorate_result'],
            'IS_COLORATION_CHECKED' => $colorate_result ? 'checked="checked"' : '',
            'L_CATEGORY' => $LANG['category'],
            'L_ALL_CATS' => $LANG['all'],
            'IS_ALL_CATS_SELECTED' => ($idcat == '-1') ? ' selected="selected"' : '',
        ));
        if( is_array($CAT_FORUM) )
        {
            foreach($CAT_FORUM as $id => $key)
            {
                if( $Member->Check_auth($CAT_FORUM[$id]['auth'], READ_CAT_FORUM) )
                {
                    $Tpl->Assign_block_vars('cats', array(
                        'MARGIN' => ($key['level'] > 0) ? str_repeat('----------', $key['level']) : '----',
                        'ID' => $id,
                        'L_NAME' => $key['name'],
                        'IS_SELECTED' => ($id == $idcat) ? ' selected="selected"' : ''
                    ));
                }
            }
        }
        return $Tpl->parse(TEMPLATE_STRING_MODE);
    }
    
    function get_search_args()
    /**
     *  Renvoie la liste des arguments de la méthode <get_search_args>
     */
    {
        return Array('ForumTime', 'ForumIdcat', 'ForumWhere', 'ForumColorate_result');
    }
    
    function get_search_request($args)
    /**
     *  Renvoie la requête de recherche dans le forum
     */
    {
        global $CONFIG, $CAT_FORUM, $Member, $Cache, $Sql;
        $Cache->Load_file('forum');
        
        $search = $args['search'];
        $idcat = !empty($args['ForumIdcat']) ? numeric($args['ForumIdcat']) : -1;
        $time = !empty($args['ForumTime']) ? numeric($args['ForumTime']) : 0;
        $where = !empty($args['ForumWhere']) ? strprotect($args['ForumWhere']) : 'title';
        $colorate_result = !empty($args['ForumColorate_result']) ? true : false;
        
        $auth_cats = '';
        if( is_array($CAT_FORUM) )
        {
            foreach($CAT_FORUM as $id => $key)
            {
                if( !$Member->Check_auth($CAT_FORUM[$id]['auth'], READ_CAT_FORUM) )
                    $auth_cats .= $id.',';
            }
        }
        $auth_cats = !empty($auth_cats) ? " AND c.id NOT IN (" . trim($auth_cats, ',') . ")" : '';

        if( $where == 'all' )         // All
            return "SELECT ".
                $args['id_search']." AS `id_search`,
                MIN(msg.id) AS `id_content`,
                t.title AS `title`,
                MAX(( 2 * MATCH(t.title) AGAINST('".$search."') + MATCH(msg.contents) AGAINST('".$search."') ) / 3) AS `relevance`,
                ".$Sql->Sql_concat("'" . PATH_TO_ROOT . "'", "'/forum/topic.php?id='", 't.id', "'#m'", 'msg.id')."  AS `link`
            FROM ".PREFIX."forum_msg msg
            JOIN ".PREFIX."forum_topics t ON t.id = msg.idtopic
            JOIN ".PREFIX."forum_cats c ON c.level != 0 AND c.aprob = 1 AND c.id = t.idcat
            WHERE ( MATCH(t.title) AGAINST('".$search."') OR MATCH(msg.contents) AGAINST('".$search."') )
            ".($idcat != -1 ? " AND c.id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'" : '')." ".$auth_cats."
            GROUP BY t.id
            ORDER BY relevance DESC".$Sql->Sql_limit(0, FORUM_MAX_SEARCH_RESULTS);
        
        if( $where == 'contents' )    // Contents
            return "SELECT ".
                $args['id_search']." AS `id_search`,
                MIN(msg.id) AS `id_content`,
                t.title AS `title`,
                MAX(MATCH(msg.contents) AGAINST('".$search."')) AS `relevance`,
                ".$Sql->Sql_concat("'" . PATH_TO_ROOT . "'", "'/forum/topic.php?id='", 't.id', "'#m'", 'msg.id')."  AS `link`
            FROM ".PREFIX."forum_msg msg
            JOIN ".PREFIX."forum_topics t ON t.id = msg.idtopic
            JOIN ".PREFIX."forum_cats c ON c.level != 0 AND c.aprob = 1 AND c.id = t.idcat
            WHERE MATCH(msg.contents) AGAINST('".$search."')
            ".($idcat != -1 ? " AND c.id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'" : '')." ".$auth_cats."
            GROUP BY t.id
            ORDER BY relevance DESC".$Sql->Sql_limit(0, FORUM_MAX_SEARCH_RESULTS);
        else                                         // Title only
            return "SELECT ".
                $args['id_search']." AS `id_search`,
                msg.id AS `id_content`,
                t.title AS `title`,
                MATCH(t.title) AGAINST('".$search."') AS `relevance`,
                ".$Sql->Sql_concat("'" . PATH_TO_ROOT . "'", "'/forum/topic.php?id='", 't.id', "'#m'", 'msg.id')."  AS `link`
            FROM ".PREFIX."forum_msg msg
            JOIN ".PREFIX."forum_topics t ON t.id = msg.idtopic
            JOIN ".PREFIX."forum_cats c ON c.level != 0 AND c.aprob = 1 AND c.id = t.idcat
            WHERE MATCH(t.title) AGAINST('".$search."')
            ".($idcat != -1 ? " AND c.id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'" : '')." ".$auth_cats."
            GROUP BY t.id
            ORDER BY relevance DESC".$Sql->Sql_limit(0, FORUM_MAX_SEARCH_RESULTS);
    }
    
    function parse_search_results(&$args)
    /**
     *  Return the string to print the results
     */
    {
        global $CONFIG, $LANG, $Sql;
        
        require_once(PATH_TO_ROOT . '/kernel/begin.php');
        load_module_lang('forum'); //Chargement de la langue du module.
        
        $Tpl = new Template('forum/forum_generic_results.tpl');

        $Tpl->Assign_vars(Array(
            'L_ON' => $LANG['on'],
            'L_TOPIC' => $LANG['topic']
        ));
        
        if( $this->GetAttribute('ResultsReqExecuted') === false  || $this->GotError(MODULE_ATTRIBUTE_DOES_NOT_EXIST) )
        {
            $ids = array();
            $results =& $args['results'];
            $newResults = array();
            $nbResults = count($results);
            for( $i = 0; $i < $nbResults; $i++ )
                $newResults[$results[$i]['id_content']] =& $results[$i];
            
            $results =& $newResults;
            
            $request = "
            SELECT
                msg.id AS msg_id,
                msg.user_id AS user_id,
                msg.idtopic AS topic_id,
                msg.timestamp AS date,
                t.title AS title,
                m.login AS login,
                s.user_id AS connect,
                msg.contents AS contents
            FROM ".PREFIX."forum_msg msg
            LEFT JOIN ".PREFIX."sessions s ON s.user_id = msg.user_id AND s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' AND s.user_id != -1
            LEFT JOIN ".PREFIX."member m ON m.user_id = msg.user_id
            JOIN ".PREFIX."forum_topics t ON t.id = msg.idtopic
            WHERE msg.id IN (".implode(',', array_keys($results)).")
            GROUP BY t.id";
            $requestResults = $Sql->Query_while($request, __LINE__, __FILE__);
            while( $row = $Sql->Sql_fetch_assoc($requestResults) )
            {
                $results[$row['msg_id']] = $row;
            }
            $Sql->Close($requestResults);
            
            $this->set_attribute('ResultsReqExecuted', true);
            $this->set_attribute('Results', $results);
            $this->set_attribute('ResultsIndex', 0);
        }
        
        $results =& $this->GetAttribute('Results');
        $indexes = array_keys($results);
        $indexSize = count($indexes);
        $resultsIndex = $this->GetAttribute('ResultsIndex');
        $resultsIndex = $resultsIndex < $indexSize ? $resultsIndex : ($indexSize > 0 ? $indexSize - 1 : 0);
        $result =& $results[$indexes[$resultsIndex]];
        
        $rewrited_title = ($CONFIG['rewrite'] == 1) ? '+' . url_encode_rewrite($result['title']) : '';
        $Tpl->Assign_vars(array(
            'USER_ONLINE' => '<img src="../templates/' . $CONFIG['theme'] . '/images/' . ((!empty($result['connect']) && $result['user_id'] !== -1) ? 'online' : 'offline') . '.png" alt="" class="valign_middle" />',
            'U_USER_PROFILE' => !empty($result['user_id']) ? PATH_TO_ROOT . '/member/member'.transid('.php?id='.$result['user_id'],'-'.$result['user_id'].'.php') : '',
            'USER_PSEUDO' => !empty($result['login']) ? wordwrap_html($result['login'], 13) : $LANG['guest'],
            'U_TOPIC' => PATH_TO_ROOT . '/forum/topic' . transid('.php?id=' . $result['topic_id'], '-' . $result['topic_id'] . $rewrited_title . '.php') . '#m' . $result['msg_id'],
            'TITLE' => ucfirst($result['title']),
            'DATE' => gmdate_format('d/m/y', $result['date']),
            'CONTENTS' => $result['contents']
        ));
        
        $this->set_attribute('ResultsIndex', ++$resultsIndex);
        
        return $Tpl->parse(TEMPLATE_STRING_MODE);
    }
    
    function syndication_data($idcat = 0)
    {
        global $Cache, $Sql, $LANG, $CONFIG, $CONFIG_FORUM, $CAT_FORUM, $Member;
        $_idcat = $idcat;
        require_once(PATH_TO_ROOT . '/forum/forum_init_auth_cats.php');
        require_once(PATH_TO_ROOT . '/kernel/framework/syndication/feed_data.class.php');
        $idcat = $_idcat;   // Because <$idcat> is overwrite in /forum/forum_init_auth_cats.php
        
        $data = new FeedData();
        
        require_once(PATH_TO_ROOT . '/kernel/framework/util/date.class.php');
        $date = new Date();
        
        $data->set_title($LANG['xml_forum_desc']);
        $data->set_date($date->format_date(DATE_FORMAT_TINY, TIMEZONE_USER));
        $data->set_date_rfc822($date->format_date(DATE_RFC822_F));
        $data->set_date_rfc3339($date->format_date(DATE_RFC3339_F));
        $data->set_link(trim(HOST, '/') . '/' . trim($CONFIG['server_path'], '/') . '/' . 'forum/syndication.php?idcat=' . $_idcat);
        $data->set_host(HOST);
        $data->set_desc($LANG['xml_forum_desc']);
        $data->set_lang($LANG['xml_lang']);
        
        $req_cats = (($idcat > 0) && isset($CAT_FORUM[$idcat])) ? " AND c.id_left >= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right <= '" . $CAT_FORUM[$idcat]['id_right'] . "' " : "";
        
        $req = "SELECT t.id, t.title, t.last_timestamp, msg.id mid, msg.contents
            FROM ".PREFIX."forum_topics t
            LEFT JOIN ".PREFIX."forum_cats c ON c.id = t.idcat
            LEFT JOIN ".PREFIX."forum_msg msg ON msg.id = t.last_msg_id
            WHERE (c.auth LIKE '%s:3:\"r-1\";i:1;%' OR c.auth LIKE '%s:3:\"r-1\";i:3;%') AND c.level != 0 AND c.aprob = 1 " . $req_cats . "
            ORDER BY t.last_timestamp DESC
            " . $Sql->Sql_limit(0, $CONFIG_FORUM['pagination_msg']);
        
        $result = $Sql->Query_while($req, __LINE__, __FILE__);
        // Generation of the feed's items
        while ($row = $Sql->Sql_fetch_assoc($result))
        {
            $item = new FeedItem();
            // Rewriting
            if ( $CONFIG['rewrite'] == 1 )
                $rewrited_title = '-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php';
            else
                $rewrited_title = '.php?id=' . $row['id'];
            $link = HOST . DIR . '/forum/topic' . $rewrited_title;
            
            // XML text's protection
            $contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));
            
            $date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['last_timestamp']);
            
            $item->set_title(htmlspecialchars(html_entity_decode($row['title'])));
            $item->set_link($link);
            $item->set_guid($link);
            $item->set_desc(( strlen($contents) > 500 ) ?  substr($contents, 0, 500) . '...[' . $LANG['next'] . ']' : $contents);
            $item->set_date($date->format_date(DATE_FORMAT_TINY, TIMEZONE_USER));
            $item->set_date_rfc822($date->format_date(DATE_RFC822_F, TIMEZONE_SITE));
            $item->set_date_rfc3339($date->format_date(DATE_RFC3339_F, TIMEZONE_SITE));
            
            $data->add_item($item);
        }
        $Sql->Close($result);
        
        return $data;
    }
}

?>
