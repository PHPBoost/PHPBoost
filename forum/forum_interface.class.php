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
require_once('../includes/module_interface.class.php');

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
	function GetMembermsgLink($memberId)
    {
        return '../forum/membermsg.php?id=' . $memberId[0];
    }
	
	//Récupère le nom associé au lien.
	function GetMembermsgName()
    {
        global $LANG;
		load_module_lang('forum'); //Chargement de la langue du module.
		
		return $LANG['forum'];
    }
	
	//Récupère l'image associé au lien.
	function GetMembermsgImg()
    {
		return '../forum/forum_mini.png';
    }
    
    // Recherche
    function GetSearchForm($args=null)
    /**
     *  Renvoie le formulaire de recherche du forum
     */
    {
        global $Member, $SECURE_MODULE, $Errorh, $CONFIG, $CONFIG_FORUM, $Cache, $CAT_FORUM, $LANG, $Sql, $Template;
        require_once('../includes/begin.php');
        require_once('../forum/forum_functions.php');
        require_once('../forum/forum_defines.php');
        load_module_lang('forum'); //Chargement de la langue du module.
        $Cache->Load_file('forum');
        
        $search = $args['search'];
        $idcat = !empty($args['ForumIdcat']) ? numeric($args['ForumIdcat']) : -1;
        $time = !empty($args['ForumTime']) ? numeric($args['ForumTime']) : 0;
        $where = !empty($args['ForumWhere']) ? securit($args['ForumWhere']) : 'title';
        $colorate_result = !empty($args['ForumColorate_result']) ? true : false;

        $Template->Set_filenames(array(
            'forum_search_form' => '../templates/'.$CONFIG['theme'].'/forum/forum_search_form.tpl'
        ));

        $Template->Assign_vars(Array(
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
                    $Template->Assign_block_vars('cats', array(
                        'MARGIN' => ($key['level'] > 0) ? str_repeat('----------', $key['level']) : '----',
                        'ID' => $id,
                        'L_NAME' => $key['name'],
                        'IS_SELECTED' => ($id == $idcat) ? ' selected="selected"' : ''
                    ));
                }
            }
        }
        
        return $Template->Pparse('forum_search_form', TEMPLATE_STRING_MODE);
    }
    
    function GetSearchArgs()
    /**
     *  Renvoie la liste des arguments de la méthode <GetSearchRequest>
     */
    {
        return Array('ForumTime', 'ForumIdcat', 'ForumWhere', 'ForumColorate_result');
    }
    
    function GetSearchRequest($args)
    /**
     *  Renvoie la requête de recherche dans le forum
     */
    {
        global $CONFIG, $CAT_FORUM, $Member, $Cache, $Sql;
        $Cache->Load_file('forum');
        
        $search = $args['search'];
        $idcat = !empty($args['ForumIdcat']) ? numeric($args['ForumIdcat']) : -1;
        $time = !empty($args['ForumTime']) ? numeric($args['ForumTime']) : 0;
        $where = !empty($args['ForumWhere']) ? securit($args['ForumWhere']) : 'title';
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

        if ( $where == 'all' )         // All
            return "SELECT ".
                $args['id_search']." AS `id_search`,
                MIN(msg.id) AS `id_content`,
                t.title AS `title`,
                MAX(( 2 * MATCH(t.title) AGAINST('".$search."') + MATCH(msg.contents) AGAINST('".$search."') ) / 3) AS `relevance`,
                ".$Sql->Sql_concat("'../forum/topic.php?id='", 't.id', "'#m'", 'msg.id')."  AS `link`
            FROM ".PREFIX."forum_msg msg
            JOIN ".PREFIX."forum_topics t ON t.id = msg.idtopic
            JOIN ".PREFIX."forum_cats c ON c.level != 0 AND c.aprob = 1 AND c.id = t.idcat
            WHERE ( MATCH(t.title) AGAINST('".$search."') OR MATCH(msg.contents) AGAINST('".$search."') )
            ".($idcat != -1 ? " AND c.id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'" : '')." ".$auth_cats."
            GROUP BY t.id
            ORDER BY relevance DESC".$Sql->Sql_limit(0, FORUM_MAX_SEARCH_RESULTS);
        
        if ( $where == 'contents' )    // Contents
            return "SELECT ".
                $args['id_search']." AS `id_search`,
                MIN(msg.id) AS `id_content`,
                t.title AS `title`,
                MAX(MATCH(msg.contents) AGAINST('".$search."')) AS `relevance`,
                ".$Sql->Sql_concat("'../forum/topic.php?id='", 't.id', "'#m'", 'msg.id')."  AS `link`
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
                ".$Sql->Sql_concat("'../forum/topic.php?id='", 't.id', "'#m'", 'msg.id')."  AS `link`
            FROM ".PREFIX."forum_msg msg
            JOIN ".PREFIX."forum_topics t ON t.id = msg.idtopic
            JOIN ".PREFIX."forum_cats c ON c.level != 0 AND c.aprob = 1 AND c.id = t.idcat
            WHERE MATCH(t.title) AGAINST('".$search."')
            ".($idcat != -1 ? " AND c.id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'" : '')." ".$auth_cats."
            GROUP BY t.id
            ORDER BY relevance DESC".$Sql->Sql_limit(0, FORUM_MAX_SEARCH_RESULTS);
    }

    function ParseSearchResults(&$args)
    /**
     *  Return the string to print the results
     */
    {
        global $CONFIG, $LANG, $Sql, $Template;
        require_once('../includes/begin.php');
        load_module_lang('forum'); //Chargement de la langue du module.
        
        $Template->Set_filenames(array(
            'forum_generic_results' => '../templates/'.$CONFIG['theme'].'/forum/forum_generic_results.tpl'
        ));

        $Template->Assign_vars(Array(
            'L_ON' => $LANG['on'],
            'L_TOPIC' => $LANG['topic']
        ));

        $ids = array();
        $results =& $args['results'];
        $newResults = array();
        $nbResults = count($results);
        for ( $i = 0; $i < $nbResults; $i++ )
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
            $results[$row['msg_id']] = $row;
        $Sql->Close($requestResults);
        
        foreach ( $results as $result )
        {
            $rewrited_title = ($CONFIG['rewrite'] == 1) ? '+' . url_encode_rewrite($row['title']) : '';
            $Template->Assign_block_vars('forum_results', array(
                'USER_ONLINE' => '<img src="../templates/' . $CONFIG['theme'] . '/images/' . ((!empty($result['connect']) && $result['user_id'] !== -1) ? 'online' : 'offline') . '.png" alt="" class="valign_middle" />',
                'U_USER_PROFILE' => !empty($result['user_id']) ? '../member/member'.transid('.php?id='.$result['user_id'],'-'.$result['user_id'].'.php') : '',
                'USER_PSEUDO' => !empty($result['login']) ? wordwrap_html($result['login'], 13) : $LANG['guest'],
                'U_TOPIC' => '../forum/topic' . transid('.php?id=' . $result['topic_id'], '-' . $result['topic_id'] . $rewrited_title . '.php') . '#m' . $result['msg_id'],
                'TITLE' => ucfirst($result['title']),
                'DATE' => gmdate_format('d/m/y', $result['date']),
                'CONTENTS' => $result['contents']
            ));
        }
        
        return $Template->Pparse('forum_generic_results', TEMPLATE_STRING_MODE);

    }
}

?>