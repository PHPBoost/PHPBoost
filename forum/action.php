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

include_once('../includes/begin.php'); 
include_once('../forum/lang/' . $CONFIG['lang'] . '/forum_' . $CONFIG['lang'] . '.php'); //Chargement de la langue du module.
include_once('../forum/forum_auth.php');
define('TITLE', '');
include_once('../includes/header_no_display.php');

//Variable GET.
$id_get = !empty($_GET['id']) ? numeric($_GET['id']) : '';
$del = !empty($_GET['del']) ? $_GET['del'] : '' ;
$track = !empty($_GET['t']) ? numeric($_GET['t']) : '';	
$untrack = !empty($_GET['ut']) ? numeric($_GET['ut']) : '';	
$alert = !empty($_GET['a']) ? numeric($_GET['a']) : '';	
$read = !empty($_GET['read']) ? $_GET['read'] : '';
$msg_d = !empty($_GET['msg_d']) ? true : false;
//Variable $_POST
$poll = !empty($_POST['valid_forum_poll']) ? $_POST['valid_forum_poll'] : ''; //Sondage forum.
	
//On va chercher les infos sur le topic	
$topic = !empty($id_get) ? $sql->query_array('forum_topics', 'user_id', 'idcat', 'title', 'subtitle', 'nbr_msg', 'last_msg_id', 'first_msg_id', 'last_timestamp', 'status', "WHERE id = '" . $id_get . "'", __LINE__, __FILE__) : '';

if( !$groups->check_auth($SECURE_MODULE['forum'], ACCESS_MODULE) )
{
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

if( !empty($id_get) && empty($del) )
{		
	if( !$groups->check_auth($CAT_FORUM[$topic['idcat']]['auth'], READ_CAT_FORUM) )
	{
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
		exit;
	}
	//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
	$rewrited_cat_title = ($CONFIG['rewrite'] == 1) ? '+' . url_encode_rewrite($CAT_FORUM[$topic['idcat']]['name']) : '';
	//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
	$rewrited_title = ($CONFIG['rewrite'] == 1) ? '+' . url_encode_rewrite($topic['title']) : '';
	
	//Changement du statut (display_msg) du sujet.
	if( $msg_d )
	{
		//Vérification de l'appartenance du sujet au membres, ou modo.
		$check_mbr = $sql->query("SELECT id FROM ".PREFIX."forum_topics WHERE user_id = '" . $session->data['user_id'] . "'", __LINE__, __FILE__);
		if( (!empty($check_mbr) && $id_get == $check_mbr) ||  $groups->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM) )
		{
			$sql->query_inject("UPDATE ".PREFIX."forum_topics SET display_msg = 1 - display_msg WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
			header('location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $id_get, '-' . $id_get . $rewrited_title . '.php', '&'));
			exit;
		}	
		else
		{	
			$errorh->error_handler('e_auth', E_USER_REDIRECT); 
			exit;
		}
	}	
	elseif( !empty($poll) && $session->data['user_id'] !== -1 ) //Enregistrement vote du sondage
	{
		$info_poll = $sql->query_array('forum_poll', 'voter_id', 'votes', "WHERE idtopic = '" . $id_get . "'", __LINE__, __FILE__);
		//Si l'utilisateur n'est pas dans le champ on prend en compte le vote.
		if( !in_array($session->data['user_id'], explode('|', $info_poll['voter_id'])) )
		{		
			//On concatène avec les votans existants.
			$add_voter_id = "voter_id = CONCAT(voter_id, '|" . $session->data['user_id'] . "'),"; 
				
			$array_votes = explode('|', $info_poll['votes']);
				
			$id_answer = isset($_POST['radio']) ? numeric($_POST['radio']) : '-1'; //Réponse simple.
			if( $id_answer >= 0 ) 
			{	
				if( isset($array_votes[$id_answer]) )
					$array_votes[$id_answer]++;
			}
			else //Réponses multiples.
			{
				//On boucle pour vérifier toutes les réponses du sondage.
				$nbr_answer = count($array_votes);
				for( $i = 0; $i < $nbr_answer; $i++)
					if( isset($_POST[$i]) ) $array_votes[$i]++;
			}
				
			$sql->query_inject("UPDATE ".PREFIX."forum_poll SET " . $add_voter_id . " votes = '" . implode('|', $array_votes) . "' WHERE idtopic = '" . $id_get . "'", __LINE__, __FILE__);
		}
		
		header('location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $id_get, '-' . $id_get . $rewrited_title . '.php', '&'));
		exit;
	}
	else
	{
		//Si l'utilisateur a le droit de déplacer le topic, ou le verrouiller.
		if( $groups->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM) )
		{
			$lock_get = !empty($_GET['lock']) ? securit($_GET['lock']) : '';
			if( !empty($lock_get) && $lock_get === 'true' ) //Verrouillage du topic.
			{
				$sql->query_inject("UPDATE ".PREFIX."forum_topics SET status = 0 WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
				
				//Insertion de l'action dans l'historique.
				$sql->query_inject("INSERT INTO ".PREFIX."forum_history (action,user_id,timestamp) VALUES(2, '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
			
				header('location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $id_get, '-' .$id_get  . $rewrited_title . '.php', '&'));
				exit;			
			}
			elseif( !empty($lock_get) && $lock_get === 'false' )  //Déverrouillage du topic.
			{
				$sql->query_inject("UPDATE ".PREFIX."forum_topics SET status = 1 WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
				
				//Insertion de l'action dans l'historique.
				$sql->query_inject("INSERT INTO ".PREFIX."forum_history (action,user_id,timestamp) VALUES(3, '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
			
				header('location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $id_get, '-' .$id_get  . $rewrited_title . '.php', '&'));
				exit;
			}

			//Avertissements aux membres. + ou - 10%. A 100% bannissement.
			$get_user_id = !empty($_GET['user_id']) ? numeric($_GET['user_id']) : '';
			if( !empty($_GET['warning_on']) && !empty($get_user_id) )
			{
				$info_mbr = $sql->query_array('member', 'user_id', 'level', "WHERE user_id = '" . $get_user_id . "'", __LINE__, __FILE__);
				//Modérateur ne peux avertir l'admin (logique non?).
				if( !empty($info_mbr['user_id']) && ( ($info_mbr['level'] == 2 && $groups->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM)) || ($info_mbr['level'] < 2 && $groups->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM)) ) )
				{
					$warning = $sql->query("SELECT user_warning FROM ".PREFIX."member WHERE user_id = '" . $get_user_id . "'", __LINE__, __FILE__);
					if( $warning < 90 ) //Ne peux pas mettre des avertissemtns supérieurs à 100.
					{
						$sql->query_inject("UPDATE ".PREFIX."member SET user_warning = user_warning + 10 WHERE user_id = '" . $get_user_id . "'", __LINE__, __FILE__);
						//Envoi d'un MP au membre pour lui signaler, si le membre en question n'est pas lui-même.
						if( $info_mbr['user_id'] != $session->data['user_id'] )
						{
							include_once('../includes/pm.class.php');
							$privatemsg = new Privatemsg();
							$privatemsg->send_pm($info_mbr['user_id'], $LANG['warning_on_title'], sprintf($LANG['warning_on_msg'], 10), '-1', false, true);	
						}
					}
					elseif( $warning == 90 ) //Ban => on supprime sa session et on le banni (pas besoin d'envoyer de mp :p).
					{
						$sql->query_inject("UPDATE ".PREFIX."member SET user_warning = 100 WHERE user_id = '" . $get_user_id . "'", __LINE__, __FILE__);
						$sql->query_inject("DELETE FROM ".PREFIX."sessions WHERE user_id = '" . $get_user_id . "'", __LINE__, __FILE__);
					}				

					//Insertion de l'action dans l'historique.
					$sql->query_inject("INSERT INTO ".PREFIX."forum_history (action,user_id,timestamp) VALUES(6, '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
					
					//Redirection vers le message averti.
					header('location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $id_get . '&idm=' . numeric($_GET['warning_on']), '-' . $id_get . '-0-' . numeric($_GET['warning_on']) . $rewrited_title . '.php', '&') . '#m' . numeric($_GET['warning_on']));
					exit;
				}
				else //Redirection vers le message averti.
				{
					header('location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $id_get . '&idm=' . numeric($_GET['warning_on']), '-' . $id_get . '-0-' . numeric($_GET['warning_on']) . $rewrited_title . '.php', '&') . '#m' . numeric($_GET['warning_on']));
					exit;
				}
			}
			elseif( !empty($_GET['warning_off']) && !empty($get_user_id) )
			{
				$info_mbr = $sql->query_array('member', 'user_id', 'level', "WHERE user_id = '" . $get_user_id . "'", __LINE__, __FILE__);
				//Modérateur ne peux avertir l'admin (logique non?).
				if( !empty($info_mbr['user_id']) && ( ($info_mbr['level'] == 2 && $groups->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM)) || ($info_mbr['level'] < 2 && $groups->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM) ) ) )
				{		
					$warning = $sql->query("SELECT user_warning FROM ".PREFIX."member WHERE user_id = '" . $get_user_id . "'", __LINE__, __FILE__);
					if( $warning >= 10 ) //Ne peux pas mettre des avertissements négatifs.
					{
						$sql->query_inject("UPDATE ".PREFIX."member SET user_warning = user_warning - 10 WHERE user_id = '" . $get_user_id . "'", __LINE__, __FILE__);
							
						//Envoi d'un MP au membre pour lui signaler, si le membre en question n'est pas lui-même.
						if( $info_mbr['user_id'] != $session->data['user_id'] )
						{
							include_once('../includes/pm.class.php');
							$privatemsg = new Privatemsg();
							$privatemsg->send_pm($info_mbr['user_id'], $LANG['warning_off_title'], sprintf($LANG['warning_off_msg'], 10), '-1', false, true);
						}
					}
					
					//Insertion de l'action dans l'historique.
					$sql->query_inject("INSERT INTO ".PREFIX."forum_history (action,user_id,timestamp) VALUES(7, '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
				
					//Redirection vers le message avertit.
					header('location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $id_get . '&idm=' . numeric($_GET['warning_off']), '-' . $id_get . '-0-' . numeric($_GET['warning_off']) . $rewrited_title . '.php', '&') . '#m' . numeric($_GET['warning_off']));
					exit;
				}	
				else //Redirection vers le message avertit.
				{
					header('location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $id_get . '&idm=' . numeric($_GET['warning_off']), '-' . $id_get . '-0-' . numeric($_GET['warning_off']) . $rewrited_title . '.php', '&') . '#m' . numeric($_GET['warning_off']));
					exit;
				}
			}	
			else
			{
				$errorh->error_handler('e_auth', E_USER_REDIRECT); 
				exit;
			}
		}
		else
		{
			$errorh->error_handler('e_auth', E_USER_REDIRECT); 
			exit;
		}		
	}
}
elseif( !empty($id_get) && !empty($del) ) //Suppression d'un message!
{
	$msg = $sql->query_array('forum_msg', 'user_id', 'idtopic', 'contents', "WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
	$msg['user_id'] = (int)$msg['user_id'];
	
	//On cherche l'id du topic dans lequel on supprime pour pouvoir choper l'id de la catégorie
	$idcat_topic = $sql->query("SELECT idcat FROM ".PREFIX."forum_topics WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);

	$nbr_msg = 1;
	$is_modo = $groups->check_auth($CAT_FORUM[$idcat_topic]['auth'], EDIT_CAT_FORUM);
	if( $is_modo || $session->data['user_id'] === $msg['user_id'] )
	{
		####################Récupération des infos pour suppression####################
		//On cherche l'id du premier et du dernier messages du topic afin de voir si on n'est pas en train d'en supprimer l'un ou l'autre
		$id_first = $sql->query("SELECT first_msg_id FROM ".PREFIX."forum_topics WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
		$id_last = $sql->query("SELECT last_msg_id FROM ".PREFIX."forum_topics WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
		
		####################Execution suivant l'id du msg####################
		//Si on veut supprimer le premier message, alors son rippe le topic entier (admin et modo seulement).
		if( $id_first == $id_get && $is_modo )
		{
			//On ne supprime pas de msg aux membres ayant postés dans le topic => trop de requêtes.
			$redirect = true; //Redirection vers le forum.
			//On compte le nombre de messages du topic.
			$nbr_msg = $sql->query("SELECT COUNT(*) as compt FROM ".PREFIX."forum_msg WHERE idtopic = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);	
			$nbr_msg = !empty($nbr_msg) ? numeric($nbr_msg)  : 0;
			
			//On rippe le topic ainsi que les messages du topic.
			$sql->query_inject("DELETE FROM ".PREFIX."forum_msg WHERE idtopic = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
			$sql->query_inject("DELETE FROM ".PREFIX."forum_topics WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
			//Suppression du sondage éventuellement associé.
			$sql->query_inject("DELETE FROM ".PREFIX."forum_poll WHERE idtopic = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
			//Récupération du timestamp du dernier message de la catégorie.		
			$last_topic_id = $sql->query("SELECT id FROM ".PREFIX."forum_topics WHERE idcat = '" . $idcat_topic . "' ORDER BY last_timestamp DESC " . $sql->sql_limit(0, 1), __LINE__, __FILE__);
			//On retranche le nombre de messages et de topic et le last_topic_id et on update.
			$sql->query_inject("UPDATE ".PREFIX."forum_cats SET nbr_topic = nbr_topic - 1, nbr_msg = nbr_msg - '" . $nbr_msg . "', last_topic_id = '" . $last_topic_id . "' WHERE id_left <= '" . $CAT_FORUM[$idcat_topic]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat_topic]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat_topic]['level'] . "'", __LINE__, __FILE__);			
			
			//Topic supprimé, on supprime les marqueurs de messages lus pour ce topic.
			$sql->query_inject("DELETE FROM ".PREFIX."forum_view WHERE idtopic = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
			
			//Insertion de l'action dans l'historique.
			if( $msg['user_id'] != $session->data['user_id'] ) $sql->query_inject("INSERT INTO ".PREFIX."forum_history (action,user_id,timestamp) VALUES(1, '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
		}
		elseif( $id_first != $id_get ) //Suppression d'un message.
		{
			//On compte le nombre de messages du topic avant l'id supprimé.
			$nbr_msg = $sql->query("SELECT COUNT(*) as compt FROM ".PREFIX."forum_msg WHERE idtopic = '" . $msg['idtopic'] . "' AND id < '" . $id_get . "'", __LINE__, __FILE__);	
			//On supprime le message demandé.
			$sql->query_inject("DELETE FROM ".PREFIX."forum_msg WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
			//On retire un msg au membre.
			$sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."member SET user_msg = user_msg - 1 WHERE user_id = '" . $msg['user_id'] . "'", __LINE__, __FILE__);
			//On met à jour la table forum_topics.
			$sql->query_inject("UPDATE ".PREFIX."forum_topics SET nbr_msg = nbr_msg - 1 WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
			//On retranche d'un messages la catégorie concernée.
			$sql->query_inject("UPDATE ".PREFIX."forum_cats SET nbr_msg = nbr_msg - 1 WHERE id_left <= '" . $CAT_FORUM[$idcat_topic]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat_topic]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat_topic]['level'] . "'", __LINE__, __FILE__);
			//Récupération du message suivant celui supprimé afin de rediriger vers la bonne ancre.
			$previous_msg_id = $sql->query("SELECT id FROM ".PREFIX."forum_msg WHERE idtopic = '" . $msg['idtopic'] . "' AND id < '" . $id_get . "' ORDER BY timestamp DESC " . $sql->sql_limit(0, 1), __LINE__, __FILE__);
			//Mise à jour du dernier message lu par les membres.
			$sql->query_inject("UPDATE ".PREFIX."forum_view SET last_view_id = '" . $previous_msg_id . "' WHERE last_view_id = '" . $id_get . "'", __LINE__, __FILE__);
			
			//Insertion de l'action dans l'historique.
			if( $msg['user_id'] != $session->data['user_id'] ) $sql->query_inject("INSERT INTO ".PREFIX."forum_history (action,user_id,timestamp) VALUES(0, '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
		}
		else //non autorisé, on redirige.
		{
			header('location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $msg['idtopic'], '-' . $msg['idtopic'] . '.php', '&'));
			exit;
		}
			
		if( $id_last == $id_get ) //On met à jour le dernier message posté dans la liste des topics.
		{
			//On cherche les infos à propos de l'avant dernier message afin de mettre la table forum_topics à jour.
			$id_before_last = $sql->query_array('forum_msg', 'user_id', 'timestamp', "WHERE id = '" . $previous_msg_id . "'", __LINE__, __FILE__);	
			$sql->query_inject("UPDATE ".PREFIX."forum_topics SET last_user_id = '" . $id_before_last['user_id'] . "', last_msg_id = '" . $previous_msg_id . "', last_timestamp = '" . $id_before_last['timestamp'] . "' WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
			//Récupération du timestamp du dernier message de la catégorie.		
			$max_timestamp = $sql->query("SELECT MAX(last_timestamp) as max_timestamp FROM ".PREFIX."forum_topics WHERE idcat = '" . $idcat_topic . "'", __LINE__, __FILE__); 
			$last_topic_id = $sql->query("SELECT id FROM ".PREFIX."forum_topics WHERE last_timestamp = '" . $max_timestamp . "'", __LINE__, __FILE__);
			//On met maintenant a jour le last_topic_id dans les catégories.
			$sql->query_inject("UPDATE ".PREFIX."forum_cats SET last_topic_id = '" . $last_topic_id . "' WHERE id_left <= '" . $CAT_FORUM[$idcat_topic]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat_topic]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat_topic]['level'] . "'", __LINE__, __FILE__);
		}
		
		//Regénération du flux rss.
		include_once('../includes/rss.class.php');
		$rss = new Rss('forum/rss.php');
		$rss->cache_path('../cache/');
		$rss->generate_file('javascript', 'rss_forum');
		$rss->generate_file('php', 'rss2_forum');
		
		####################Fin redirection####################
		$last_page = ceil( ($nbr_msg - 1) / $CONFIG_FORUM['pagination_msg'] );
		$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
		$last_page = ($last_page > 1) ? '&pt=' . $last_page : '';
		
		if( isset($redirect) ) //Redirection après suppression.
		{
			header('location:' . HOST . DIR . '/forum/forum' . transid('.php?id=' . $idcat_topic, '-' . $idcat_topic . '.php', '&'));
			exit;
		}
		else
		{
			header('location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $msg['idtopic'] . $last_page, '-' . $msg['idtopic'] . $last_page_rewrite . '.php', '&') . '#m' . $previous_msg_id);
			exit;
		}
	}
	else
	{
		header('Location:' . HOST . DIR . '/forum/index.php' . SID2);
		exit;
	}
}
elseif( !empty($track) && $session->check_auth($session->data, 0) ) //Ajout du sujet, aux sujets suivis
{
	$exist = $sql->query("SELECT COUNT(*) FROM ".PREFIX."forum_track WHERE user_id = '" . $session->data['user_id'] . "' AND idtopic = '" . $track . "'", __LINE__, __FILE__);	
	if( $exist == 0 )
		$sql->query_inject("INSERT INTO ".PREFIX."forum_track (idtopic,user_id, pm, mail) VALUES('" . $track . "', '" . $session->data['user_id'] . "', 0, 0)", __LINE__, __FILE__);
	
	//Limite de sujets suivis?
	if( !$groups->check_auth($CONFIG_FORUM['auth'], TRACK_TOPIC_FORUM) )
	{
		//Récupère par la variable @compt l'id du topic le plus vieux autorisé par la limite de sujet suivis.
		$sql->query("SELECT @compt := id 
		FROM ".PREFIX."forum_track 
		WHERE user_id = '" . $session->data['user_id'] . "' 
		ORDER BY id DESC
		" . $sql->sql_limit(0, $CONFIG_FORUM['topic_track']), __LINE__, __FILE__);	
		
		//Suppression des sujets suivis dépassant le nbr maximum autorisé.
		$sql->query_inject("DELETE FROM ".PREFIX."forum_track WHERE user_id = '" . $session->data['user_id'] . "' AND id < @compt", __LINE__, __FILE__);
	}
	
	header('location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $track, '-' . $track . '.php', '&') . '#quote');
	exit;
}
elseif( !empty($untrack) && $session->check_auth($session->data, 0) ) //Retrait du sujet, aux sujets suivis
{
	$sql->query_inject("DELETE FROM ".PREFIX."forum_track WHERE idtopic = '" . $untrack . "' AND user_id = '" . $session->data['user_id'] . "'", __LINE__, __FILE__);

	header('location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $untrack, '-' . $untrack . '.php', '&') . '#quote');
	exit;
}
elseif( !empty($read) && $session->check_auth($session->data, 0) )
{
	//Calcul du temps de péremption, ou de dernière vue des messages.
	$check_last_view_forum = $sql->query("SELECT COUNT(*) FROM ".PREFIX."member_extend WHERE user_id = '" . $session->data['user_id'] . "'", __LINE__, __FILE__);

	//Modification du last_view_forum, si le membre est déjà dans la table
	if( !empty($check_last_view_forum) )
		$sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."member_extend SET last_view_forum = '" .  time(). "' WHERE user_id = '" . $session->data['user_id'] . "'", __LINE__, __FILE__); 	
	else
		$sql->query_inject("INSERT INTO ".PREFIX."member_extend (user_id,last_view_forum) VALUES ('" . $session->data['user_id'] . "', '" .  time(). "')", __LINE__, __FILE__); 	

		header('location:' . HOST . DIR . '/forum/index.php' . SID2);
	exit;
}
else
{
	header('Location:' . HOST . DIR . '/forum/index.php' . SID2);
	exit;
}

include_once('../includes/footer_no_display.php');

?>