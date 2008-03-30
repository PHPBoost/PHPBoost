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
        global $Member, $SECURE_MODULE, $Errorh, $CONFIG_FORUM, $Cache, $CAT_FORUM, $LANG, $Sql;
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
        
        $form  = '
        <dl>
            <dt><label for="ForumTime">'.$LANG['date'].'</label></dt>
            <dd><label>
                <select id="time" name="ForumTime" class="search_field">
                    <option value="30000"'.($time == 30000 ? ' selected="selected"' : '' ).'>Tout</option>
                    <option value="1'.($time == 1 ? ' selected="selected"' : '' ).'">1 '.$LANG['day'].'</option>
                    <option value="7"'.($time == 7 ? ' selected="selected"' : '' ).'>7 '.$LANG['day_s'].'</option>
                    <option value="15"'.($time == 15 ? ' selected="selected"' : '' ).'>15 '.$LANG['day_s'].'</option>
                    <option value="30"'.($time == 30 ? ' selected="selected"' : '' ).'>1 '.$LANG['month'].'</option>
                    <option value="180"'.($time == 180 ? ' selected="selected"' : '' ).'>6 '.$LANG['month'].'</option>
                    <option value="360"'.($time == 360 ? ' selected="selected"' : '' ).'>1 '.$LANG['year'].'</option>
                </select>
            </label></dd>
        </dl>
        <dl>
            <dt><label for="ForumIdcat">'.$LANG['category'].'</label></dt>
            <dd><label>
                <select name="ForumIdcat" id="idcat" class="search_field">';
        
        $selected = ($idcat == '-1') ? ' selected="selected"' : '';
        $form .= '<option value="-1"' . $selected . '>' . $LANG['all'] . '</option>';
        if( is_array($CAT_FORUM) )
        {
            foreach($CAT_FORUM as $id => $key)
            {
                if( $Member->Check_auth($CAT_FORUM[$id]['auth'], READ_CAT_FORUM) )
                {
                    $margin = ($key['level'] > 0) ? str_repeat('----------', $key['level']) : '----';
                    $selected = ($id == $idcat) ? ' selected="selected"' : '';
                    $form .= '<option value="'.$id.'"'.$selected.'>'.$margin.' '.$key['name'].'</option>';
                }
            }
        }
        
        $form .= '
                </select>
            </label></dd>
        </dl>
        <dl>
            <dt><label for="ForumWhere">'.$LANG['options'].'</label></dt>
            <dd>
                <label><input type="radio" name="ForumWhere" value="title"'.($where == 'title' ? ' checked="checked"' : '' ).' /> '.$LANG['title'].'</label>
                <br />
                <label><input type="radio" name="ForumWhere" id="where" value="contents"'.($where == 'contents' ? ' checked="checked"' : '' ).' /> '.$LANG['contents'].'</label>
                <br />
                <label><input type="radio" name="ForumWhere" value="all"'.($where == 'all' ? ' checked="checked"' : '' ).' /> '.$LANG['title'].' / '.$LANG['contents'].'</label>
            </dd>
        </dl>
        <dl>
            <dt><label for="ForumColorate_result">'.$LANG['colorate_result'].'</label></dt>
            <dd>
                <label><input type="checkbox" name="ForumColorate_result" id="colorate_result" value="1"'.($colorate_result ? 'checked="checked"' : '').' /></label>
            </dd>
        </dl>';
        
        return $form;
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
        $auth_cats = !empty($auth_cats) ? " AND c1.id NOT IN (" . trim($auth_cats, ',') . ")" : '';
        
        if ( $args['ForumWhere'] == 'all' )         // All
            return "SELECT ".
                $args['id_search']." AS `id_search`,
                msg.id AS `id_content`,
                t.title AS `title`,
                ( 4 * MATCH(t.title) AGAINST('".$search."') + MATCH(msg.contents) AGAINST('".$search."') ) / 5 AS `relevance`,
                CONCAT(CONCAT(CONCAT('../forum/topic.php?id=',t.id),'#'),msg.id)  AS `link`
            FROM ".PREFIX."forum_msg msg
            JOIN ".PREFIX."forum_topics t ON t.id = msg.idtopic
            JOIN ".PREFIX."forum_cats c ON c.level != 0 AND c.aprob = 1 AND c.id = t.idcat
            WHERE ( MATCH(t.title) AGAINST('".$search."') OR MATCH(msg.contents) AGAINST('".$search."') )
            ".($idcat != -1 ? " AND c.id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'" : '')." ".$auth_cats."
            GROUP BY msg.id
            ORDER BY relevance DESC".$Sql->Sql_limit(0, FORUM_MAX_SEARCH_RESULTS);
        
        if ( $args['ForumWhere'] == 'contents' )    // Contents
            return "SELECT ".
                $args['id_search']." AS `id_search`,
                msg.id AS `id_content`,
                t.title AS `title`,
                MATCH(msg.contents) AGAINST('".$search."') AS `relevance`,
                CONCAT(CONCAT(CONCAT('../forum/topic.php?id=',t.id),'#'),msg.id) AS `link`
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
                CONCAT(CONCAT(CONCAT('../forum/topic.php?id=',t.id),'#'),msg.id) AS `link`
            FROM ".PREFIX."forum_msg msg
            JOIN ".PREFIX."forum_topics t ON t.id = msg.idtopic
            JOIN ".PREFIX."forum_cats c ON c.level != 0 AND c.aprob = 1 AND c.id = t.idcat
            WHERE MATCH(t.title) AGAINST('".$search."')
            ".($idcat != -1 ? " AND c.id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'" : '')." ".$auth_cats."
            GROUP BY t.id
            ORDER BY relevance DESC".$Sql->Sql_limit(0, FORUM_MAX_SEARCH_RESULTS);
    }
}

?>