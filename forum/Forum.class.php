<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 11
 * @since       PHPBoost 2.0 - 2007 12 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor ph-7 <me@ph7.me>
*/

define('NO_HISTORY', false);
define('FORUM_EMAIL_TRACKING', 1);
define('FORUM_PM_TRACKING', 2);

class Forum
{
	//Ajout d'un message.
	function Add_msg($idtopic, $idcat, $contents, $title, $last_page, $last_page_rewrite, $new_topic = false)
	{
		global $LANG;

		##### Insertion message #####
		$last_timestamp = time();
		$result = PersistenceContext::get_querier()->insert(PREFIX . 'forum_msg', array('idtopic' => $idtopic, 'user_id' => AppContext::get_current_user()->get_id(), 'contents' => FormatingHelper::strparse($contents),
			'timestamp' => $last_timestamp, 'timestamp_edit' => 0, 'user_id_edit' => 0, 'user_ip' => AppContext::get_request()->get_ip_address()));
		$last_msg_id = $result->get_last_inserted_id();

		//Topic
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_topics SET " . ($new_topic ? '' : 'nbr_msg = nbr_msg + 1, ') . "last_user_id = '" . AppContext::get_current_user()->get_id() . "', last_msg_id = '" . $last_msg_id . "', last_timestamp = '" . $last_timestamp . "' WHERE id = '" . $idtopic . "'");

		//On met à jour le last_topic_id dans la catégorie dans le lequel le message a été posté et ses parents
		$categories = array_keys(CategoriesService::get_categories_manager('forum', 'idcat')->get_parents($idcat, true));
		PersistenceContext::get_querier()->update(ForumSetup::$forum_cats_table, array('last_topic_id' => $idtopic), 'WHERE id IN :categories_id', array('categories_id' => $categories));

		//Mise à jour du nombre de messages du membre.
		PersistenceContext::get_querier()->inject("UPDATE " . DB_TABLE_MEMBER . " SET posted_msg = posted_msg + 1 WHERE user_id = '" . AppContext::get_current_user()->get_id() . "'");

		//On marque le topic comme lu.
		mark_topic_as_read($idtopic, $last_msg_id, $last_timestamp);

		##### Gestion suivi du sujet mp/mail #####
		if (!$new_topic)
		{
			//Message précédent ce nouveau message.
			$previous_msg_id = 0;
			try {
				$previous_msg_id = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_msg", 'MAX(id)', 'WHERE idtopic = :idtopic AND id < :id', array('idtopic' => $idtopic, 'id' => $last_msg_id));
			} catch (RowNotFoundException $e) {}

			$title_subject = TextHelper::html_entity_decode($title);
			$title_subject_pm = $title_subject;
			if (AppContext::get_current_user()->get_id() > 0)
			{
				$pseudo = '';
				try {
					$pseudo = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'display_name', 'WHERE user_id = :id', array('id' => AppContext::get_current_user()->get_id()));
				} catch (RowNotFoundException $e) {}

				$pseudo_pm = '<a href="'. UserUrlBuilder::profile(AppContext::get_current_user()->get_id())->rel() .'">' . $pseudo . '</a>';
			}
			else
			{
				$pseudo = $LANG['guest'];
				$pseudo_pm = $LANG['guest'];
			}
			$next_msg_link = '/forum/topic' . url('.php?id=' . $idtopic . $last_page, '-' . $idtopic . $last_page_rewrite . '.php') . ($previous_msg_id ? '#m' . $previous_msg_id : '');

			$content_manager = AppContext::get_content_formatting_service()->get_default_factory();
			$parser = $content_manager->get_parser();
			$parser->set_content($contents);
			$parser->parse();

			$preview_contents = $parser->get_content();

			//Récupération des membres suivant le sujet.
			$max_time = time() - SessionsConfig::load()->get_active_session_duration();
			$result = PersistenceContext::get_querier()->select("SELECT m.user_id, m.display_name, m.email, tr.pm, tr.mail, v.last_view_id
			FROM " . PREFIX . "forum_track tr
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = tr.user_id
			LEFT JOIN " . PREFIX . "forum_view v ON v.idtopic = :idtopic AND v.user_id = tr.user_id
			WHERE tr.idtopic = :idtopic AND v.last_view_id IS NOT NULL AND m.user_id != :user_id", array(
				'idtopic' => $idtopic,
				'user_id' => AppContext::get_current_user()->get_id()
			));
			while ($row = $result->fetch())
			{
				//Envoi un Mail à ceux dont le last_view_id est le message précedent.
				if ($row['last_view_id'] == $previous_msg_id && $row['mail'] == '1')
				{
					$mail_contents = FormatingHelper::second_parse($preview_contents);
					AppContext::get_mail_service()->send_from_properties(
						$row['email'],
						$LANG['forum_mail_title_new_post'],
						nl2br(sprintf($LANG['forum_mail_new_post'], $row['display_name'], $title_subject, AppContext::get_current_user()->get_display_name(), $mail_contents, '<a href="' . HOST . DIR . $next_msg_link . '">' . $next_msg_link . '</a>', '<a href="' . HOST . DIR . '/forum/action.php?ut=' . $idtopic . '&trt=1">' . HOST . DIR . '/forum/action.php?ut=' . $idtopic . '&trt=1</a>', 1))
					);
				}

				//Envoi un MP à ceux dont le last_view_id est le message précedent.
				if ($row['last_view_id'] == $previous_msg_id && $row['pm'] == '1')
				{
					$content = sprintf($LANG['forum_mail_new_post'], $row['display_name'], $title_subject_pm, AppContext::get_current_user()->get_display_name(), $preview_contents, '<a href="' . $next_msg_link . '">' . $next_msg_link . '</a>', '<a href="/forum/action.php?ut=' . $idtopic . '&trt=2">/forum/action.php?ut=' . $idtopic . '&trt=2</a>');

					PrivateMsg::start_conversation(
						$row['user_id'],
						$LANG['forum_mail_title_new_post'],
						nl2br($content),
						'-1',
						PrivateMsg::SYSTEM_PM
					);
				}
			}
			$result->dispose();
		}

		forum_generate_feeds(); //Regénération du flux rss.
		ForumCategoriesCache::invalidate();

		return $last_msg_id;
	}

	//Ajout d'un sujet.
	function Add_topic($idcat, $title, $subtitle, $contents, $type)
	{
		$result = PersistenceContext::get_querier()->insert(PREFIX . "forum_topics", array('idcat' => $idcat, 'title' => $title, 'subtitle' => $subtitle, 'user_id' => AppContext::get_current_user()->get_id(), 'nbr_msg' => 1, 'nbr_views' => 0, 'last_user_id' => AppContext::get_current_user()->get_id(), 'last_msg_id' => 0, 'last_timestamp' => time(), 'first_msg_id' => 0, 'type' => $type, 'status' => 1, 'aprob' => 0, 'display_msg' => 0));
		$last_topic_id = $result->get_last_inserted_id(); //Dernier topic inseré

		$last_msg_id = $this->Add_msg($last_topic_id, $idcat, $contents, $title, 0, 0, true); //Insertion du message.
		PersistenceContext::get_querier()->update(PREFIX . 'forum_topics', array('first_msg_id' => $last_msg_id), 'WHERE id=:id', array('id' => $last_topic_id));

		forum_generate_feeds(); //Regénération des flux flux
		ForumCategoriesCache::invalidate();

		return array($last_topic_id, $last_msg_id);
	}

	//Edition d'un message.
	function Update_msg($idtopic, $idmsg, $contents, $user_id_msg, $history = true)
	{
		$config = ForumConfig::load();

		//Marqueur d'édition du message?
		$edit_mark = (!ForumAuthorizationsService::check_authorizations()->hide_edition_mark()) ? ", timestamp_edit = '" . time() . "', user_id_edit = '" . AppContext::get_current_user()->get_id() . "'" : '';
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_msg SET contents = '" . FormatingHelper::strparse($contents) . "'" . $edit_mark . " WHERE id = '" . $idmsg . "'");

		$nbr_msg_before = PersistenceContext::get_querier()->count(PREFIX . "forum_msg", 'WHERE idtopic = :idtopic AND id < :id', array('idtopic' => $idtopic, 'id' => $idmsg));

		//Calcul de la page sur laquelle se situe le message.
		$msg_page = ceil( ($nbr_msg_before + 1) / $config->get_number_messages_per_page() );
		$msg_page_rewrite = ($msg_page > 1) ? '-' . $msg_page : '';
		$msg_page = ($msg_page > 1) ? '&pt=' . $msg_page : '';

		//Insertion de l'action dans l'historique.
		if (AppContext::get_current_user()->get_id() != $user_id_msg && $history)
		forum_history_collector(H_EDIT_MSG, $user_id_msg, 'topic' . url('.php?id=' . $idtopic . $msg_page, '-' . $idtopic .  $msg_page_rewrite . '.php', '&') . '#m' . $idmsg);

		return $nbr_msg_before;
	}

	//Edition d'un sujet.
	function Update_topic($idtopic, $idmsg, $title, $subtitle, $contents, $type, $user_id_msg)
	{
		//Mise à jour du sujet.
		PersistenceContext::get_querier()->update(PREFIX . 'forum_topics', array('title' => $title, 'subtitle' => $subtitle, 'type' => $type), 'WHERE id=:id', array('id' => $idtopic));
		//Mise à jour du contenu du premier message du sujet.
		$this->Update_msg($idtopic, $idmsg, $contents, $user_id_msg, NO_HISTORY);

		//Insertion de l'action dans l'historique.
		if (AppContext::get_current_user()->get_id() != $user_id_msg)
		forum_history_collector(H_EDIT_TOPIC, $user_id_msg, 'topic' . url('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}

	//Supression d'un message.
	function Del_msg($idmsg, $idtopic, $idcat, $first_msg_id, $last_msg_id, $last_timestamp, $msg_user_id)
	{
		$config = ForumConfig::load();

		if ($first_msg_id != $idmsg) //Suppression d'un message.
		{
			//On compte le nombre de messages du topic avant l'id supprimé.
			$nbr_msg = PersistenceContext::get_querier()->count(PREFIX . "forum_msg", 'WHERE idtopic = :idtopic AND id < :id', array('idtopic' => $idtopic, 'id' => $idmsg));
			//On supprime le message demandé.
			PersistenceContext::get_querier()->delete(PREFIX . 'forum_msg', 'WHERE id=:id', array('id' => $idmsg));
			//On met à jour la table forum_topics.
			$actual_nbr_msg = 0;
			try {
				$actual_nbr_msg = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_topics", 'nbr_msg', 'WHERE id=:id', array('id' => $idtopic));
			} catch (RowNotFoundException $e) {}

			if (!empty($actual_nbr_msg))
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_topics SET nbr_msg = nbr_msg - 1 WHERE id = '" . $idtopic . "'");
			//Récupération du message précédent celui supprimé afin de rediriger vers la bonne ancre.
			$previous_msg_id = 0;
			try {
				$previous_msg_id = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_msg" , 'id', 'WHERE idtopic = :idtopic AND id < :id ORDER BY timestamp DESC LIMIT 1', array('idtopic' => $idtopic, 'id' => $idmsg));
			} catch (RowNotFoundException $e) {}

			if ($last_msg_id == $idmsg) //On met à jour le dernier message posté dans la liste des topics.
			{
				//On cherche les infos à propos de l'avant dernier message afin de mettre la table forum_topics à jour.
				try {
					$id_before_last = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_msg', array('user_id', 'timestamp'), 'WHERE id=:id', array('id' => $previous_msg_id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_element();
					DispatchManager::redirect($error_controller);
				}

				PersistenceContext::get_querier()->update(PREFIX . 'forum_topics', array('last_user_id' => $id_before_last['user_id'], 'last_msg_id' => $previous_msg_id, 'last_timestamp' => $id_before_last['timestamp']), 'WHERE id=:id', array('id' => $idtopic));

				//On met maintenant a jour le last_topic_id dans les catégories.
				$this->Update_last_topic_id($idcat);
			}

			//On retire un msg au membre.
			$user_posted_msg_number = 0;
			try {
				$user_posted_msg_number = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'posted_msg', 'WHERE user_id=:id', array('id' => $msg_user_id));
			} catch (RowNotFoundException $e) {}

			if (!empty($user_posted_msg_number))
				PersistenceContext::get_querier()->inject("UPDATE " . DB_TABLE_MEMBER . " SET posted_msg = posted_msg - 1 WHERE user_id = '" . $msg_user_id . "'");

			//Mise à jour du dernier message lu par les membres.
			PersistenceContext::get_querier()->update(PREFIX . 'forum_view', array('last_view_id' => $previous_msg_id), 'WHERE last_view_id=:id', array('id' => $idmsg));
			//On marque le topic comme lu, si c'est le dernier du message du topic.
			if ($last_msg_id == $idmsg)
			mark_topic_as_read($idtopic, $previous_msg_id, $last_timestamp);

			//Insertion de l'action dans l'historique.
			if ($msg_user_id != AppContext::get_current_user()->get_id())
			{
				//Calcul de la page sur laquelle se situe le message.
				$msg_page = ceil($nbr_msg / $config->get_number_messages_per_page());
				$msg_page_rewrite = ($msg_page > 1) ? '-' . $msg_page : '';
				$msg_page = ($msg_page > 1) ? '&pt=' . $msg_page : '';
				forum_history_collector(H_DELETE_MSG, $msg_user_id, 'topic' . url('.php?id=' . $idtopic . $msg_page, '-' . $idtopic .  $msg_page_rewrite . '.php', '&') . '#m' . $previous_msg_id);
			}
			forum_generate_feeds(); //Regénération des flux flux
			ForumCategoriesCache::invalidate();

			return array($nbr_msg, $previous_msg_id);
		}

		return array(false, false);
	}

	//Suppresion d'un sujet.
	function Del_topic($idtopic, $generate_rss = true)
	{
		try {
			$topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array('idcat', 'user_id'), 'WHERE id=:id', array('id' => $idtopic));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		$topic['user_id'] = (int)$topic['user_id'];

		//On ne supprime pas de msg aux membres ayant postés dans le topic => trop de requêtes.
		//On compte le nombre de messages du topic.
		$nbr_msg = PersistenceContext::get_querier()->count(PREFIX . "forum_msg", 'WHERE idtopic = :idtopic', array('idtopic' => $idtopic));
		$nbr_msg = !empty($nbr_msg) ? NumberHelper::numeric($nbr_msg) : 1;

		//On rippe le topic ainsi que les messages du topic.
		PersistenceContext::get_querier()->delete(PREFIX . 'forum_msg', 'WHERE idtopic=:id', array('id' => $idtopic));
		PersistenceContext::get_querier()->delete(PREFIX . 'forum_topics', 'WHERE id=:id', array('id' => $idtopic));
		PersistenceContext::get_querier()->delete(PREFIX . 'forum_poll', 'WHERE idtopic=:id', array('id' => $idtopic));

		//On met maintenant a jour le last_topic_id dans les catégories.
		$this->Update_last_topic_id($topic['idcat']);

		//Topic supprimé, on supprime les marqueurs de messages lus pour ce topic.
		PersistenceContext::get_querier()->delete(PREFIX . 'forum_view', 'WHERE idtopic=:id', array('id' => $idtopic));

		//On supprime l'alerte.
		$this->Del_alert_topic($idtopic);

		//Insertion de l'action dans l'historique.
		if ($topic['user_id'] != AppContext::get_current_user()->get_id())
			forum_history_collector(H_DELETE_TOPIC, $topic['user_id'], 'forum' . url('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '.php', '&'));

		if ($generate_rss)
			forum_generate_feeds(); //Regénération des flux flux

		ForumCategoriesCache::invalidate();
	}

	//Suivi d'un sujet.
	function Track_topic($idtopic, $tracking_type = 0)
	{
		$config = ForumConfig::load();

		list($mail, $pm, $track) = array(0, 0, 0);
		if ($tracking_type == 0) //Suivi par email.
			$track = '1';
		elseif ($tracking_type == 1) //Suivi par email.
			$mail = '1';
		elseif ($tracking_type == 2) //Suivi par email.
			$pm = '1';

		$exist = PersistenceContext::get_querier()->count(PREFIX . 'forum_track', 'WHERE user_id = :user_id AND idtopic = :idtopic', array('user_id' => AppContext::get_current_user()->get_id(), 'idtopic' => $idtopic));
		if ($exist == 0)
			PersistenceContext::get_querier()->insert(PREFIX . "forum_track", array('idtopic' => $idtopic, 'user_id' => AppContext::get_current_user()->get_id(), 'track' => $track, 'pm' => $pm, 'mail' => $mail));
		elseif ($tracking_type == 0)
			PersistenceContext::get_querier()->update(PREFIX . "forum_track", array('track' => 1), 'WHERE idtopic = :idtopic AND user_id = :user_id', array('idtopic' => $idtopic , 'user_id' => AppContext::get_current_user()->get_id()));
		elseif ($tracking_type == 1)
			PersistenceContext::get_querier()->update(PREFIX . "forum_track", array('mail' => 1), 'WHERE idtopic = :idtopic AND user_id = :user_id', array('idtopic' => $idtopic , 'user_id' => AppContext::get_current_user()->get_id()));
		elseif ($tracking_type == 2)
			PersistenceContext::get_querier()->update(PREFIX . "forum_track", array('pm' => 1), 'WHERE idtopic = :idtopic AND user_id = :user_id', array('idtopic' => $idtopic , 'user_id' => AppContext::get_current_user()->get_id()));

		//Limite de sujets suivis?
		if (!ForumAuthorizationsService::check_authorizations()->unlimited_topics_tracking())
		{
			$tracked_topics_number = 0;
			//Récupère l'id du topic le plus vieux autorisé par la limite de sujet suivis.
			try {
				$tracked_topics_number = PersistenceContext::get_querier()->select_single_row_query("SELECT COUNT(*) as number
				FROM " . PREFIX . "forum_track
				WHERE user_id = :user_id
				ORDER BY id DESC
				LIMIT " . $config->get_max_topic_number_in_favorite(), array(
					'user_id' => AppContext::get_current_user()->get_id()
				));
			} catch (RowNotFoundException $e) {}

			//Suppression des sujets suivis dépassant le nbr maximum autorisé.
			if (!empty($tracked_topics_number))
				PersistenceContext::get_querier()->delete(PREFIX . 'forum_track', 'WHERE user_id=:id  AND id < :number', array('id' => AppContext::get_current_user()->get_id(), 'number' => $tracked_topics_number['number']));
		}
	}

	//Retrait du suivi d'un sujet.
	function Untrack_topic($idtopic, $tracking_type = 0)
	{
		if ($tracking_type == 1) //Par mail
		{
			try {
				$info = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_track', array("pm", "track"), 'WHERE user_id=:user_id AND idtopic=:idtopic', array('user_id' => AppContext::get_current_user()->get_id(), 'idtopic' => $idtopic));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_element();
				DispatchManager::redirect($error_controller);
			}

			if ($info['track'] == 0 && $info['pm'] == 0)
				PersistenceContext::get_querier()->delete(PREFIX . 'forum_track', 'WHERE idtopic=:id AND user_id =:user_id', array('id' => $idtopic, 'user_id' => AppContext::get_current_user()->get_id()));
			else
				PersistenceContext::get_querier()->update(PREFIX . "forum_track", array('mail' => 0), 'WHERE idtopic = :idtopic AND user_id = :user_id', array('idtopic' => $idtopic , 'user_id' => AppContext::get_current_user()->get_id()));
		}
		elseif ($tracking_type == 2) //Par mp
		{
			try {
				$info = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_track', array("mail", "track"), 'WHERE user_id=:user_id AND idtopic=:idtopic', array('user_id' => AppContext::get_current_user()->get_id(), 'idtopic' => $idtopic));
				} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_element();
				DispatchManager::redirect($error_controller);
			}

			if ($info['mail'] == 0 && $info['track'] == 0)
				PersistenceContext::get_querier()->delete(PREFIX . 'forum_track', 'WHERE idtopic=:id AND user_id =:user_id', array('id' => $idtopic, 'user_id' => AppContext::get_current_user()->get_id()));
			else
				PersistenceContext::get_querier()->update(PREFIX . "forum_track", array('pm' => 0), 'WHERE idtopic = :idtopic AND user_id = :user_id', array('idtopic' => $idtopic , 'user_id' => AppContext::get_current_user()->get_id()));
		}
		else //Suivi
		{
			try {
				$info = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_track', array("mail", "pm"), 'WHERE user_id=:user_id AND idtopic=:idtopic', array('user_id' => AppContext::get_current_user()->get_id(), 'idtopic' => $idtopic));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_element();
				DispatchManager::redirect($error_controller);
			}

			if ($info['mail'] == 0 && $info['pm'] == 0)
				PersistenceContext::get_querier()->delete(PREFIX . 'forum_track', 'WHERE idtopic=:id AND user_id =:user_id', array('id' => $idtopic, 'user_id' => AppContext::get_current_user()->get_id()));
			else
				PersistenceContext::get_querier()->update(PREFIX . "forum_track", array('track' => 0), 'WHERE idtopic = :idtopic AND user_id = :user_id', array('idtopic' => $idtopic , 'user_id' => AppContext::get_current_user()->get_id()));
		}
	}

	//Verrouillage d'un sujet.
	function Lock_topic($idtopic)
	{
		PersistenceContext::get_querier()->update(PREFIX . "forum_topics", array('status' => 0), 'WHERE id = :id', array('id' => $idtopic));

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_LOCK_TOPIC, 0, 'topic' . url('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}

	//Déverrouillage d'un sujet.
	function Unlock_topic($idtopic)
	{
		PersistenceContext::get_querier()->update(PREFIX . "forum_topics", array('status' => 1), 'WHERE id = :id', array('id' => $idtopic));

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_UNLOCK_TOPIC, 0, 'topic' . url('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));
	}

	//Déplacement d'un sujet.
	function Move_topic($idtopic, $idcat, $idcat_dest)
	{
		//On va chercher le nombre de messages dans la table topics
		try {
			$topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array("user_id", "nbr_msg"), 'WHERE id=:id', array('id' => $idtopic));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		$topic['nbr_msg'] = !empty($topic['nbr_msg']) ? NumberHelper::numeric($topic['nbr_msg']) : 1;

		//On déplace le topic dans la nouvelle catégorie
		PersistenceContext::get_querier()->update(PREFIX . "forum_topics", array('idcat' => $idcat_dest), 'WHERE id = :id', array('id' => $idtopic));

		//On met maintenant a jour le last_topic_id dans les catégories.
		$this->Update_last_topic_id($idcat);

		//On met maintenant a jour le last_topic_id dans les catégories.
		$this->Update_last_topic_id($idcat_dest);

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_MOVE_TOPIC, $topic['user_id'], 'topic' . url('.php?id=' . $idtopic, '-' . $idtopic . '.php', '&'));

		ForumCategoriesCache::invalidate();
	}

	//Déplacement d'un sujet
	function Cut_topic($id_msg_cut, $idtopic, $idcat, $idcat_dest, $title, $subtitle, $contents, $type, $msg_user_id, $last_user_id, $last_msg_id)
	{
		$now = new Date();
		//Calcul du nombre de messages déplacés.
		$nbr_msg = PersistenceContext::get_querier()->count(PREFIX . "forum_msg", 'WHERE idtopic = :idtopic AND id >= :id', array('idtopic' => $idtopic, 'id' => $id_msg_cut));
		$nbr_msg = !empty($nbr_msg) ? NumberHelper::numeric($nbr_msg) : 1;

		//Insertion nouveau topic.
		$result = PersistenceContext::get_querier()->insert(PREFIX . "forum_topics", array('idcat' => $idcat_dest, 'title' => $title, 'subtitle' => $subtitle, 'user_id' => $msg_user_id, 'nbr_msg' => $nbr_msg, 'nbr_views' => 0, 'last_user_id' => $last_user_id, 'last_msg_id' => $last_msg_id, 'last_timestamp' => $now->get_timestamp(), 'first_msg_id' => $id_msg_cut, 'type' => $type, 'status' => 1, 'aprob' => 0));
		$last_topic_id = $result->get_last_inserted_id(); //Dernier topic inseré

		//Mise à jour du message.
		PersistenceContext::get_querier()->update(PREFIX . "forum_msg", array('contents' => $contents), 'WHERE id = :id', array('id' => $id_msg_cut));

		//Déplacement des messages.
		$messages_to_move = array();
		$result = PersistenceContext::get_querier()->select_rows(PREFIX . 'forum_msg', array('id'), 'WHERE idtopic = :idtopic AND id >= :id', array('idtopic' => $idtopic, 'id' => $id_msg_cut));
		while ($row = $result->fetch())
		{
			$messages_to_move[] = $row['id'];
		}
		$result->dispose();

		if (!empty($messages_to_move))
			PersistenceContext::get_querier()->update(PREFIX . "forum_msg", array('idtopic' => $last_topic_id), 'WHERE id IN :ids_list', array('ids_list' => $messages_to_move));

		//Mise à jour de l'ancien topic
		try {
			$previous_topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_msg', array('id', 'user_id', 'timestamp'), 'WHERE id<:id AND idtopic =:idtopic ORDER BY timestamp DESC LIMIT 0, 1', array('id' => $id_msg_cut, 'idtopic' => $idtopic));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_topics SET last_user_id = '" . $previous_topic['user_id'] . "', last_msg_id = '" . $previous_topic['id'] . "', nbr_msg = nbr_msg - " . $nbr_msg . ", last_timestamp = '" . $previous_topic['timestamp'] . "'  WHERE id = '" . $idtopic . "'");

		//Mise à jour de l'ancienne catégorie, si elle est différente.
		if ($idcat != $idcat_dest)
		{
			//On met maintenant a jour le last_topic_id dans les catégories.
			$this->Update_last_topic_id($idcat_dest);
		}

		//On met maintenant a jour le last_topic_id dans les catégories.
		$this->Update_last_topic_id($idcat);

		//On marque comme lu le message avant le message scindé qui est le dernier message de l'ancienne catégorie pour tous les utilisateurs.
		PersistenceContext::get_querier()->update(PREFIX . "forum_view", array('last_view_id' => $previous_topic['id'], 'timestamp' => time()), 'WHERE idtopic = :idtopic', array('idtopic' => $idtopic));

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_CUT_TOPIC, 0, 'topic' . url('.php?id=' . $last_topic_id, '-' . $last_topic_id . '.php', '&'));

		ForumCategoriesCache::invalidate();

		return $last_topic_id;
	}

	//Ajoute une alerte sur un sujet.
	function Alert_topic($alert_post, $alert_title, $alert_contents)
	{
		global $LANG;

		try {
			$topic_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array("idcat", "title"), 'WHERE id=:id', array('id' => $alert_post));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		$result = PersistenceContext::get_querier()->insert(PREFIX . "forum_alerts", array('idcat' => $topic_infos['idcat'], 'idtopic' => $alert_post, 'title' => $alert_title, 'contents' => $alert_contents, 'user_id' => AppContext::get_current_user()->get_id(), 'status' => 0, 'idmodo' => 0, 'timestamp' => time()));

		$alert_id = $result->get_last_inserted_id();

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
		$contribution->set_poster_id(AppContext::get_current_user()->get_id());
		//The module
		$contribution->set_module('forum');
		//It's an alert, we will be able to manage other kinds of contributions in the module if we choose to use a type.
		$contribution->set_type('alert');

		//Assignation des autorisations d'écriture / Writing authorization assignation
		$contribution->set_auth(
		//On déplace le bit sur l'autorisation obtenue pour le mettre sur celui sur lequel travaille les contributions, à savoir Contribution::CONTRIBUTION_AUTH_BIT
		//We shift the authorization bit to the one with which the contribution class works, Contribution::CONTRIBUTION_AUTH_BIT
			Authorizations::capture_and_shift_bit_auth(
				CategoriesService::get_categories_manager('forum', 'idcat')->get_heritated_authorizations($topic_infos['idcat'], Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
				Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
			)
		);

		//Sending the contribution to the kernel. It will place it in the contribution panel to be approved
		ContributionService::save_contribution($contribution);
	}

	//Passe en résolu une alerte sur un sujet.
	function Solve_alert_topic($id_alert)
	{
		PersistenceContext::get_querier()->update(PREFIX . "forum_alerts", array('status' => 1, 'idmodo' => AppContext::get_current_user()->get_id()), 'WHERE id = :id', array('id' => $id_alert));

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_SOLVE_ALERT, 0, 'moderation_forum.php?action=alert&id=' . $id_alert, '', '&');

		//Si la contribution associée n'est pas réglée, on la règle
		$corresponding_contributions = ContributionService::find_by_criteria('forum', $id_alert, 'alert');
		if (count($corresponding_contributions) > 0)
		{
			foreach ($corresponding_contributions as $contribution)
			{
				$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
				ContributionService::save_contribution($contribution);
			}
		}
	}

	//Passe en attente une alerte sur un sujet.
	function Wait_alert_topic($id_alert)
	{
		PersistenceContext::get_querier()->update(PREFIX . 'forum_alerts', array('status' => 0, 'idmodo' => 0), 'WHERE id=:id', array('id' => $id_alert));

		//Insertion de l'action dans l'historique.
		forum_history_collector(H_WAIT_ALERT, 0, 'moderation_forum.php?action=alert&id=' . $id_alert);
	}

	//Supprime une alerte sur un sujet.
	function Del_alert_topic($id_alert)
	{
		PersistenceContext::get_querier()->delete(PREFIX . 'forum_alerts', 'WHERE id=:id', array('id' => $id_alert));

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
		PersistenceContext::get_querier()->insert(PREFIX . "forum_poll", array('idtopic' => $idtopic, 'question' => $question, 'answers' => implode('|', $answers), 'voter_id' => 0, 'votes' => trim(str_repeat('0|', $nbr_votes)), 'type' => NumberHelper::numeric($type)));
	}

	//Edition d'un sondage.
	function Update_poll($idtopic, $question, $answers, $type)
	{
		//Vérification => vérifie si il n'y a pas de nouvelle réponses à ajouter.
		$previous_votes = array();
		try {
			$previous_votes = explode('|', PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_poll", 'votes', 'WHERE idtopic = :idtopic', array('idtopic' => $idtopic)));
		} catch (RowNotFoundException $e) {}

		$votes = array();
		foreach ($answers as $key => $answer_value) //Récupération des votes précédents.
		$votes[$key] = isset($previous_votes[$key]) ? $previous_votes[$key] : 0;

		PersistenceContext::get_querier()->update(PREFIX . "forum_poll", array('question' => $question, 'answers' => implode('|', $answers), 'votes' => implode('|', $votes), 'type' => $type), 'WHERE idtopic = :idtopic', array('idtopic' => $idtopic));
	}

	//Suppression d'un sondage.
	function Del_poll($idtopic)
	{
		PersistenceContext::get_querier()->delete(PREFIX . 'forum_poll', 'WHERE idtopic=:id', array('id' => $idtopic));
	}

	## Private Method ##
	//Met à jour chaque catégories quelque soit le niveau de profondeur de la catégorie source. Cas le plus favorable et courant seulement 3 requêtes.
	function Update_last_topic_id($idcat)
	{
		$category = CategoriesService::get_categories_manager('forum', 'idcat')->get_categories_cache()->get_category($idcat);
		$children = CategoriesService::get_categories_manager('forum', 'idcat')->get_categories_cache()->get_children($idcat);
		$cat_ids = implode(', ', array_keys($children));
		if (empty($cat_ids))
			$cat_ids = $idcat;

		//Récupération du timestamp du dernier message de la catégorie.
		$last_timestamp = 0;
		try {
			$last_timestamp = PersistenceContext::get_querier()->get_column_value(ForumSetup::$forum_topics_table, 'MAX(last_timestamp)', 'WHERE idcat IN (' . $cat_ids . ')');
		} catch (RowNotFoundException $e) {}

		$last_topic_id = 0;
		try {
			$last_topic_id = PersistenceContext::get_querier()->get_column_value(ForumSetup::$forum_topics_table, 'id', 'WHERE last_timestamp = :timestamp', array('timestamp' => $last_timestamp));
		} catch (RowNotFoundException $e) {}

		PersistenceContext::get_querier()->update(ForumSetup::$forum_cats_table, array('last_topic_id' => (int)$last_topic_id ), 'WHERE id = :id', array('id' => $idcat));

		if ($category->get_id_parent() != Category::ROOT_CATEGORY) //Appel recursif si sous-forum.
		{
			$this->Update_last_topic_id($category->get_id_parent()); //Appel recursif.
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
