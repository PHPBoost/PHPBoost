<?php
/*##################################################
 *                                action.php
 *                            -------------------
 *   begin                : August 14, 2005
 *   copyright          : (C) 2005 Viarre Régis
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
$Speed_bar->Add_link($CONFIG_FORUM['forum_name'], 'index.php' . SID);
require_once('../includes/header_no_display.php');

//Variable GET.
$idt_get = !empty($_GET['id']) ? numeric($_GET['id']) : '';
$idm_get = !empty($_GET['idm']) ? numeric($_GET['idm']) : '';
$del = !empty($_GET['del']) ? true : false;
$track = !empty($_GET['t']) ? numeric($_GET['t']) : '';	
$untrack = !empty($_GET['ut']) ? numeric($_GET['ut']) : '';	
$alert = !empty($_GET['a']) ? numeric($_GET['a']) : '';	
$read = !empty($_GET['read']) ? true : false;;
$msg_d = !empty($_GET['msg_d']) ? true : false;
$lock_get = !empty($_GET['lock']) ? securit($_GET['lock']) : '';
//Variable $_POST
$poll = !empty($_POST['valid_forum_poll']) ? true : false; //Sondage forum.
$massive_action_type = !empty($_POST['massive_action_type']) ? trim($_POST['action_type']) : ''; //Opération de masse.

//Instanciation de la class du forum.
include_once('../forum/forum.class.php');
$Forumfct = new Forum;

if( !empty($idm_get) && $del ) //Suppression d'un message/topic.
{
	//Info sur le message.	
	$msg = $Sql->Query_array('forum_msg', 'user_id', 'idtopic', "WHERE id = '" . $idm_get . "'", __LINE__, __FILE__);
	
	//On va chercher les infos sur le topic	
	$topic = $Sql->Query_array('forum_topics', 'user_id', 'idcat', 'first_msg_id', 'last_msg_id', 'last_timestamp', "WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);

	//Si on veut supprimer le premier message, alors son rippe le topic entier (admin et modo seulement).
	if( !empty($msg['idtopic']) && $topic['first_msg_id'] == $idm_get )
	{
		if( !empty($msg['idtopic']) && ($Member->Check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM) || $Member->Get_attribute('user_id') == $topic['user_id']) ) //Autorisé à supprimer?
			$Forumfct->Del_topic($msg['idtopic']); //Suppresion du topic.
		else
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
		
		redirect(HOST . DIR . '/forum/forum' . transid('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '.php', '&'));
	}
	elseif( !empty($msg['idtopic']) && $topic['first_msg_id'] != $idm_get ) //Suppression d'un message.
	{	
		if( !empty($topic['idcat']) && ($Member->Check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM) || $Member->Get_attribute('user_id') == $msg['user_id']) ) //Autorisé à supprimer?
			list($nbr_msg, $previous_msg_id) = $Forumfct->Del_msg($idm_get, $msg['idtopic'], $topic['idcat'], $topic['first_msg_id'], $topic['last_msg_id'], $topic['last_timestamp'], $msg['user_id']);
		else
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
		
		if( $nbr_msg === false && $previous_msg_id === false ) //Echec de la suppression.
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
		
		//On compte le nombre de messages du topic avant l'id supprimé.
		$last_page = ceil( $nbr_msg/ $CONFIG_FORUM['pagination_msg'] );
		$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
		$last_page = ($last_page > 1) ? '&pt=' . $last_page : '';
			
		redirect(HOST . DIR . '/forum/topic' . transid('.php?id=' . $msg['idtopic'] . $last_page, '-' . $msg['idtopic'] . $last_page_rewrite . '.php', '&') . '#m' . $previous_msg_id);
	}
	else //Non autorisé, on redirige.
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
}
elseif( !empty($idt_get) )
{		
	//On va chercher les infos sur le topic	
	$topic = $Sql->Query_array('forum_topics', 'user_id', 'idcat', 'title', 'subtitle', 'nbr_msg', 'last_msg_id', 'first_msg_id', 'last_timestamp', 'status', "WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);

	if( !$Member->Check_auth($CAT_FORUM[$topic['idcat']]['auth'], READ_CAT_FORUM) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
	//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
	$rewrited_cat_title = ($CONFIG['rewrite'] == 1) ? '+' . url_encode_rewrite($CAT_FORUM[$topic['idcat']]['name']) : '';
	//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
	$rewrited_title = ($CONFIG['rewrite'] == 1) ? '+' . url_encode_rewrite($topic['title']) : '';
	
	//Changement du statut (display_msg) du sujet.
	if( $msg_d )
	{
		//Vérification de l'appartenance du sujet au membres, ou modo.
		$check_mbr = $Sql->Query("SELECT user_id FROM ".PREFIX."forum_topics WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);
		if( (!empty($check_mbr) && $Member->Get_attribute('user_id') == $check_mbr) || $Member->Check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM) )
		{
			$Sql->Query_inject("UPDATE ".PREFIX."forum_topics SET display_msg = 1 - display_msg WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);
			
			redirect(HOST . DIR . '/forum/topic' . transid('.php?id=' . $idt_get, '-' . $idt_get . $rewrited_title . '.php', '&'));
		}	
		else
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
	}	
	elseif( $poll && $Member->Get_attribute('user_id') !== -1 ) //Enregistrement vote du sondage
	{
		$info_poll = $Sql->Query_array('forum_poll', 'voter_id', 'votes', 'type', "WHERE idtopic = '" . $idt_get . "'", __LINE__, __FILE__);
		//Si l'utilisateur n'est pas dans le champ on prend en compte le vote.
		if( !in_array($Member->Get_attribute('user_id'), explode('|', $info_poll['voter_id'])) )
		{		
			//On concatène avec les votans existants.
			$add_voter_id = "voter_id = CONCAT(voter_id, '|" . $Member->Get_attribute('user_id') . "'),"; 
			$array_votes = explode('|', $info_poll['votes']);
				
			if( $info_poll['type'] == 0 ) //Réponse simple.
			{
				$id_answer = isset($_POST['radio']) ? numeric($_POST['radio']) : '-1'; 
				if( isset($array_votes[$id_answer]) )
					$array_votes[$id_answer]++;
			}
			else //Réponses multiples.
			{
				//On boucle pour vérifier toutes les réponses du sondage.
				$nbr_answer = count($array_votes);
				for($i = 0; $i < $nbr_answer; $i++)
				{
					if( isset($_POST[$i]) ) 
						$array_votes[$i]++;
				}
			}

			$Sql->Query_inject("UPDATE ".PREFIX."forum_poll SET " . $add_voter_id . " votes = '" . implode('|', $array_votes) . "' WHERE idtopic = '" . $idt_get . "'", __LINE__, __FILE__);
		}
		
		redirect(HOST . DIR . '/forum/topic' . transid('.php?id=' . $idt_get, '-' . $idt_get . $rewrited_title . '.php', '&'));
	}
	elseif( !empty($lock_get) )
	{
		//Si l'utilisateur a le droit de déplacer le topic, ou le verrouiller.
		if( $Member->Check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM) )
		{
			if( $lock_get === 'true' ) //Verrouillage du topic.
			{
				//Instanciation de la class du forum.
				include_once('../forum/forum.class.php');
				$Forumfct = new Forum;
			
				$Forumfct->Lock_topic($idt_get);
			
				redirect(HOST . DIR . '/forum/topic' . transid('.php?id=' . $idt_get, '-' . $idt_get  . $rewrited_title . '.php', '&'));
			}
			elseif( $lock_get === 'false' )  //Déverrouillage du topic.
			{
				//Instanciation de la class du forum.
				include_once('../forum/forum.class.php');
				$Forumfct = new Forum;
				
				$Forumfct->Unlock_topic($idt_get);
			
				redirect(HOST . DIR . '/forum/topic' . transid('.php?id=' . $idt_get, '-' . $idt_get  . $rewrited_title . '.php', '&'));
			}
		}
		else
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
	}
	else
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
}
elseif( !empty($track) && $Member->Check_level(MEMBER_LEVEL) ) //Ajout du sujet aux sujets suivis.
{
	$Forumfct->Track_topic($track); //Ajout du sujet aux sujets suivis.
	
	redirect(HOST . DIR . '/forum/topic' . transid('.php?id=' . $track, '-' . $track . '.php', '&') . '#go_bottom');
}
elseif( !empty($untrack) && $Member->Check_level(MEMBER_LEVEL) ) //Retrait du sujet, aux sujets suivis.
{
	$Forumfct->Untrack_topic($untrack); //Retrait du sujet aux sujets suivis.
	
	redirect(HOST . DIR . '/forum/topic' . transid('.php?id=' . $untrack, '-' . $untrack . '.php', '&') . '#go_bottom');
}
elseif( $read ) //Marquer comme lu.
{
	if( !$Member->Check_level(MEMBER_LEVEL) ) //Réservé aux membres.
	{
		header('location: ' . HOST . DIR . '/member/error.php'); 
		exit;
	}
			
	//Calcul du temps de péremption, ou de dernière vue des messages.
	$check_last_view_forum = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."member_extend WHERE user_id = '" . $Member->Get_attribute('user_id') . "'", __LINE__, __FILE__);

	//Modification du last_view_forum, si le membre est déjà dans la table
	if( !empty($check_last_view_forum) )
		$Sql->Query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."member_extend SET last_view_forum = '" .  time(). "' WHERE user_id = '" . $Member->Get_attribute('user_id') . "'", __LINE__, __FILE__); 	
	else
		$Sql->Query_inject("INSERT INTO ".PREFIX."member_extend (user_id,last_view_forum) VALUES ('" . $Member->Get_attribute('user_id') . "', '" .  time(). "')", __LINE__, __FILE__); 	

	redirect(HOST . DIR . '/forum/index.php' . SID2);
}
else
	redirect(HOST . DIR . '/forum/index.php' . SID2);

require_once('../includes/footer_no_display.php');

?>