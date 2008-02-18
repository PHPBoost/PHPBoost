<?php
/*##################################################
 *                               forum.class.php
 *                            -------------------
 *   begin                : December 10, 2007
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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('NO_HISTORY', false);

class Forum
{	
	## Public Methods ##
	//Constructeur
	function Forum() 
	{
	}
	
	//Ajout d'un message.
	function Add_msg($idtopic, $idcat, $contents, $title, $last_page, $last_page_rewrite, $new_topic = false)
	{
		global $CONFIG, $Sql, $Member, $CAT_FORUM, $LANG;
		
		##### Insertion message #####
		$last_timestamp = time();
		$Sql->Query_inject("INSERT INTO ".PREFIX."forum_msg (idtopic, user_id, contents, timestamp, timestamp_edit, user_id_edit, user_ip) VALUES ('" . $idtopic . "', '" . $Member->Get_attribute('user_id') . "', '" . parse($contents) . "', '" . $last_timestamp . "', '0', '0', '" . USER_IP . "')", __LINE__, __FILE__);
		$last_msg_id = $Sql->Sql_insert_id("SELECT MAX(id) FROM ".PREFIX."forum_msg"); 
		
		//Topic
		$Sql->Query_inject("UPDATE ".PREFIX."forum_topics SET " . ($new_topic ? '' : 'nbr_msg = nbr_msg + 1, ') . "last_user_id = '" . $Member->Get_attribute('user_id') . "', last_msg_id = '" . $last_msg_id . "', last_timestamp = '" . $last_timestamp . "' WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
		
		//On met à jour le last_topic_id dans la catégorie dans le lequel le message a été posté, et le nombre de messages..
		$Sql->Query_inject("UPDATE ".PREFIX."forum_cats SET last_topic_id = '" . $idtopic . "', nbr_msg = nbr_msg + 1" . ($new_topic ? ', nbr_topic = nbr_topic + 1' : '') . " WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'", __LINE__, __FILE__);
		
		//Mise à jour du nombre de messages du membre.
		$Sql->Query_inject("UPDATE ".PREFIX."member SET user_msg = user_msg + 1 WHERE user_id = '" . $Member->Get_attribute('user_id') . "'", __LINE__, __FILE__);
		
		//On marque le topic comme lu.
		mark_topic_as_read($idtopic, $last_msg_id, $last_timestamp);
		
		##### Gestion suivi du sujet mp/mail #####
		if( !$new_topic )
		{
			//Message précédent ce nouveau message.
			$previous_msg_id = $Sql->Query("SELECT MAX(id) FROM ".PREFIX."forum_msg WHERE idtopic = '" . $idtopic . "' AND id < '" . $last_msg_id . "'", __LINE__, __FILE__);
		
			$title_subject = html_entity_decode($title);
			$title_subject_pm = '[url=' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $idtopic . $last_page, '-' . $idtopic . $last_page_rewrite . '.php') . '#m' . $previous_msg_id . ']' . $title_subject . '[/url]';			
			$title_subject_mail = "\n" . HOST . DIR . '/forum/topic' . transid('.php?id=' . $idtopic . $last_page, '-' . $idtopic . $last_page_rewrite . '.php') . '#m' . $previous_msg_id;				
			if( $Member->Get_attribute('user_id') > 0 )
			{
				$pseudo = $Sql->Query("SELECT login FROM ".PREFIX."member WHERE user_id = '" . $Member->Get_attribute('user_id') . "'", __LINE__, __FILE__); 
				$pseudo_pm = '[url=' . HOST . DIR . '/member/member.php?id=' . $Member->Get_attribute('user_id') . ']' . $pseudo . '[/url]';
			}
			else
			{	
				$pseudo = $LANG['guest'];
				$pseudo_pm = $LANG['guest'];				
			}				
			$next_pm = '[url=' . HOST . DIR . '/forum/topic.php?id=' . $idtopic . $last_page . '#m' . $previous_msg_id . '][' . $LANG['next'] . '][/url]';
			$preview_contents = substr($contents, 0, 300);			

			include_once('../includes/mail.class.php');
			$Mail = new Mail();				
			include_once('../includes/pm.class.php');
			$Privatemsg = new Privatemsg();
			
			//Récupération des membres suivant le sujet.
			$max_time = time() - $CONFIG['site_session_invit'];
			$result = $Sql->Query_while("SELECT m.user_id, m.user_mail, tr.pm, tr.mail, v.last_view_id, s.session_time 
			FROM ".PREFIX."forum_track tr
			LEFT JOIN ".PREFIX."member m ON m.user_id = tr.user_id
			LEFT JOIN ".PREFIX."forum_view v ON v.idtopic = '" . $idtopic . "' AND v.user_id = tr.user_id
			LEFT JOIN ".PREFIX."sessions s ON s.user_id = tr.user_id
			WHERE tr.idtopic = '" . $idtopic . "' AND v.last_view_id IS NOT NULL AND m.user_id != '" . $Member->Get_attribute('user_id') . "'", __LINE__, __FILE__);
			while($row = $Sql->Sql_fetch_assoc($result) )
			{
				//Envoi un Mail à ceux dont le last_view_id est le message précedent, et qui ne sont pas connectés sur le site.
				if( $row['last_view_id'] == $previous_msg_id && $row['mail'] == '1' && $row['session_time'] < $max_time ) 
					$Mail->Send_mail($row['user_mail'], $LANG['forum_mail_title_new_post'], sprintf($LANG['forum_mail_new_post'], $title_subject, $pseudo, $preview_contents, $title_subject_mail), $CONFIG['mail']);

				//Envoi un MP à ceux dont le last_view_id est le message précedent.
				if( $row['last_view_id'] == $previous_msg_id && $row['pm'] == '1' ) 
					$Privatemsg->Send_pm($row['user_id'], addslashes($LANG['forum_mail_title_new_post']), sprintf(addslashes($LANG['forum_mail_new_post']), addslashes($title_subject_pm), addslashes($pseudo_pm), $preview_contents, addslashes($next_pm)), '-1', SYSTEM_PM);
			}
			
			forum_generate_rss(); //Regénération du flux rss.
		}
		
		return $last_msg_id;
	}	
	
	//Ajout d'un sujet.
	function Add_topic($idcat, $title, $subtitle, $contents, $type)
	{
		global $Sql, $Member;
		
		$Sql->Query_inject("INSERT INTO ".PREFIX."forum_topics (idcat, title, subtitle, user_id, nbr_msg, nbr_views, last_user_id, last_msg_id, last_timestamp, first_msg_id, type, status, aprob, display_msg) VALUES ('" . $idcat . "', '" . $title . "', '" . $subtitle . "', '" . $Member->Get_attribute('user_id') . "', 1, 0, '" . $Member->Get_attribute('user_id') . "', '0', '" . time() . "', 0, '" . $type . "', 1, 0, 0)", __LINE__, __FILE__);
		$last_topic_id = $Sql->Sql_insert_id("SELECT MAX(id) FROM ".PREFIX."forum_topics");	//Dernier topic inseré
		
		$last_msg_id = $this->Add_msg($last_topic_id, $idcat, $contents, $title, $last_page, $last_page_rewrite, true); //Insertion du message.
		$Sql->Query_inject("UPDATE ".PREFIX."forum_topics SET first_msg_id = '" . $last_msg_id . "' WHERE id = '" . $last_topic_id . "'", __LINE__, __FILE__);
				
		forum_generate_rss(); //Regénération du flux rss.
		
		return array($last_topic_id, $last_msg_id);
	}
	
	//Edition d'un message.
	function Update_msg($idtopic, $idmsg, $contents, $user_id_msg, $history = true)
	{
		global $Sql, $Member, $Group, $CONFIG_FORUM;
		
		//Marqueur d'édition du message?					
		$edit_mark = (!$Member->Check_auth($CONFIG_FORUM['auth'], EDIT_MARK_FORUM)) ? ", timestamp_edit = '" . time() . "', user_id_edit = '" . $Member->Get_attribute('user_id') . "'" : '';
		$Sql->Query_inject("UPDATE ".PREFIX."forum_msg SET contents = '" . parse($contents) . "'" . $edit_mark . " WHERE id = '" . $idmsg . "'", __LINE__, __FILE__);
		
		$nbr_msg_before = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."forum_msg WHERE idtopic = '" . $idtopic . "' AND id < '" . $idmsg . "'", __LINE__, __FILE__);
		
		//Calcul de la page sur laquelle se situe le message.
		$msg_page = ceil( ($nbr_msg_before + 1) / $CONFIG_FORUM['pagination_msg'] );
		$msg_page_rewrite = ($msg_page > 1) ? '-' . $msg_page : '';
		$msg_page = ($msg_page > 1) ? '&pt=' . $msg_page : '';
					
		//Insertion de l'action dans l'historique.
		if( $Member->Get_attribute('user_id') != $user_id_msg && $history ) 
			forum_history_collector(H_EDIT_MSG, $user_id_msg, 'topic' . transid('.php?id=' . $idtopic . $msg_page, '-' . $idtopic .  $msg_page_rewrite . '.php', '&') . '#m' . $idmsg);
			
		return $nbr_msg_before;
	}
	
	//Edition d'un sujet.
	function Update_topic($idtopic, $idmsg, $title, $subtitle, $contents, $type, $user_id_msg)
	{
		global $Sql, $Member;
		
		//Mise à jour du sujet.
		$Sql->Query_inject("UPDATE ".PREFIX."forum_topics SET title = '" . $title . "', subtitle = '" . $subtitle . "', type = '" . $type . "' WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
		//Mise à jour du contenu du premier message du sujet.
		$this->Update_msg($idtopic, $idmsg, $contents, $user_id_msg, NO_HISTORY);

		//Insertion de l'action dans l'historique.
		if( $Member->Get_attribute('user_id') != $user_id_msg ) 
			forum_history_collector(H_EDIT_TOPIC, $user_id_msg, 'topic' . transid('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}
		
	//Supression d'un message.
	function Del_msg($idmsg, $idtopic, $idcat, $first_msg_id, $last_msg_id, $last_timestamp, $msg_user_id)
	{
		global $Sql, $Member, $CAT_FORUM, $CONFIG_FORUM;
		
		if( $first_msg_id != $idmsg ) //Suppression d'un message.
		{
			//On compte le nombre de messages du topic avant l'id supprimé.
			$nbr_msg = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."forum_msg WHERE idtopic = '" . $idtopic . "' AND id < '" . $idmsg . "'", __LINE__, __FILE__);	
			//On supprime le message demandé.
			$Sql->Query_inject("DELETE FROM ".PREFIX."forum_msg WHERE id = '" . $idmsg . "'", __LINE__, __FILE__);
			//On met à jour la table forum_topics.
			$Sql->Query_inject("UPDATE ".PREFIX."forum_topics SET nbr_msg = nbr_msg - 1 WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
			//On retranche d'un messages la catégorie concernée.
			$Sql->Query_inject("UPDATE ".PREFIX."forum_cats SET nbr_msg = nbr_msg - 1 WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'", __LINE__, __FILE__);
			//Récupération du message suivant celui supprimé afin de rediriger vers la bonne ancre.
			$previous_msg_id = $Sql->Query("SELECT id FROM ".PREFIX."forum_msg WHERE idtopic = '" . $idtopic . "' AND id < '" . $idmsg . "' ORDER BY timestamp DESC " . $Sql->Sql_limit(0, 1), __LINE__, __FILE__);

			if( $last_msg_id == $idmsg ) //On met à jour le dernier message posté dans la liste des topics.
			{
				//On cherche les infos à propos de l'avant dernier message afin de mettre la table forum_topics à jour.
				$id_before_last = $Sql->Query_array('forum_msg', 'user_id', 'timestamp', "WHERE id = '" . $previous_msg_id . "'", __LINE__, __FILE__);	
				$last_timestamp = $id_before_last['timestamp'];
				$Sql->Query_inject("UPDATE ".PREFIX."forum_topics SET last_user_id = '" . $id_before_last['user_id'] . "', last_msg_id = '" . $previous_msg_id . "', last_timestamp = '" . $last_timestamp . "' WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
	
				//On met maintenant a jour le last_topic_id dans les catégories.
				$this->Update_last_topic_id($idcat);
			}
			
			//On retire un msg au membre.
			$Sql->Query_inject("UPDATE ".PREFIX."member SET user_msg = user_msg - 1 WHERE user_id = '" . $msg_user_id . "'", __LINE__, __FILE__);
			
			//Mise à jour du dernier message lu par les membres.
			$Sql->Query_inject("UPDATE ".PREFIX."forum_view SET last_view_id = '" . $previous_msg_id . "' WHERE last_view_id = '" . $idmsg . "'", __LINE__, __FILE__);
			//On marque le topic comme lu.
			mark_topic_as_read($idtopic, $previous_msg_id, $last_timestamp);
			
			//Insertion de l'action dans l'historique.
			if( $msg_user_id != $Member->Get_attribute('user_id') ) 
			{
				//Calcul de la page sur laquelle se situe le message.
				$msg_page = ceil($nbr_msg / $CONFIG_FORUM['pagination_msg']);
				$msg_page_rewrite = ($msg_page > 1) ? '-' . $msg_page : '';
				$msg_page = ($msg_page > 1) ? '&pt=' . $msg_page : '';
				forum_history_collector(H_DELETE_MSG, $msg_user_id, 'topic' . transid('.php?id=' . $idtopic . $msg_page, '-' . $idtopic .  $msg_page_rewrite . '.php', '&') . '#m' . $previous_msg_id);
			}
			forum_generate_rss(); //Regénération du flux rss.
			
			return array($nbr_msg, $previous_msg_id);
		}
		
		return array(false, false);
	}
	
	//Suppresion d'un sujet.
	function Del_topic($idtopic, $generate_rss = true)
	{
		global $Sql, $Member, $CAT_FORUM;
		
		$topic = $Sql->Query_array('forum_topics', 'idcat', 'user_id', "WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
		$topic['user_id'] = (int)$topic['user_id'];
		
		//On ne supprime pas de msg aux membres ayant postés dans le topic => trop de requêtes.
		//On compte le nombre de messages du topic.
		$nbr_msg = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."forum_msg WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);	
		$nbr_msg = !empty($nbr_msg) ? numeric($nbr_msg) : 1;
		
		//On rippe le topic ainsi que les messages du topic.
		$Sql->Query_inject("DELETE FROM ".PREFIX."forum_msg WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
		$Sql->Query_inject("DELETE FROM ".PREFIX."forum_topics WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
		//Suppression du sondage éventuellement associé.
		$Sql->Query_inject("DELETE FROM ".PREFIX."forum_poll WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
		
		//On retranche le nombre de messages et de topic.
		$Sql->Query_inject("UPDATE ".PREFIX."forum_cats SET nbr_topic = nbr_topic - 1, nbr_msg = nbr_msg - '" . $nbr_msg . "' WHERE id_left <= '" . $CAT_FORUM[$topic['idcat']]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$topic['idcat']]['id_right'] ."' AND level <= '" . $CAT_FORUM[$topic['idcat']]['level'] . "'", __LINE__, __FILE__);			
		
		//On met maintenant a jour le last_topic_id dans les catégories.
		$this->Update_last_topic_id($topic['idcat']);
		
		//Topic supprimé, on supprime les marqueurs de messages lus pour ce topic.
		$Sql->Query_inject("DELETE FROM ".PREFIX."forum_view WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
		
		//Insertion de l'action dans l'historique.
		if( $topic['user_id'] != $Member->Get_attribute('user_id') ) 
			forum_history_collector(H_DELETE_TOPIC, $topic['user_id'], 'forum' . transid('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '.php', '&'));
		
		if( $generate_rss )
			forum_generate_rss(); //Regénération du flux rss.
	}
	
	//Suivi d'un sujet.
	function Track_topic($idtopic)
	{
		global $Sql, $Group, $Member, $CONFIG_FORUM;
		
		$exist = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."forum_track WHERE user_id = '" . $Member->Get_attribute('user_id') . "' AND idtopic = '" . $idtopic . "'", __LINE__, __FILE__);	
		if( $exist == 0 )
			$Sql->Query_inject("INSERT INTO ".PREFIX."forum_track (idtopic, user_id, pm, mail) VALUES('" . $idtopic . "', '" . $Member->Get_attribute('user_id') . "', 0, 0)", __LINE__, __FILE__);
		
		//Limite de sujets suivis?
		if( !$Member->Check_auth($CONFIG_FORUM['auth'], TRACK_TOPIC_FORUM) )
		{
			//Récupère par la variable @compt l'id du topic le plus vieux autorisé par la limite de sujet suivis.
			$Sql->Query("SELECT @compt := id 
			FROM ".PREFIX."forum_track 
			WHERE user_id = '" . $Member->Get_attribute('user_id') . "' 
			ORDER BY id DESC
			" . $Sql->Sql_limit(0, $CONFIG_FORUM['topic_track']), __LINE__, __FILE__);	
			
			//Suppression des sujets suivis dépassant le nbr maximum autorisé.
			$Sql->Query_inject("DELETE FROM ".PREFIX."forum_track WHERE user_id = '" . $Member->Get_attribute('user_id') . "' AND id < @compt", __LINE__, __FILE__);
		}
	}
	
	//Retrait du suivi d'un sujet.
	function Untrack_topic($idtopic)
	{
		global $Sql, $Member;
		
		$Sql->Query_inject("DELETE FROM ".PREFIX."forum_track WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $Member->Get_attribute('user_id') . "'", __LINE__, __FILE__);
	}
	
	//Verrouillage d'un sujet.
	function Lock_topic($idtopic)
	{
		global $Sql;
		
		$Sql->Query_inject("UPDATE ".PREFIX."forum_topics SET status = 0 WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
				
		//Insertion de l'action dans l'historique.
		forum_history_collector(H_LOCK_TOPIC, 0, 'topic' . transid('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}
	
	//Déverrouillage d'un sujet.
	function Unlock_topic($idtopic)
	{
		global $Sql;
		
		$Sql->Query_inject("UPDATE ".PREFIX."forum_topics SET status = 1 WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
				
		//Insertion de l'action dans l'historique.
		forum_history_collector(H_UNLOCK_TOPIC, 0, 'topic' . transid('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}
	
	//Déplacement d'un sujet.
	function Move_topic($idtopic, $idcat, $idcat_dest)
	{
		global $Sql, $Member, $CAT_FORUM;
		
		//On va chercher le nombre de messages dans la table topics
		$topic = $Sql->Query_array("forum_topics", "user_id", "nbr_msg", "WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
		$topic['nbr_msg'] = !empty($topic['nbr_msg']) ? numeric($topic['nbr_msg']) : 1;
		
		//On déplace le topic dans la nouvelle catégorie
		$Sql->Query_inject("UPDATE ".PREFIX."forum_topics SET idcat = '" . $idcat_dest . "' WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
		
		//On met à jour l'ancienne table
		$Sql->Query_inject("UPDATE ".PREFIX."forum_cats SET nbr_msg = nbr_msg - '" . $topic['nbr_msg'] . "', nbr_topic = nbr_topic - 1 WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'", __LINE__, __FILE__);
		//On met maintenant a jour le last_topic_id dans les catégories.
		$this->Update_last_topic_id($idcat);
		
		//On met à jour la nouvelle table
		$Sql->Query_inject("UPDATE ".PREFIX."forum_cats SET nbr_msg = nbr_msg + '" . $topic['nbr_msg'] . "', nbr_topic = nbr_topic + 1 WHERE id_left <= '" . $CAT_FORUM[$idcat_dest]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat_dest]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat_dest]['level'] . "'", __LINE__, __FILE__);
		//On met maintenant a jour le last_topic_id dans les catégories.
		$this->Update_last_topic_id($idcat_dest);
				
		//Insertion de l'action dans l'historique.
		forum_history_collector(H_MOVE_TOPIC, $topic['user_id'], 'topic' . transid('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}
	
	//Déplacement d'un sujet
	function Cut_topic($id_msg_cut, $idtopic, $idcat, $idcat_dest, $title, $subtitle, $contents, $type, $msg_user_id, $last_user_id, $last_msg_id, $last_timestamp)
	{
		global $Sql, $Member, $CAT_FORUM;
		
		//Calcul du nombre de messages déplacés.
		$nbr_msg = $Sql->Query("SELECT COUNT(*) as compt FROM ".PREFIX."forum_msg WHERE idtopic = '" . $idtopic . "' AND id >= '" . $id_msg_cut . "'", __LINE__, __FILE__);
		$nbr_msg = !empty($nbr_msg) ? numeric($nbr_msg) : 1;
		
		//Insertion nouveau topic.
		$Sql->Query_inject("INSERT INTO ".PREFIX."forum_topics (idcat, title, subtitle, user_id, nbr_msg, nbr_views, last_user_id, last_msg_id, last_timestamp, first_msg_id, type, status, aprob) VALUES ('" . $idcat_dest . "', '" . $title . "', '" . $subtitle . "', '" . $msg_user_id . "', '" . $nbr_msg . "', 0, '" . $last_user_id . "', '" . $last_msg_id . "', '" . $last_timestamp . "', '" . $id_msg_cut . "', '" . $type . "', 1, 0)", __LINE__, __FILE__);
		$last_topic_id = $Sql->Sql_insert_id("SELECT MAX(id) FROM ".PREFIX."forum_topics");	//Dernier topic inseré
		
		//Mise à jour du message.
		$Sql->Query_inject("UPDATE ".PREFIX."forum_msg SET contents = '" . $contents . "' WHERE id = '" . $id_msg_cut . "'", __LINE__, __FILE__);
		
		//Déplacement des messages.
		$Sql->Query_inject("UPDATE ".PREFIX."forum_msg SET idtopic = '" . $last_topic_id . "' WHERE idtopic = '" . $idtopic . "' AND id >= '" . $id_msg_cut . "'", __LINE__, __FILE__);
		
		//Mise à jour de l'ancien topic
		$previous_topic = $Sql->Query_array('forum_msg', 'id', 'user_id', 'timestamp', "WHERE id < '" . $id_msg_cut . "' AND idtopic = '" . $idtopic . "' ORDER BY timestamp DESC " . $Sql->Sql_limit(0, 1), __LINE__, __FILE__);
		$Sql->Query_inject("UPDATE ".PREFIX."forum_topics SET last_user_id = '" . $previous_topic['user_id'] . "', last_msg_id = '" . $previous_topic['id'] . "', nbr_msg = nbr_msg - " . $nbr_msg . ", last_timestamp = '" . $previous_topic['timestamp'] . "'  WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
		
		//Mise à jour de l'ancienne catégorie, si elle est différente.
		if( $idcat != $idcat_dest )
		{
			//Mise à jour du nombre de messages de la nouvelle catégorie, ainsi que du last_topic_id.
			$Sql->Query_inject("UPDATE ".PREFIX."forum_cats SET nbr_topic = nbr_topic + 1, nbr_msg = nbr_msg + '" . $nbr_msg . "' WHERE id_left <= '" . $CAT_FORUM[$idcat_dest]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat_dest]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat_dest]['level'] . "'", __LINE__, __FILE__);
			//On met maintenant a jour le last_topic_id dans les catégories.
			$this->Update_last_topic_id($idcat_dest);
		
			//Mise à jour du nombre de messages de l'ancienne catégorie, ainsi que du last_topic_id.
			$Sql->Query_inject("UPDATE ".PREFIX."forum_cats SET nbr_msg = nbr_msg - '" . $nbr_msg . "' WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'", __LINE__, __FILE__);		
		}
		else //Mise à jour du nombre de messages de la catégorie, ainsi que du last_topic_id.
			$Sql->Query_inject("UPDATE ".PREFIX."forum_cats SET nbr_topic = nbr_topic + 1 WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'", __LINE__, __FILE__);		
		
		//On met maintenant a jour le last_topic_id dans les catégories.
		$this->Update_last_topic_id($idcat);
			
		//On marque comme lu le message avant le message scindé qui est le dernier message de l'ancienne catégorie pour tous les utilisateurs.
		$Sql->Query_inject("UPDATE ".PREFIX."forum_view SET last_view_id = '" . $previous_topic['id'] . "', timestamp = '" . time() . "' WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
				
		//Insertion de l'action dans l'historique.
		forum_history_collector(H_CUT_TOPIC, 0, 'topic' . transid('.php?id=' . $last_topic_id, '-' . $last_topic_id . '.php', '&'));
		
		return $last_topic_id;
	}
	
	//Fusion de deux sujets.
	function Merge_topic($idtopic, $idtopic_merge)
	{
		global $Sql;
		
	}
	
	//Ajoute une alerte sur un sujet.
	function Alert_topic($alert_post, $alert_title, $alert_contents)
	{
		global $Sql, $Member;
		
		$idcat = $Sql->Query("SELECT idcat FROM ".PREFIX."forum_topics WHERE id = '" . $alert_post . "'", __LINE__, __FILE__);
		$Sql->Query_inject("INSERT INTO ".PREFIX."forum_alerts (idcat, idtopic, title, contents, user_id, status, idmodo, timestamp) VALUES ('" . $idcat . "', '" . $alert_post . "', '" . $alert_title . "', '" . $alert_contents . "', '" . $Member->Get_attribute('user_id') . "', 0, 0, '" . time() . "')", __LINE__, __FILE__);
	}
	
	//Passe en résolu une alerte sur un sujet.
	function Solve_alert_topic($id_alert)
	{
		global $Sql;
		
		$Sql->Query_inject("UPDATE ".PREFIX."forum_alerts SET status = 1, idmodo = '" . $Member->Get_attribute('user_id') . "' WHERE id = '" . $id_alert . "'", __LINE__, __FILE__);
		
		//Insertion de l'action dans l'historique.
		$Forumfct->history_collector(H_SOLVE_ALERT, 0, 'moderation_forum.php?action=alert&id=' . $id_alert, '', '&');
	}
	
	//Passe en attente une alerte sur un sujet.
	function Wait_alert_topic($id_alert)
	{
		global $Sql;
		
		$Sql->Query_inject("UPDATE ".PREFIX."forum_alerts SET status = 0, idmodo = 0 WHERE id = '" . $id_alert . "'", __LINE__, __FILE__);
		
		//Insertion de l'action dans l'historique.
		forum_history_collector(H_WAIT_ALERT, 0, 'moderation_forum.php?action=alert&id=' . $id_alert);
	}
	
	//Supprime une alerte sur un sujet.
	function Del_alert_topic($id_alert)
	{
		global $Sql;
		
		$Sql->Query_inject("DELETE FROM ".PREFIX."forum_alerts WHERE id = '" . $id_alert . "'", __LINE__, __FILE__);
		
		//Insertion de l'action dans l'historique.
		forum_history_collector(H_DEL_ALERT);
	}
		
	//Ajout d'un sondage.
	function Add_poll($idtopic, $question, $answers, $voter_id, $votes, $type)
	{	
		global $Sql;
		
		$Sql->Query_inject("INSERT INTO ".PREFIX."forum_poll (idtopic, question, answers, voter_id, votes,type) VALUES ('" . $idtopic . "', '" . $question . "', '" . trim($answers, '|') . "', '" . numeric($voter_id) . "', '" . trim($votes, '|') . "', '" . numeric($type) . "')", __LINE__, __FILE__);
	}
	
	//Edition d'un sondage.
	function Update_poll($idtopic, $question, $answers, $votes, $type)
	{
		global $Sql;
		
		//Vérification => vérifie si il n'y a pas de nouvelle réponses à ajouter.
		$array_answer = explode('|', $Sql->Query("SELECT answers FROM ".PREFIX."forum_poll WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__));
		$nbr_answer	= count($array_answer);

		$new_nbr_answer = $check_nbr_answer - $nbr_answer;
		$votes = '';
		if( $new_nbr_answer > 0 ) //Insertion de nouvelles réponses => ajout de nouveaux 0 dans le champ vote.
		{
			$votes = "votes = CONCAT(votes, '";
			for($i = $nbr_answer; $i < $check_nbr_answer; $i++) 
				$votes .= '|0';
			$votes .= "'), ";							
		}
		elseif( $new_nbr_answer < 0 ) //Suppression d'une réponse => suppréssion des votes associés.
			$votes = "votes = SUBSTRING(votes FROM 1 FOR (CHAR_LENGTH(votes) " . ($new_nbr_answer * 2) . ")), "; //On coupe la chaîne du nombre de réponses en moins.

		$Sql->Query_inject("UPDATE ".PREFIX."forum_poll SET question = '" . $question . "', answers = '" . trim($answers, '|') . "', " . $votes . "type = '" . $type . "' WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
	}
	
	//Suppression d'un sondage.
	function Del_poll($idtopic)
	{
		global $Sql;
		
		$Sql->Query_inject("DELETE FROM ".PREFIX."forum_poll WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
	}
	
	
	## Private Method ##
	//Met à jour chaque catégories quelque soit le niveau de profondeur de la catégorie source. Cas le plus favorable et courant seulement 3 requêtes.
	function update_last_topic_id($idcat)
	{
		global $Sql, $CAT_FORUM;
		
		$clause = "idcat = '" . $idcat . "'";
		if( ($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$idcat]['id_left']) > 1 ) //Sous forums présents.
		{
			//Sous forums du forum à mettre à jour.
			$list_cats = '';
			$result = $Sql->Query_while("SELECT id
			FROM ".PREFIX."forum_cats 
			WHERE id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'
			ORDER BY id_left", __LINE__, __FILE__);
			while( $row = $Sql->Sql_fetch_assoc($result) )
			{
				$list_cats .= $row['id'] . ', ';
			}
			$Sql->Close($result);
			$clause = "idcat IN (" . trim($list_cats, ', ') . ")";			
		}
		
		//Récupération du timestamp du dernier message de la catégorie.		
		$last_timestamp = $Sql->Query("SELECT MAX(last_timestamp) FROM ".PREFIX."forum_topics WHERE " . $clause, __LINE__, __FILE__);
		$last_topic_id = $Sql->Query("SELECT id FROM ".PREFIX."forum_topics WHERE last_timestamp = '" . $last_timestamp . "'", __LINE__, __FILE__);
		if( !empty($last_topic_id) )
			$Sql->Query_inject("UPDATE ".PREFIX."forum_cats SET last_topic_id = '" . $last_topic_id . "' WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
			
		if( $CAT_FORUM[$idcat]['level'] > 1 ) //Appel recursif si sous-forum.
		{	
			//Recherche de l'id du forum parent.
			$idcat_parent = $Sql->Query("SELECT id 
			FROM ".PREFIX."forum_cats 
			WHERE id_left < '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right > '" . $CAT_FORUM[$idcat]['id_right'] . "' AND level = '" .  ($CAT_FORUM[$idcat]['level'] - 1) . "'", __LINE__, __FILE__);

			$this->Update_last_topic_id($idcat_parent); //Appel recursif.
		}
	}
}

?>