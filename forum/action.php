<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 29
 * @since       PHPBoost 1.2 - 2005 08 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
$Bread_crumb->add($config->get_forum_name(), 'index.php');
require_once('../kernel/header_no_display.php');

//Variable GET.
$idt_get = (int)retrieve(GET, 'id', 0);
$idm_get = (int)retrieve(GET, 'idm', 0);
$del = (bool)retrieve(GET, 'del', false);
$alert = retrieve(GET, 'a', '');
$read = (bool)retrieve(GET, 'read', false);
$msg_d = (bool)retrieve(GET, 'msg_d', false);
$lock_get = retrieve(GET, 'lock', '');
$page_get = (int)retrieve(GET, 'p', 1);

$track = retrieve(GET, 't', '');
$untrack = retrieve(GET, 'ut', '');
$track_pm = retrieve(GET, 'tp', '');
$untrack_pm = retrieve(GET, 'utp', '');
$track_mail = retrieve(GET, 'tm', '');
$untrack_mail = retrieve(GET, 'utm', '');

$poll = (bool)retrieve(POST, 'valid_forum_poll', false); //Sondage forum.
$massive_action_type = retrieve(POST, 'action_type', ''); //Opération de masse.

$Forumfct = new Forum();

if (!empty($idm_get) && $del) //Suppression d'un message/topic.
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	//Info sur le message.
	try {
		$msg = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_msg', array('user_id', 'idtopic'), 'WHERE id=:id', array('id' => $idm_get));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	//On va chercher les infos sur le topic
	try {
		$topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array('user_id', 'id_category', 'title', 'subtitle', 'first_msg_id', 'last_msg_id', 'last_timestamp'), 'WHERE id=:id', array('id' => $msg['idtopic']));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	//Si on veut supprimer le premier message, alors son rippe le topic entier (admin et modo seulement).
	if (!empty($msg['idtopic']) && $topic['first_msg_id'] == $idm_get)
	{
		if (!empty($msg['idtopic']) && (ForumAuthorizationsService::check_authorizations($topic['id_category'])->moderation() || AppContext::get_current_user()->get_id() == $topic['user_id'])) //Autorisé à supprimer?
		{
			$Forumfct->Del_topic($msg['idtopic']); //Suppresion du topic.
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		AppContext::get_response()->redirect('/forum/forum' . url('.php?id=' . $topic['id_category'], '-' . $topic['id_category'] . '.php', '&'));
	}
	elseif (!empty($msg['idtopic']) && $topic['first_msg_id'] != $idm_get) //Suppression d'un message.
	{
		if (!empty($topic['id_category']) && (ForumAuthorizationsService::check_authorizations($topic['id_category'])->moderation() || AppContext::get_current_user()->get_id() == $msg['user_id'])) //Autorisé à supprimer?
		{
			list($nbr_msg, $previous_msg_id) = $Forumfct->Del_msg($idm_get, $msg['idtopic'], $topic['id_category'], $topic['first_msg_id'], $topic['last_msg_id'], $topic['last_timestamp'], $msg['user_id']);
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		if ($nbr_msg === false && $previous_msg_id === false) //Echec de la suppression.
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		//On compte le nombre de messages du topic avant l'id supprimé.
		$last_page = ceil( $nbr_msg/ $config->get_number_messages_per_page() );
		$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
		$last_page = ($last_page > 1) ? '&pt=' . $last_page : '';

		AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $msg['idtopic'] . $last_page, '-' . $msg['idtopic'] . $last_page_rewrite . '.php', '&') . '#m' . $previous_msg_id);
	}
	else //Non autorisé, on redirige.
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
}
elseif (!empty($idt_get))
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	//On va chercher les infos sur le topic
	try {
		$topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array('user_id', 'id_category', 'title', 'subtitle', 'nbr_msg', 'last_msg_id', 'first_msg_id', 'last_timestamp', 'status'), 'WHERE id=:id', array('id' => $idt_get));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	if (!ForumAuthorizationsService::check_authorizations($topic['id_category'])->read())
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	try {
		$category = CategoriesService::get_categories_manager('forum', 'id_category')->get_categories_cache()->get_category($topic['id_category']);
	} catch (CategoryNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
	$rewrited_cat_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . $category->get_rewrited_name() : '';
	//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
	$rewrited_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . Url::encode_rewrite($topic['title']) : '';

	//Changement du statut (display_msg) du sujet.
	if ($msg_d)
	{
		//Vérification de l'appartenance du sujet au membres, ou modo.
		$check_mbr = 0;
		try {
			$check_mbr = PersistenceContext::get_querier()->get_column_value(PREFIX . 'forum_topics', 'user_id', 'WHERE id=:id', array('id' => $idt_get));
		} catch (RowNotFoundException $e) {}

		if ((!empty($check_mbr) && AppContext::get_current_user()->get_id() == $check_mbr) || ForumAuthorizationsService::check_authorizations($topic['id_category'])->moderation())
		{
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_topics SET display_msg = 1 - display_msg WHERE id = '" . $idt_get . "'");

			AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $idt_get, '-' . $idt_get . $rewrited_title . '.php', '&'));
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	elseif ($poll && AppContext::get_current_user()->get_id() !== -1) //Enregistrement vote du sondage
	{
		try {
			$info_poll = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_poll', array('voter_id', 'votes', 'type'), 'WHERE idtopic=:id', array('id' => $idt_get));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		//Si l'utilisateur n'est pas dans le champ on prend en compte le vote.
		$voter_id = explode('|', $info_poll['voter_id']);
		if (!in_array(AppContext::get_current_user()->get_id(), $voter_id))
		{
			//On concatène avec les votans existants.
			$voter_id[] = AppContext::get_current_user()->get_id();
			$array_votes = explode('|', $info_poll['votes']);

			if ($info_poll['type'] == 0) //Réponse simple.
			{
				$id_answer = (int)retrieve(POST, 'forumpoll', 0);
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
			PersistenceContext::get_querier()->update(PREFIX . 'forum_poll', array('voter_id' =>  implode('|', $voter_id), 'votes' =>  implode('|', $array_votes)), 'WHERE idtopic=:id', array('id' => $idt_get));
		}

		AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $idt_get . '&pt=' . $page_get, '-' . $idt_get . '-' . $page_get . $rewrited_title . '.php', '&'));
	}
	elseif (!empty($lock_get))
	{
		//Si l'utilisateur a le droit de déplacer le topic, ou le verrouiller.
		if (ForumAuthorizationsService::check_authorizations($topic['id_category'])->moderation())
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
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	else
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
}
elseif (!empty($track) && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Ajout du sujet aux sujets suivis.
{
	$Forumfct->Track_topic($track); //Ajout du sujet aux sujets suivis.

	AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $track, '-' . $track . '.php', '&') . '#go-bottom');
}
elseif (!empty($untrack) && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Retrait du sujet, aux sujets suivis.
{
	$tracking_type = (int)retrieve(GET, 'trt', 0);
	$Forumfct->Untrack_topic($untrack, $tracking_type); //Retrait du sujet aux sujets suivis.

	AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $untrack, '-' . $untrack . '.php', '&') . '#go-bottom');
}
elseif (!empty($track_pm) && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Ajout du sujet aux sujets suivis.
{
	//Instanciation de la class du forum.
	$Forumfct = new Forum();

	$Forumfct->Track_topic($track_pm, FORUM_PM_TRACKING); //Ajout du sujet aux sujets suivis.
	AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $track_pm, '-' . $track_pm . '.php', '&') . '#go-bottom');
}
elseif (!empty($untrack_pm) && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Retrait du sujet, aux sujets suivis.
{
	//Instanciation de la class du forum.
	$Forumfct = new Forum();

	$Forumfct->Untrack_topic($untrack_pm, FORUM_PM_TRACKING); //Retrait du sujet aux sujets suivis.
	AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $untrack_pm, '-' . $untrack_pm . '.php', '&') . '#go-bottom');
}
elseif (!empty($track_mail) && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Ajout du sujet aux sujets suivis.
{
	//Instanciation de la class du forum.
	$Forumfct = new Forum();

	$Forumfct->Track_topic($track_mail, FORUM_EMAIL_TRACKING); //Ajout du sujet aux sujets suivis.
	AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $track_mail, '-' . $track_mail . '.php', '&') . '#go-bottom');
}
elseif (!empty($untrack_mail) && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Retrait du sujet, aux sujets suivis.
{
	//Instanciation de la class du forum.
	$Forumfct = new Forum();

	$Forumfct->Untrack_topic($untrack_mail, FORUM_EMAIL_TRACKING); //Retrait du sujet aux sujets suivis.
	AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $untrack_mail, '-' . $untrack_mail . '.php', '&') . '#go-bottom');
}
elseif ($read) //Marquer comme lu.
{
	if (!AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Réservé aux membres.
		AppContext::get_response()->redirect(UserUrlBuilder::connect());

	//Calcul du temps de péremption, ou de dernière vue des messages.
	$check_last_view_forum = PersistenceContext::get_querier()->count(DB_TABLE_MEMBER_EXTENDED_FIELDS, 'WHERE user_id=:user_id', array('user_id' => AppContext::get_current_user()->get_id()));

	//Modification du last_view_forum, si le membre est déjà dans la table
	if (!empty($check_last_view_forum))
		PersistenceContext::get_querier()->update(DB_TABLE_MEMBER_EXTENDED_FIELDS, array('last_view_forum' => time()), 'WHERE user_id=:id', array('id' => AppContext::get_current_user()->get_id()));
	else
		PersistenceContext::get_querier()->insert(DB_TABLE_MEMBER_EXTENDED_FIELDS, array('user_id' => AppContext::get_current_user()->get_id(), 'last_view_forum' =>  time()));

	AppContext::get_session()->recheck_cached_data();

	AppContext::get_response()->redirect('/forum/index.php');
}
else
	AppContext::get_response()->redirect('/forum/index.php');

require_once('../kernel/footer_no_display.php');

?>
