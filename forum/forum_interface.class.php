<?php
/*##################################################
 *                              forum_interface.class.php
 *                            -------------------
 *   begin                : Februar 24, 2008
 *   copyright          : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
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
                <select id="time" name="ForumTime">
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
                <select name="ForumIdcat" id="idcat">';
        
        forum_list_cat();
        $auth_cats = '';
        if( is_array($CAT_FORUM) )
        {
            foreach($CAT_FORUM as $id => $key)
            {
                if( !$Member->Check_auth($CAT_FORUM[$id]['auth'], READ_CAT_FORUM) )
                    $auth_cats .= $id . ',';
            }
        }
        $auth_cats_select = !empty($auth_cats) ? " AND id NOT IN (" . trim($auth_cats, ',') . ")" : '';
        
        $selected = ($idcat == '-1') ? ' selected="selected"' : '';
        $form .= '<option value="-1"' . $selected . '>' . $LANG['all'] . '</option>';
        $result = $Sql->Query_while("SELECT id, name, level
        FROM ".PREFIX."forum_cats 
        WHERE aprob = 1 " . $auth_cats_select . "
        ORDER BY id_left", __LINE__, __FILE__);
        while( $row = $Sql->Sql_fetch_assoc($result) )
        {
            $margin = ($row['level'] > 0) ? str_repeat('----------', $row['level']) : '----';
            $selected = ($row['id'] == $idcat) ? ' selected="selected"' : '';
            $form .= '<option value="' . $row['id'] . '"' . $selected . '>' . $margin . ' ' . $row['name'] . '</option>';
        }
        $Sql->Close($result);
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
        return "SELECT ".
			$args['id_search']." AS id_search,
			`id` AS `id_content`,
			`title` AS `title`,
			MATCH(`title`) AGAINST('".$args['search']."') AS `relevance`,
			CONCAT('../wiki/wiki.php?title=',encoded_title) AS link
		FROM ".
			PREFIX."wiki_articles
		WHERE
			MATCH(`title`) AGAINST('".$args['search']."')
		";
    }
}
 
?>