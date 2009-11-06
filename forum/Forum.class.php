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
define('FORUM_EMAIL_TRACKING', 1);
define('FORUM_PM_TRACKING', 2);

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
		global $CONFIG, $Sql, $User, $CAT_FORUM, $LANG;

		##### Insertion message #####
		$last_timestamp = time();
		$Sql->query_inject("INSERT INTO " . PREFIX . "forum_msg (idtopic, user_id, contents, timestamp, timestamp_edit, user_id_edit, user_ip) VALUES ('" . $idtopic . "', '" . $User->get_attribute('user_id') . "', '" . strparse($contents) . "', '" . $last_timestamp . "', '0', '0', '" . USER_IP . "')", __LINE__, __FILE__);
		$last_msg_id = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "forum_msg");

		//Topic
		$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET " . ($new_topic ? '' : 'nbr_msg = nbr_msg + 1, ') . "last_user_id = '" . $User->get_attribute('user_id') . "', last_msg_id = '" . $last_msg_id . "', last_timestamp = '" . $last_timestamp . "' WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);

		//On met à jour le last_topic_id dans la catégorie dans le lequel le message a été posté, et le nombre de messages..
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET last_topic_id = '" . $idtopic . "', nbr_msg = nbr_msg + 1" . ($new_topic ? ', nbr_topic = nbr_topic + 1' : '') . " WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'", __LINE__, __FILE__);

		//Mise à jour du nombre de messages du membre.
		$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_msg = user_msg + 1 WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);

		//On marque le topic comme lu.
		mark_topic_as_read($idtopic, $last_msg_id, $last_timestamp);

		##### Gestion suivi du sujet mp/mail #####
		if (!$new_topic)
		{
			//Message précédent ce nouveau message.
			$previous_msg_id = $Sql->query("SELECT MAX(id) FROM " . PREFIX . "forum_msg WHERE idtopic = '" . $idtopic . "' AND id < '" . $last_msg_id . "'", __LINE__, __FILE__);

			$title_subject = html_entity_decode($title);
			$title_subject_pm = '[url=' . HOST . DIR . '/forum/topic' . url('.php?id=' . $idtopic . $last_page, '-' . $idtopic . $last_page_rewrite . '.php') . '#m' . $previous_msg_id . ']' . $title_subject . '[/url]';
			if ($User->get_attribute('user_id') > 0)
			{
				$pseudo = $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
				$pseudo_pm = '[url=' . HOST . DIR . '/member/member.php?id=' . $User->get_attribute('user_id') . ']' . $pseudo . '[/url]';
			}
			else
			{
				$pseudo = $LANG['guest'];
				$pseudo_pm = $LANG['guest'];
			}
			$next_msg_link = HOST . DIR . '/forum/topic' . url('.php?id=' . $idtopic . $last_page, '-' . $idtopic . $last_page_rewrite . '.php') . '#m' . $previous_msg_id;
			$preview_contents = substr($contents, 0, 300);

			
			$Mail = new Mail();
			

			//Récupération des membres suivant le sujet.
			$max_time = time() - $CONFIG['site_session_invit'];
			$result = $Sql->query_while("SELECT m.user_id, m.login, m.user_mail, tr.pm, tr.mail, v.last_view_id
			FROM " . PREFIX . "forum_track tr
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = tr.user_id
			LEFT JOIN " . PREFIX . "forum_view v ON v.idtopic = '" . $idtopic . "' AND v.user_id = tr.user_id
			WHERE tr.idtopic = '" . $idtopic . "' AND v.last_view_id IS NOT NULL AND m.user_id != '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				//Envoi un Mail à ceux dont le last_view_id est le message précedent.
				if ($row['last_view_id'] == $previous_msg_id && $row['mail'] == '1')
				{	
					$Mail->send_from_properties(
						$row['user_mail'], 
						$LANG['forum_mail_title_new_post'], 
						sprintf($LANG['forum_mail_new_post'], $row['login'], $title_subject, $User->get_attribute('login'), $preview_contents, $next_msg_link, HOST . DIR . '/forum/action.php?ut=' . $idtopic . '&trt=1', 1), 
						$CONFIG['mail_exp']
					);
				}	
				
				//Envoi un MP à ceux dont le last_view_id est le message précedent.
				if ($row['last_view_id'] == $previous_msg_id && $row['pm'] == '1')
				{
					PrivateMsg::start_conversation(
						$row['user_id'], 
						addslashes($LANG['forum_mail_title_new_post']), 
						sprintf($LANG['forum_mail_new_post'], $row['login'], $title_subject_pm, $User->get_attribute('login'), $preview_contents, '[url]'.$next_msg_link.'[/url]', '[url]' . HOST . DIR . '/forum/action.php?ut=' . $idtopic . '&trt=2[/url]'), 
						'-1', 
						PrivateMsg::SYSTEM_PM
					);
				}
			}
				
			forum_generate_feeds(); //Regénération du flux rss.
		}

		return $last_msg_id;
	}

	//Ajout d'un sujet.
	function Add_topic($idcat, $title, $subtitle, $contents, $type)
	{
		global $Sql, $User;

		$Sql->query_inject("INSERT INTO " . PREFIX . "forum_topics (idcat, title, subtitle, user_id, nbr_msg, nbr_views, last_user_id, last_msg_id, last_timestamp, first_msg_id, type, status, aprob, display_msg) VALUES ('" . $idcat . "', '" . $title . "', '" . $subtitle . "', '" . $User->get_attribute('user_id') . "', 1, 0, '" . $User->get_attribute('user_id') . "', '0', '" . time() . "', 0, '" . $type . "', 1, 0, 0)", __LINE__, __FILE__);
		$last_topic_id = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "forum_topics");	//Dernier topic inseré

		$last_msg_id = $this->Add_msg($last_topic_id, $idcat, $contents, $title, 0, 0, true); //Insertion du message.
		$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET first_msg_id = '" . $last_msg_id . "' WHERE id = '" . $last_topic_id . "'", __LINE__, __FILE__);

		forum_generate_feeds(); //Regénération des flux flux

		return array($last_topic_id, $last_msg_id);
	}

	//Edition d'un message.
	function Update_msg($idtopic, $idmsg, $contents, $user_id_msg, $history = true)
	{
		global $Sql, $User, $CONFIG_FORUM;

		//Marqueur d'édition du message?
		$edit_mark = (!$User->check_auth($CONFIG_FORUM['auth'], EDIT_MARK_FORUM)) ? ", timestamp_edit = '" . time() . "', user_id_edit = '" . $User->get_attribute('user_id') . "'" : '';
		$Sql->query_inject("UPDATE " . PREFIX . "forum_msg SET contents = '" . strparse($contents) . "'" . $edit_mark . " WHERE id = '" . $idmsg . "'", __LINE__, __FILE__);

		$nbr_msg_before = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "forum_msg WHERE idtopic = '" . $idtopic . "' AND id < '" . $idmsg . "'", __LINE__, __FILE__);

		//Calcul de la page sur laquelle se situe le message.
		$msg_page = ceil( ($nbr_msg_before + 1) / $CONFIG_FORUM['pagination_msg'] );
		$msg_page_rewrite = ($msg_page > 1) ? '-' . $msg_page : '';
		$msg_page = ($msg_page > 1) ? '&pt=' . $msg_page : '';
			
		//Insertion de l'action dans l'historique.
		if ($User->get_attribute('user_id') != $user_id_msg && $history)
		forum_history_collector(H_EDIT_MSG, $user_id_msg, 'topic' . url('.php?id=' . $idtopic . $msg_page, '-' . $idtopic .  $msg_page_rewrite . '.php', '&') . '#m' . $idmsg);

		return $nbr_msg_before;
	}

	//Edition d'un sujet.
	function Update_topic($idtopic, $idmsg, $title, $subtitle, $contents, $type, $user_id_msg)
	{
		global $Sql, $User;

		//Mise à jour du sujet.
		$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET title = '" . $title . "', subtitle = '" . $subtitle . "', type = '" . $type . "' WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
		//Mise à jour du contenu du premier message du sujet.
		$this->Update_msg($idtopic, $idmsg, $contents, $user_id_msg, NO_HISTORY);

		//Insertion de l'action dans l'historique.
		if ($User->get_attribute('user_id') != $user_id_msg)
		forum_history_collector(H_EDIT_TOPIC, $user_id_msg, 'topic' . url('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}

	//Supression d'un message.
	function Del_msg($idmsg, $idtopic, $idcat, $first_msg_id, $last_msg_id, $last_timestamp, $msg_user_id)
	{
		global $Sql, $User, $CAT_FORUM, $CONFIG_FORUM;

		if ($first_msg_id != $idmsg) //Suppression d'un message.
		{
			//On compte le nombre de messages du topic avant l'id supprimé.
			$nbr_msg = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "forum_msg WHERE idtopic = '" . $idtopic . "' AND id < '" . $idmsg . "'", __LINE__, __FILE__);
			//On supprime le message demandé.
			$Sql->query_inject("DELETE FROM " . PREFIX . "forum_msg WHERE id = '" . $idmsg . "'", __LINE__, __FILE__);
			//On met à jour la table forum_topics.
			$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET nbr_msg = nbr_msg - 1 WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
			//On retranche d'un messages la catégorie concernée.
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET nbr_msg = nbr_msg - 1 WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'", __LINE__, __FILE__);
			//Récupération du message précédent celui supprimé afin de rediriger vers la bonne ancre.
			$previous_msg_id = $Sql->query("SELECT id FROM " . PREFIX . "forum_msg WHERE idtopic = '" . $idtopic . "' AND id < '" . $idmsg . "' ORDER BY timestamp DESC " . $Sql->limit(0, 1), __LINE__, __FILE__);

			if ($last_msg_id == $idmsg) //On met à jour le dernier message posté dans la liste des topics.
			{
				//On cherche les infos à propos de l'avant dernier message afin de mettre la table forum_topics à jour.
				$id_before_last = $Sql->query_array(PREFIX . 'forum_msg', 'user_id', 'timestamp', "WHERE id = '" . $previous_msg_id . "'", __LINE__, __FILE__);
				$last_timestamp = $id_before_last['timestamp'];
				$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET last_user_id = '" . $id_before_last['user_id'] . "', last_msg_id = '" . $previous_msg_id . "', last_timestamp = '" . $last_timestamp . "' WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);

				//On met maintenant a jour le last_topic_id dans les catégories.
				$this->Update_last_topic_id($idcat);
			}
				
			//On retire un msg au membre.
			$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_msg = user_msg - 1 WHERE user_id = '" . $msg_user_id . "'", __LINE__, __FILE__);
				
			//Mise à jour du dernier message lu par les membres.
			$Sql->query_inject("UPDATE " . PREFIX . "forum_view SET last_view_id = '" . $previous_msg_id . "' WHERE last_view_id = '" . $idmsg . "'", __LINE__, __FILE__);
			//On marque le topic comme lu, si c'est le dernier du message du topic.
			if ($last_msg_id == $idmsg)
			mark_topic_as_read($idtopic, $previous_msg_id, $last_timestamp);
				
			//Insertion de l'action dans l'historique.
			if ($msg_user_id != $User->get_attribute('user_id'))
			{
				//Calcul de la page sur laquelle se situe le message.
				$msg_page = ceil($nbr_msg / $CONFIG_FORUM['pagination_msg']);
				$msg_page_rewrite = ($msg_page > 1) ? '-' . $msg_page : '';
				$msg_page = ($msg_page > 1) ? '&pt=' . $msg_page : '';
				forum_history_collector(H_DELETE_MSG, $msg_user_id, 'topic' . url('.php?id=' . $idtopic . $msg_page, '-' . $idtopic .  $msg_page_rewrite . '.php', '&') . '#m' . $previous_msg_id);
			}
			forum_generate_feeds(); //Regénération des flux flux
				
			return array($nbr_msg, $previous_msg_id);
		}

		return array(false, false);
	}

	//Suppresion d'un sujet.
	function Del_topic($idtopic, $generate_rss = true)
	{
		global $Sql, $User, $CAT_FORUM;

		$topic = $Sql->query_array(PREFIX . 'forum_topics', 'idcat', 'user_id', "WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
		$topic['user_id'] = (int)$topic['user_id'];

		//On ne supprime pas de msg aux membres ayant postés dans le topic => trop de requêtes.
		//On compte le nombre de messages du topic.
		$nbr_msg = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "forum_msg WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
		$nbr_msg = !empty($nbr_msg) ? numeric($nbr_msg) : 1;

		//On rippe le topic ainsi que les messages du topic.
		$Sql->query_inject("DELETE FROM " . PREFIX . "forum_msg WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
		$Sql->query_inject("DELETE FROM " . PREFIX . "forum_topics WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
		//Suppression du sondage éventuellement associé.
		$Sql->query_inject("DELETE FROM " . PREFIX . "forum_poll WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);

		//On retranche le nombre de messages et de topic.
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET nbr_topic = nbr_topic - 1, nbr_msg = nbr_msg - '" . $nbr_msg . "' WHERE id_left <= '" . $CAT_FORUM[$topic['idcat']]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$topic['idcat']]['id_right'] ."' AND level <= '" . $CAT_FORUM[$topic['idcat']]['level'] . "'", __LINE__, __FILE__);

		//On met maintenant a jour le last_topic_id dans les catégories.
		$this->Update_last_topic_id($topic['idcat']);

		//Topic supprimé, on supprime les marqueurs de messages lus pour ce topic.
		$Sql->query_inject("DELETE FROM " . PREFIX . "forum_view WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);

		//On supprime l'alerte.
		$this->Del_alert_topic($idtopic);
		
		//Insertion de l'action dans l'historique.
		if ($topic['user_id'] != $User->get_attribute('user_id'))
			forum_history_collector(H_DELETE_TOPIC, $topic['user_id'], 'forum' . url('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '.php', '&'));

		if ($generate_rss)
			forum_generate_feeds(); //Regénération des flux flux
	}

	//Suivi d'un sujet.
	function Track_topic($idtopic, $tracking_type = 0)
	{
		global $Sql, $User, $CONFIG_FORUM;

		list($mail, $pm, $track) = array(0, 0, 0);
		if ($tracking_type == 0) //Suivi par email.
			$track = '1';
		elseif ($tracking_type == 1) //Suivi par email.
			$mail = '1';
		elseif ($tracking_type == 2) //Suivi par email.
			$pm = '1';
			
		$exist = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "forum_track WHERE user_id = '" . $User->get_attribute('user_id') . "' AND idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
		if ($exist == 0)
			$Sql->query_inject("INSERT INTO " . PREFIX . "forum_track (idtopic, user_id, track, pm, mail) VALUES('" . $idtopic . "', '" . $User->get_attribute('user_id') . "', '" . $track . "', '" . $pm . "', '" . $mail . "')", __LINE__, __FILE__);
		elseif ($tracking_type == 0)
			$Sql->query_inject("UPDATE " . PREFIX . "forum_track SET track = '1' WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
		elseif ($tracking_type == 1)
			$Sql->query_inject("UPDATE " . PREFIX . "forum_track SET mail = '1' WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
		elseif ($tracking_type == 2)
			$Sql->query_inject("UPDATE " . PREFIX . "forum_track SET pm = '1' WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
			
		//Limite de sujets suivis?
		if (!$User->check_auth($CONFIG_FORUM['auth'], TRACK_TOPIC_FORUM))
		{
			//Récupère par la variable @compt l'id du topic le plus vieux autorisé par la limite de sujet suivis.
			$Sql->query("SELECT @compt := id
			FROM " . PREFIX . "forum_track
			WHERE user_id = '" . $User->get_attribute('user_id') . "'
			ORDER BY id DESC
			" . $Sql->limit(0, $CONFIG_FORUM['topic_track']), __LINE__, __FILE__);
				
			//Suppression des sujets suivis dépassant le nbr maximum autorisé.
			$Sql->query_inject("DELETE FROM " . PREFIX . "forum_track WHERE user_id = '" . $User->get_attribute('user_id') . "' AND id < @compt", __LINE__, __FILE__);
		}
	}

	//Retrait du suivi d'un sujet.
	function Untrack_topic($idtopic, $tracking_type = 0)
	{
		global $Sql, $User;

		if ($tracking_type == 1) //Par mail
		{
			$info = $Sql->query_array(PREFIX . "forum_track", "pm", "track", "WHERE user_id = '" . $User->get_attribute('user_id') . "' AND idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
			if ($info['track'] == 0 && $info['pm'] == 0)
				$Sql->query_inject("DELETE FROM " . PREFIX . "forum_track WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
			else
				$Sql->query_inject("UPDATE " . PREFIX . "forum_track SET mail = '0' WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
		}
		elseif ($tracking_type == 2) //Par mp
		{
			$info = $Sql->query_array(PREFIX . "forum_track", "mail", "track", "WHERE user_id = '" . $User->get_attribute('user_id') . "' AND idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
			if ($info['mail'] == 0 && $info['track'] == 0)
				$Sql->query_inject("DELETE FROM " . PREFIX . "forum_track WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
			else
				$Sql->query_inject("UPDATE " . PREFIX . "forum_track SET pm = '0' WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
		}
		else //Suivi
		{
			$info = $Sql->query_array(PREFIX . "forum_track", "mail", "pm", "WHERE user_id = '" . $User->get_attribute('user_id') . "' AND idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
			if ($info['mail'] == 0 && $info['pm'] == 0)
				$Sql->query_inject("DELETE FROM " . PREFIX . "forum_track WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
			else
				$Sql->query_inject("UPDATE " . PREFIX . "forum_track SET track = '0' WHERE idtopic = '" . $idtopic . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
		}
	}

	//Verrouillage d'un sujet.
	function Lock_topic($idtopic)
	{
		global $Sql;

		$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET status = 0 WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_LOCK_TOPIC, 0, 'topic' . url('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}

	//Déverrouillage d'un sujet.
	function Unlock_topic($idtopic)
	{
		global $Sql;

		$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET status = 1 WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_UNLOCK_TOPIC, 0, 'topic' . url('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}

	//Déplacement d'un sujet.
	function Move_topic($idtopic, $idcat, $idcat_dest)
	{
		global $Sql, $User, $CAT_FORUM;

		//On va chercher le nombre de messages dans la table topics
		$topic = $Sql->query_array(PREFIX . "forum_topics", "user_id", "nbr_msg", "WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);
		$topic['nbr_msg'] = !empty($topic['nbr_msg']) ? numeric($topic['nbr_msg']) : 1;

		//On déplace le topic dans la nouvelle catégorie
		$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET idcat = '" . $idcat_dest . "' WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);

		//On met à jour l'ancienne table
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET nbr_msg = nbr_msg - '" . $topic['nbr_msg'] . "', nbr_topic = nbr_topic - 1 WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'", __LINE__, __FILE__);
		//On met maintenant a jour le last_topic_id dans les catégories.
		$this->Update_last_topic_id($idcat);

		//On met à jour la nouvelle table
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET nbr_msg = nbr_msg + '" . $topic['nbr_msg'] . "', nbr_topic = nbr_topic + 1 WHERE id_left <= '" . $CAT_FORUM[$idcat_dest]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat_dest]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat_dest]['level'] . "'", __LINE__, __FILE__);
		//On met maintenant a jour le last_topic_id dans les catégories.
		$this->Update_last_topic_id($idcat_dest);

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_MOVE_TOPIC, $topic['user_id'], 'topic' . url('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}

	//Déplacement d'un sujet
	function Cut_topic($id_msg_cut, $idtopic, $idcat, $idcat_dest, $title, $subtitle, $contents, $type, $msg_user_id, $last_user_id, $last_msg_id, $last_timestamp)
	{
		global $Sql, $User, $CAT_FORUM;

		//Calcul du nombre de messages déplacés.
		$nbr_msg = $Sql->query("SELECT COUNT(*) as compt FROM " . PREFIX . "forum_msg WHERE idtopic = '" . $idtopic . "' AND id >= '" . $id_msg_cut . "'", __LINE__, __FILE__);
		$nbr_msg = !empty($nbr_msg) ? numeric($nbr_msg) : 1;

		//Insertion nouveau topic.
		$Sql->query_inject("INSERT INTO " . PREFIX . "forum_topics (idcat, title, subtitle, user_id, nbr_msg, nbr_views, last_user_id, last_msg_id, last_timestamp, first_msg_id, type, status, aprob) VALUES ('" . $idcat_dest . "', '" . $title . "', '" . $subtitle . "', '" . $msg_user_id . "', '" . $nbr_msg . "', 0, '" . $last_user_id . "', '" . $last_msg_id . "', '" . $last_timestamp . "', '" . $id_msg_cut . "', '" . $type . "', 1, 0)", __LINE__, __FILE__);
		$last_topic_id = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "forum_topics");	//Dernier topic inseré

		//Mise à jour du message.
		$Sql->query_inject("UPDATE " . PREFIX . "forum_msg SET contents = '" . $contents . "' WHERE id = '" . $id_msg_cut . "'", __LINE__, __FILE__);

		//Déplacement des messages.
		$Sql->query_inject("UPDATE " . PREFIX . "forum_msg SET idtopic = '" . $last_topic_id . "' WHERE idtopic = '" . $idtopic . "' AND id >= '" . $id_msg_cut . "'", __LINE__, __FILE__);

		//Mise à jour de l'ancien topic
		$previous_topic = $Sql->query_array(PREFIX . 'forum_msg', 'id', 'user_id', 'timestamp', "WHERE id < '" . $id_msg_cut . "' AND idtopic = '" . $idtopic . "' ORDER BY timestamp DESC " . $Sql->limit(0, 1), __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET last_user_id = '" . $previous_topic['user_id'] . "', last_msg_id = '" . $previous_topic['id'] . "', nbr_msg = nbr_msg - " . $nbr_msg . ", last_timestamp = '" . $previous_topic['timestamp'] . "'  WHERE id = '" . $idtopic . "'", __LINE__, __FILE__);

		//Mise à jour de l'ancienne catégorie, si elle est différente.
		if ($idcat != $idcat_dest)
		{
			//Mise à jour du nombre de messages de la nouvelle catégorie, ainsi que du last_topic_id.
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET nbr_topic = nbr_topic + 1, nbr_msg = nbr_msg + '" . $nbr_msg . "' WHERE id_left <= '" . $CAT_FORUM[$idcat_dest]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat_dest]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat_dest]['level'] . "'", __LINE__, __FILE__);
			//On met maintenant a jour le last_topic_id dans les catégories.
			$this->Update_last_topic_id($idcat_dest);

			//Mise à jour du nombre de messages de l'ancienne catégorie, ainsi que du last_topic_id.
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET nbr_msg = nbr_msg - '" . $nbr_msg . "' WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'", __LINE__, __FILE__);
		}
		else //Mise à jour du nombre de messages de la catégorie, ainsi que du last_topic_id.
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET nbr_topic = nbr_topic + 1 WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat]['level'] . "'", __LINE__, __FILE__);

		//On met maintenant a jour le last_topic_id dans les catégories.
		$this->Update_last_topic_id($idcat);
			
		//On marque comme lu le message avant le message scindé qui est le dernier message de l'ancienne catégorie pour tous les utilisateurs.
		$Sql->query_inject("UPDATE " . PREFIX . "forum_view SET last_view_id = '" . $previous_topic['id'] . "', timestamp = '" . time() . "' WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_CUT_TOPIC, 0, 'topic' . url('.php?id=' . $last_topic_id, '-' . $last_topic_id . '.php', '&'));

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
		global $Sql, $User, $CAT_FORUM, $LANG;

		$topic_infos = $Sql->query_array(PREFIX . "forum_topics", "idcat", "title", "WHERE id = '" . $alert_post . "'", __LINE__, __FILE__);
		$Sql->query_inject("INSERT INTO " . PREFIX . "forum_alerts (idcat, idtopic, title, contents, user_id, status, idmodo, timestamp) VALUES ('" . $topic_infos['idcat'] . "', '" . $alert_post . "', '" . $alert_title . "', '" . $alert_contents . "', '" . $User->get_attribute('user_id') . "', 0, 0, '" . time() . "')", __LINE__, __FILE__);

		$alert_id = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "forum_alerts");

		//Importing the contribution classes
		
		

		$contribution = new Contribution();

		//The id of the file in the module. It's useful when the module wants to search a contribution (we will need it in the file edition)
		$contribution->set_id_in_module($alert_id);
		//The entitled of the contribution
		$contribution->set_entitled(sprintf($LANG['contribution_alert_moderators_for_topics'], stripslashes($alert_title)));
		//The URL where a validator can treat the contribution (in the file edition panel)
		$contribution->set_fixing_url('/forum/moderation_forum.php?action=alert&id=' . $alert_id);
		//Description
		$contribution->set_description(stripslashes($alert_contents));
		//Who is the contributor?
		$contribution->set_poster_id($User->get_attribute('user_id'));
		//The module
		$contribution->set_module('forum');
		//It's an alert, we will be able to manage other kinds of contributions in the module if we choose to use a type.
		$contribution->set_type('alert');

		//Assignation des autorisations d'écriture / Writing authorization assignation
		$contribution->set_auth(
		//On déplace le bit sur l'autorisation obtenue pour le mettre sur celui sur lequel travaille les contributions, à savoir Contribution::CONTRIBUTION_AUTH_BIT
		//We shift the authorization bit to the one with which the contribution class works, Contribution::CONTRIBUTION_AUTH_BIT
		Authorizations::capture_and_shift_bit_auth(
		$CAT_FORUM[$topic_infos['idcat']]['auth'],
		EDIT_CAT_FORUM, Contribution::CONTRIBUTION_AUTH_BIT
		)
		);

		//Sending the contribution to the kernel. It will place it in the contribution panel to be approved
		ContributionService::save_contribution($contribution);
	}

	//Passe en résolu une alerte sur un sujet.
	function Solve_alert_topic($id_alert)
	{
		global $Sql, $User;

		$Sql->query_inject("UPDATE " . PREFIX . "forum_alerts SET status = 1, idmodo = '" . $User->get_attribute('user_id') . "' WHERE id = '" . $id_alert . "'", __LINE__, __FILE__);

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_SOLVE_ALERT, 0, 'moderation_forum.php?action=alert&id=' . $id_alert, '', '&');

		//Si la contribution associée n'est pas réglée, on la règle
		
		
		 
		$corresponding_contributions = ContributionService::find_by_criteria('forum', $id_alert, 'alert');
		if (count($corresponding_contributions) > 0)
		{
			$file_contribution = $corresponding_contributions[0];
			//The contribution is now processed
			$file_contribution->set_status(Event::EVENT_STATUS_PROCESSED);

			//We save the contribution
			ContributionService::save_contribution($file_contribution);
		}
	}

	//Passe en attente une alerte sur un sujet.
	function Wait_alert_topic($id_alert)
	{
		global $Sql;

		$Sql->query_inject("UPDATE " . PREFIX . "forum_alerts SET status = 0, idmodo = 0 WHERE id = '" . $id_alert . "'", __LINE__, __FILE__);

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_WAIT_ALERT, 0, 'moderation_forum.php?action=alert&id=' . $id_alert);
	}

	//Supprime une alerte sur un sujet.
	function Del_alert_topic($id_alert)
	{
		global $Sql;

		$Sql->query_inject("DELETE FROM " . PREFIX . "forum_alerts WHERE id = '" . $id_alert . "'", __LINE__, __FILE__);
		
		//Si la contribution associée n'est pas réglée, on la règle
		
		
		 
		$corresponding_contributions = ContributionService::find_by_criteria('forum', $id_alert, 'alert');
		if (count($corresponding_contributions) > 0)
		{
			$file_contribution = $corresponding_contributions[0];

			//We delete the contribution
			ContributionService::delete_contribution($file_contribution);
		}

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_DEL_ALERT);
	}

	//Ajout d'un sondage.
	function Add_poll($idtopic, $question, $answers, $nbr_votes, $type)
	{
		global $Sql;

		$Sql->query_inject("INSERT INTO " . PREFIX . "forum_poll (idtopic, question, answers, voter_id, votes,type) VALUES ('" . $idtopic . "', '" . $question . "', '" . implode('|', $answers) . "', '0', '" . trim(str_repeat('0|', $nbr_votes), '|') . "', '" . numeric($type) . "')", __LINE__, __FILE__);
	}

	//Edition d'un sondage.
	function Update_poll($idtopic, $question, $answers, $type)
	{
		global $Sql;

		//Vérification => vérifie si il n'y a pas de nouvelle réponses à ajouter.
		$previous_votes = explode('|', $Sql->query("SELECT votes FROM " . PREFIX . "forum_poll WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__));

		$votes = array();
		foreach ($answers as $key => $answer_value) //Récupération des votes précédents.
		$votes[$key] = isset($previous_votes[$key]) ? $previous_votes[$key] : 0;

		$Sql->query_inject("UPDATE " . PREFIX . "forum_poll SET question = '" . $question . "', answers = '" . implode('|', $answers) . "', votes = '" . implode('|', $votes) . "', type = '" . $type . "' WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
	}

	//Suppression d'un sondage.
	function Del_poll($idtopic)
	{
		global $Sql;

		$Sql->query_inject("DELETE FROM " . PREFIX . "forum_poll WHERE idtopic = '" . $idtopic . "'", __LINE__, __FILE__);
	}


	/**
	 * @desc Returns an ordered tree with all categories informations
	 * @return array[] an ordered tree with all categories informations
	 */
	function get_cats_tree()
	{
		global $LANG, $CAT_FORUM;
		Cache::load('forum');
	  
		if (!(isset($CAT_FORUM) && is_array($CAT_FORUM)))
		{
			$CAT_ARTICLES = array();
		}

		$ordered_cats = array();
		foreach ($CAT_FORUM as $id => $cat)
		{   // Sort by id_left
			$cat['id'] = $id;
			$ordered_cats[numeric($cat['id_left'])] = array('this' => $cat, 'children' => array());
		}
	  
		$level = 0;
		$cats_tree = array(array('this' => array('id' => 0, 'name' => $LANG['root']), 'children' => array()));
		$parent =& $cats_tree[0]['children'];
		$nb_cats = count($CAT_FORUM);
		foreach ($ordered_cats as $cat)
		{
			if (($cat['this']['level'] == $level + 1) && count($parent) > 0)
			{   // The new parent is the previous cat
				$parent =& $parent[count($parent) - 1]['children'];
			}
			elseif ($cat['this']['level'] < $level)
			{   // Find the new parent (an ancestor)
				$j = 0;
				$parent =& $cats_tree[0]['children'];
				while ($j < $cat['this']['level'])
				{
					$parent =& $parent[count($parent) - 1]['children'];
					$j++;
				}
			}

			// Add the current cat at the good level
			$parent[] = $cat;
			$level = $cat['this']['level'];
		}
		return $cats_tree[0];
	}

	## Private Method ##
	//Met à jour chaque catégories quelque soit le niveau de profondeur de la catégorie source. Cas le plus favorable et courant seulement 3 requêtes.
	function update_last_topic_id($idcat)
	{
		global $Sql, $CAT_FORUM;

		$clause = "idcat = '" . $idcat . "'";
		if (($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$idcat]['id_left']) > 1) //Sous forums présents.
		{
			//Sous forums du forum à mettre à jour.
			$list_cats = '';
			$result = $Sql->query_while("SELECT id
			FROM " . PREFIX . "forum_cats
			WHERE id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'
			ORDER BY id_left", __LINE__, __FILE__);
				
			while ($row = $Sql->fetch_assoc($result))
			$list_cats .= $row['id'] . ', ';
				
			$Sql->query_close($result);
			$clause = "idcat IN (" . trim($list_cats, ', ') . ")";
		}

		//Récupération du timestamp du dernier message de la catégorie.
		$last_timestamp = $Sql->query("SELECT MAX(last_timestamp) FROM " . PREFIX . "forum_topics WHERE " . $clause, __LINE__, __FILE__);
		$last_topic_id = $Sql->query("SELECT id FROM " . PREFIX . "forum_topics WHERE last_timestamp = '" . $last_timestamp . "'", __LINE__, __FILE__);
		if (!empty($last_topic_id))
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET last_topic_id = '" . $last_topic_id . "' WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
			
		if ($CAT_FORUM[$idcat]['level'] > 1) //Appel recursif si sous-forum.
		{
			//Recherche de l'id du forum parent.
			$idcat_parent = $Sql->query("SELECT id
			FROM " . PREFIX . "forum_cats
			WHERE id_left < '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right > '" . $CAT_FORUM[$idcat]['id_right'] . "' AND level = '" .  ($CAT_FORUM[$idcat]['level'] - 1) . "'", __LINE__, __FILE__);

			$this->Update_last_topic_id($idcat_parent); //Appel recursif.
		}
	}

	//Emulation de la fonction PHP 5 array_diff_key
	function array_diff_key_emulate()
	{
		$args = func_get_args();
		if (count($args) < 2) {
			user_error('Wrong parameter count for array_diff_key()', E_USER_WARNING);
			return;
		}

		// Check arrays
		$array_count = count($args);
		for ($i = 0; $i !== $array_count; $i++) {
			if (!is_array($args[$i])) {
				user_error('array_diff_key() Argument #' .
				($i + 1) . ' is not an array', E_USER_WARNING);
				return;
			}
		}

		$result = $args[0];
		foreach ($args[0] as $key1 => $value1) {
			for ($i = 1; $i !== $array_count; $i++) {
				foreach ($args[$i] as $key2 => $value2) {
					if ((string) $key1 === (string) $key2) {
						unset($result[$key2]);
						break 2;
					}
				}
			}
		}
		return $result;
	}
}

?>