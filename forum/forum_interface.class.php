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
        $form  = '
        <dl>
            <dt><label for="time">Date</label></dt>
            <dd><label> 
                <select id="time" name="time">
                    <option value="30000" selected="selected">Tout</option>
                    <option value="1">1 jour</option>
                    <option value="7">7 Jours</option>
                    <option value="15">15 Jours</option>
                    <option value="30">1 Mois</option>
                    <option value="180">6 Mois</option>
                    <option value="360">1 An</option>
                </select>
            </label></dd>
        </dl>
        <dl>
            <dt><label for="idcat">Catégorie</label></dt>
            <dd><label>
                <select name="idcat" id="idcat">
                    <option value="-1" selected="selected">Tout</option>
                    <option value="4">---- Support PHPBoost</option>
                    <option value="2">---------- Annonces</option>
                </select>
            </label></dd>
        </dl>
        <dl>
            <dt><label for="where">Options</label></dt>
            <dd>
                <label><input type="radio" name="where" id="where" value="contents" checked="checked" /> Contenu</label>
                <br />
                <label><input type="radio" name="where" value="title"  /> Titre</label>
                <br />
                <label><input type="radio" name="where" value="all"  /> Titre/Contenu</label>
            </dd>
        </dl>
        <dl>
            <dt><label for="colorate_result">Colorer les résultats</label></dt>
            <dd>
                <label><input type="checkbox" name="colorate_result" id="colorate_result" value="1" checked="checked" /></label>
            </dd>
        </dl>';
        
        return $form;
    }
    
    function GetSearchArgs()
    /**
     *  Renvoie la liste des arguments de la méthode <GetSearchRequest>
     */
    {
        return Array('time', 'idcat', 'where', 'colorate_result');
    }
    
    function GetSearchRequest($args)
    /**
     *  Renvoie la requête de recherche dans le forum
     */
    {
        return "";
//         SELECT msg.id as msgid, msg.user_id, msg.idtopic, msg.timestamp, t.title, c.id, c.auth, m.login, s.user_id AS connect, msg.contents, MATCH(t.title) AGAINST('" . $search . "') AS relevance, 0 AS relevance2
//         FROM ".PREFIX."forum_msg msg
//         LEFT JOIN ".PREFIX."sessions s ON s.user_id = msg.user_id AND s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' AND s.user_id != -1
//         LEFT JOIN ".PREFIX."member m ON m.user_id = msg.user_id
//         JOIN ".PREFIX."forum_topics t ON t.id = msg.idtopic
//         JOIN ".PREFIX."forum_cats c1 ON c1.id = t.idcat
//         JOIN ".PREFIX."forum_cats c ON c.level != 0 AND c.aprob = 1
//         WHERE MATCH(t.title) AGAINST('" . $search . "') AND msg.timestamp > '" . (time() - $time) . "'
//         " . (!empty($idcat) ? " AND t.idcat = '" . $idcat . "'" : '') . $auth_cats . "
//         GROUP BY t.id
//         ORDER BY relevance DESC
//         " . $Sql->Sql_limit(0, 24);
    }
}
 
?>