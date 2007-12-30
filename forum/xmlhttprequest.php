<?php
/*##################################################
 *                                xmlhttprequest.php
 *                            -------------------
 *   begin                : Februar 15, 2007
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

require_once('../includes/begin.php');
require_once('../forum/forum_begin.php');
define('TITLE', 'Ajax forum');
require_once('../includes/header_no_display.php');

if( !empty($_GET['del']) ) //Suppression d'un message.
{
	//Instanciation de la class du forum.
	include_once('../forum/forum.class.php');
	$forumfct = new Forum;

	$idm_get = !empty($_GET['idm']) ? numeric($_GET['idm']) : '';	
	//Info sur le message.	
	$msg = $sql->query_array('forum_msg', 'user_id', 'idtopic', "WHERE id = '" . $idm_get . "'", __LINE__, __FILE__);	
	//On va chercher les infos sur le topic	
	$topic = $sql->query_array('forum_topics', 'id', 'user_id', 'idcat', 'first_msg_id', 'last_msg_id', 'last_timestamp', "WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
	if( !empty($msg['idtopic']) && $topic['first_msg_id'] != $idm_get ) //Suppression d'un message.
	{	
		if( !empty($topic['idcat']) && ($groups->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM) || $session->data['user_id'] == $msg['user_id']) ) //Autorisé à supprimer?
		{
			list($nbr_msg, $previous_msg_id) = $forumfct->del_msg($idm_get, $msg['idtopic'], $topic['idcat'], $topic['first_msg_id'], $topic['last_msg_id'], $topic['last_timestamp'], $msg['user_id']); //Suppression du message.
			if( $nbr_msg === false && $previous_msg_id === false ) //Echec de la suppression.
				echo '-1';
			else
				echo '1';
		}
		else
			echo '-1';
	}
	else
		echo '-1';	
}
elseif( !empty($_GET['warning_moderation_panel'])  || !empty($_GET['punish_moderation_panel']) ) //Recherche d'un membre
{
	$login = !empty($_POST['login']) ? securit(utf8_decode($_POST['login'])) : '';
	$login = str_replace('*', '%', $login);
	if( !empty($login) )
	{
		$i = 0;
		$result = $sql->query_while("SELECT user_id, login FROM ".PREFIX."member WHERE login LIKE '" . $login . "%'", __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) )
		{
			if( !empty($_GET['warning_moderation_panel']) )
				echo '<a href="moderation_forum.php?action=warning&amp;id=' . $row['user_id'] . '">' . $row['login'] . '</a><br />';
			elseif( !empty($_GET['punish_moderation_panel']) )
				echo '<a href="moderation_forum.php?action=punish&amp;id=' . $row['user_id'] . '">' . $row['login'] . '</a><br />';
			
			$i++;
		}
		
		if( $i == 0 ) //Aucun membre trouvé.
			echo $LANG['no_result'];
	}
	else
		echo $LANG['no_result'];
	
	$sql->sql_close(); //Fermeture de mysql
}

?>