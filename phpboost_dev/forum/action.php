<?php
/*##################################################
 *                                action.php
 *                            -------------------
 *   begin                : August 14, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
$Bread_crumb->add($CONFIG_FORUM['forum_name'], 'index.php' . SID);
require_once('../kernel/header_no_display.php');

//Variable GET.
$idt_get = retrieve(GET, 'id', 0);
$idm_get = retrieve(GET, 'idm', 0);
$del = retrieve(GET, 'del', false);
$alert = retrieve(GET, 'a', '');
$read = retrieve(GET, 'read', false);
$msg_d = retrieve(GET, 'msg_d', false);
$lock_get = retrieve(GET, 'lock', '');
$page_get = retrieve(GET, 'p', 1);

$track = retrieve(GET, 't', '');
$untrack = retrieve(GET, 'ut', '');
$track_pm = retrieve(GET, 'tp', '');
$untrack_pm = retrieve(GET, 'utp', '');
$track_mail = retrieve(GET, 'tm', '');
$untrack_mail = retrieve(GET, 'utm', '');

//Variable $_POST
$poll = retrieve(POST, 'valid_forum_poll', false); //Sondage forum.
$massive_action_type = retrieve(POST, 'action_type', ''); //Opération de masse.

$Forumfct = new Forum();

if (!empty($idm_get) && $del) //Suppression d'un message/topic.
{
	$Session->csrf_get_protect(); //Protection csrf

	//Info sur le message.
	$msg = $Sql->query_array(PREFIX . 'forum_msg', 'user_id', 'idtopic', "WHERE id = '" . $idm_get . "'", __LINE__, __FILE__);

	//On va chercher les infos sur le topic
	$topic = $Sql->query_array(PREFIX . 'forum_topics', 'user_id', 'idcat', 'first_msg_id', 'last_msg_id', 'last_timestamp', "WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);

	//Si on veut supprimer le premier message, alors son rippe le topic entier (admin et modo seulement).
	if (!empty($msg['idtopic']) && $topic['first_msg_id'] == $idm_get)
	{
		if (!empty($msg['idtopic']) && ($User->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM) || $User->get_attribute('user_id') == $topic['user_id'])) //Autorisé à supprimer?
			$Forumfct->Del_topic($msg['idtopic']); //Suppresion du topic.
		else
			$Errorh->handler('e_auth', E_USER_REDIRECT);

		AppContext::get_response()->redirect('/forum/forum' . url('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '.php', '&'));
	}
	elseif (!empty($msg['idtopic']) && $topic['first_msg_id'] != $idm_get) //Suppression d'un message.
	{
		if (!empty($topic['idcat']) && ($User->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM) || $User->get_attribute('user_id') == $msg['user_id'])) //Autorisé à supprimer?
			list($nbr_msg, $previous_msg_id) = $Forumfct->Del_msg($idm_get, $msg['idtopic'], $topic['idcat'], $topic['first_msg_id'], $topic['last_msg_id'], $topic['last_timestamp'], $msg['user_id']);
		else
			$Errorh->handler('e_auth', E_USER_REDIRECT);

		if ($nbr_msg === false && $previous_msg_id === false) //Echec de la suppression.
			$Errorh->handler('e_auth', E_USER_REDIRECT);

		//On compte le nombre de messages du topic avant l'id supprimé.
		$last_page = ceil( $nbr_msg/ $CONFIG_FORUM['pagination_msg'] );
		$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
		$last_page = ($last_page > 1) ? '&pt=' . $last_page : '';

		AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $msg['idtopic'] . $last_page, '-' . $msg['idtopic'] . $last_page_rewrite . '.php', '&') . '#m' . $previous_msg_id);
	}
	else //Non autorisé, on redirige.
		$Errorh->handler('e_auth', E_USER_REDIRECT);
}
elseif (!empty($idt_get))
{
	$Session->csrf_get_protect(); //Protection csrf

	//On va chercher les infos sur le topic
	$topic = $Sql->query_array(PREFIX . 'forum_topics', 'user_id', 'idcat', 'title', 'subtitle', 'nbr_msg', 'last_msg_id', 'first_msg_id', 'last_timestamp', 'status', "WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);

	if (!$User->check_auth($CAT_FORUM[$topic['idcat']]['auth'], READ_CAT_FORUM))
		$Errorh->handler('e_auth', E_USER_REDIRECT);
	//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
	$rewrited_cat_title = ($CONFIG['rewrite'] == 1) ? '+' . Url::encode_rewrite($CAT_FORUM[$topic['idcat']]['name']) : '';
	//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
	$rewrited_title = ($CONFIG['rewrite'] == 1) ? '+' . Url::encode_rewrite($topic['title']) : '';

	//Changement du statut (display_msg) du sujet.
	if ($msg_d)
	{
		//Vérification de l'appartenance du sujet au membres, ou modo.
		$check_mbr = $Sql->query("SELECT user_id FROM " . PREFIX . "forum_topics WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);
		if ((!empty($check_mbr) && $User->get_attribute('user_id') == $check_mbr) || $User->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM))
		{
			$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET display_msg = 1 - display_msg WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);

			AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $idt_get, '-' . $idt_get . $rewrited_title . '.php', '&'));
		}
		else
			$Errorh->handler('e_auth', E_USER_REDIRECT);
	}
	elseif ($poll && $User->get_attribute('user_id') !== -1) //Enregistrement vote du sondage
	{
		$info_poll = $Sql->query_array(PREFIX . 'forum_poll', 'voter_id', 'votes', 'type', "WHERE idtopic = '" . $idt_get . "'", __LINE__, __FILE__);
		//Si l'utilisateur n'est pas dans le champ on prend en compte le vote.
		if (!in_array($User->get_attribute('user_id'), explode('|', $info_poll['voter_id'])))
		{
			//On concatène avec les votans existants.
			$add_voter_id = "voter_id = CONCAT(voter_id, '|" . $User->get_attribute('user_id') . "'),";
			$array_votes = explode('|', $info_poll['votes']);

			if ($info_poll['type'] == 0) //Réponse simple.
			{
				$id_answer = retrieve(POST, 'forumpoll', 0);
				if (isset($array_votes[$id_answer]))
					$array_votes[$id_answer]++;
			}
			else //Réponses multiples.
			{
				//On boucle pour vérifier toutes les réponses du sondage.
				$nbr_answer = count($array_votes);
				for ($i = 0; $i < $nbr_answer; $i++)
				{
					if (retrieve(POST, 'forumpoll' . $i, false))
						$array_votes[$i]++;
				}
			}
			$Sql->query_inject("UPDATE " . PREFIX . "forum_poll SET " . $add_voter_id . " votes = '" . implode('|', $array_votes) . "' WHERE idtopic = '" . $idt_get . "'", __LINE__, __FILE__);
		}

		AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $idt_get . '&pt=' . $page_get, '-' . $idt_get . '-' . $page_get . $rewrited_title . '.php', '&'));
	}
	elseif (!empty($lock_get))
	{
		//Si l'utilisateur a le droit de déplacer le topic, ou le verrouiller.
		if ($User->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM))
		{
			if ($lock_get === 'true') //Verrouillage du topic.
			{
				//Instanciation de la class du forum.
				$Forumfct = new Forum();

				$Forumfct->Lock_topic($idt_get);

				AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $idt_get, '-' . $idt_get  . $rewrited_title . '.php', '&'));
			}
			elseif ($lock_get === 'false')  //Déverrouillage du topic.
			{
				//Instanciation de la class du forum.
				$Forumfct = new Forum();

				$Forumfct->Unlock_topic($idt_get);

				AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $idt_get, '-' . $idt_get  . $rewrited_title . '.php', '&'));
			}
		}
		else
			$Errorh->handler('e_auth', E_USER_REDIRECT);
	}
	else
		$Errorh->handler('e_auth', E_USER_REDIRECT);
}
elseif (!empty($track) && $User->check_level(MEMBER_LEVEL)) //Ajout du sujet aux sujets suivis.
{
	$Forumfct->Track_topic($track); //Ajout du sujet aux sujets suivis.

	AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $track, '-' . $track . '.php', '&') . '#go_bottom');
}
elseif (!empty($untrack) && $User->check_level(MEMBER_LEVEL)) //Retrait du sujet, aux sujets suivis.
{
	$tracking_type = retrieve(GET, 'trt', 0);
	$Forumfct->Untrack_topic($untrack, $tracking_type); //Retrait du sujet aux sujets suivis.

	AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $untrack, '-' . $untrack . '.php', '&') . '#go_bottom');
}
elseif (!empty($track_pm) && $User->check_level(MEMBER_LEVEL)) //Ajout du sujet aux sujets suivis.
{
	//Instanciation de la class du forum.
	$Forumfct = new Forum();

	$Forumfct->Track_topic($track_pm, FORUM_PM_TRACKING); //Ajout du sujet aux sujets suivis.
	AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $track_pm, '-' . $track_pm . '.php', '&') . '#go_bottom');
}
elseif (!empty($untrack_pm) && $User->check_level(MEMBER_LEVEL)) //Retrait du sujet, aux sujets suivis.
{
	//Instanciation de la class du forum.
	$Forumfct = new Forum();

	$Forumfct->Untrack_topic($untrack_pm, FORUM_PM_TRACKING); //Retrait du sujet aux sujets suivis.
	AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $untrack_pm, '-' . $untrack_pm . '.php', '&') . '#go_bottom');
}
elseif (!empty($track_mail) && $User->check_level(MEMBER_LEVEL)) //Ajout du sujet aux sujets suivis.
{
	//Instanciation de la class du forum.
	$Forumfct = new Forum();

	$Forumfct->Track_topic($track_mail, FORUM_EMAIL_TRACKING); //Ajout du sujet aux sujets suivis.
	AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $track_mail, '-' . $track_mail . '.php', '&') . '#go_bottom');
}
elseif (!empty($untrack_mail) && $User->check_level(MEMBER_LEVEL)) //Retrait du sujet, aux sujets suivis.
{
	//Instanciation de la class du forum.
	$Forumfct = new Forum();

	$Forumfct->Untrack_topic($untrack_mail, FORUM_EMAIL_TRACKING); //Retrait du sujet aux sujets suivis.
	AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $untrack_mail, '-' . $untrack_mail . '.php', '&') . '#go_bottom');
}
elseif ($read) //Marquer comme lu.
{
	if (!$User->check_level(MEMBER_LEVEL)) //Réservé aux membres.
		AppContext::get_response()->redirect('/member/error.php');

	//Calcul du temps de péremption, ou de dernière vue des messages.
	$check_last_view_forum = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND . " WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);

	//Modification du last_view_forum, si le membre est déjà dans la table
	if (!empty($check_last_view_forum))
		$Sql->query_inject("UPDATE ".LOW_PRIORITY." " . DB_TABLE_MEMBER_EXTEND . " SET last_view_forum = '" .  time(). "' WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
	else
		$Sql->query_inject("INSERT INTO " . DB_TABLE_MEMBER_EXTEND . " (user_id,last_view_forum) VALUES ('" . $User->get_attribute('user_id') . "', '" .  time(). "')", __LINE__, __FILE__);

	AppContext::get_response()->redirect('/forum/index.php' . SID2);
}
else
	AppContext::get_response()->redirect('/forum/index.php' . SID2);

require_once('../kernel/footer_no_display.php');

?>