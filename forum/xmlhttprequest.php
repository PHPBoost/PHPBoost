<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 29
 * @since       PHPBoost 1.6 - 2007 02 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
AppContext::get_session()->no_session_location(); //Permet de ne pas mettre jour la page dans la session.
require_once('../forum/forum_begin.php');
require_once('../kernel/header_no_display.php');

$track = retrieve(GET, 't', '');
$untrack = retrieve(GET, 'ut', '');
$track_pm = retrieve(GET, 'tp', '');
$untrack_pm = retrieve(GET, 'utp', '');
$track_mail = retrieve(GET, 'tm', '');
$untrack_mail = retrieve(GET, 'utm', '');
$msg_d = retrieve(GET, 'msg_d', '');

if (retrieve(GET, 'refresh_unread', false)) //Affichage des messages non lus
{
	$is_guest = (AppContext::get_current_user()->get_id() == -1);
	$nbr_msg_not_read = 0;
	if (!$is_guest)
	{
		//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
		$max_time_msg = forum_limit_time_msg();

		//Vérification des autorisations.
		$authorized_categories = CategoriesService::get_authorized_categories();

		$contents = '';
		//Requête pour compter le nombre de messages non lus.
		$nbr_msg_not_read = 0;
		$result = PersistenceContext::get_querier()->select("SELECT t.id AS tid, t.title, t.last_timestamp, t.last_user_id, t.last_msg_id, t.nbr_msg AS t_nbr_msg, t.display_msg, m.user_id, m.display_name as login, m.level as user_level, m.groups, v.last_view_id
		FROM " . PREFIX . "forum_topics t
		LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.id_category
		LEFT JOIN " . PREFIX . "forum_view v ON v.idtopic = t.id AND v.user_id = '" . AppContext::get_current_user()->get_id() . "'
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = t.last_user_id
		WHERE t.last_timestamp >= '" . $max_time_msg . "' AND (v.last_view_id != t.last_msg_id OR v.last_view_id IS NULL) AND c.id IN :authorized_categories
		ORDER BY t.last_timestamp DESC", array(
			'authorized_categories' => $authorized_categories
		));
		while ($row = $result->fetch())
		{
			//Si le dernier message lu est présent on redirige vers lui, sinon on redirige vers le dernier posté.
			if (!empty($row['last_view_id'])) //Calcul de la page du last_view_id réalisé dans topic.php
			{
				$last_msg_id = $row['last_view_id'];
				$last_page = 'idm=' . $row['last_view_id'] . '&amp;';
				$last_page_rewrite = '-0-' . $row['last_view_id'];
			}
			else
			{
				$last_msg_id = $row['last_msg_id'];
				$last_page = ceil($row['t_nbr_msg'] / $config->get_number_messages_per_page());
				$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
				$last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';
			}

			$last_topic_title = (($config->is_message_before_topic_title_displayed() && $row['display_msg']) ? $config->get_message_before_topic_title() : '') . ' ' . $row['title'];
			$last_topic_title = addslashes($last_topic_title);
			$row['login'] = !empty($row['login']) ? $row['login'] : $LANG['guest'];
			$group_color = User::get_group_color($row['groups'], $row['user_level']);

			$contents .= '<tr><td class="forum-notread" style="width:100%"><a href="topic' . url('.php?' . $last_page .  'id=' . $row['tid'], '-' . $row['tid'] . $last_page_rewrite . '+' . addslashes(Url::encode_rewrite($row['title']))  . '.php') . '#m' .  $last_msg_id . '"><i class="fa fa-hand-point-right" aria-hidden="true"></i></a> <a href="topic' . url('.php?id=' . $row['tid'], '-' . $row['tid'] . '+' . addslashes(Url::encode_rewrite($row['title']))  . '.php') . '" class="small">' . $last_topic_title . '</a></td><td class="forum-notread" style="white-space:nowrap">' . ($row['last_user_id'] != '-1' ? '<a href="'. UserUrlBuilder::profile($row['last_user_id'])->rel() .'" class="small '.UserService::get_level_class($row['user_level']).'"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . addslashes($row['login']) . '</a>' : '<em>' . addslashes($LANG['guest']) . '</em>') . '</td><td class="forum-notread" style="white-space:nowrap">' . Date::to_format($row['last_timestamp'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) . '</td></tr>';
			$nbr_msg_not_read++;
		}
		$result->dispose();

		$max_visible_topics = 10;
		$height_visible_topics = ($nbr_msg_not_read < $max_visible_topics) ? (23 * $nbr_msg_not_read) : 23 * $max_visible_topics;

		echo "array_unread_topics[0] = '" . $nbr_msg_not_read . "';\n";
		echo "array_unread_topics[1] = '" . '<a class="small" href="' . PATH_TO_ROOT . '/forum/unread.php">' . addslashes($LANG['show_not_reads']) . (AppContext::get_current_user()->get_id() !== -1 ? ' (' . $nbr_msg_not_read . ')' : '') . '</a>' . "';\n";
		echo "array_unread_topics[2] = '" . '<div style="width:438px;height:' . max($height_visible_topics, 65) . 'px;overflow:auto;padding:0px;" onmouseover="forum_hide_block(\\\'forum_unread\\\', 1);" onmouseout="forum_hide_block(\\\'forum_unread\\\', 0);"><table class="module-table" style="margin:2px;width:99%">' . $contents . "</table></div>';";
	}
	else
		echo '';
}
elseif (retrieve(GET, 'del', false)) //Suppression d'un message.
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	//Instanciation de la class du forum.
	$Forumfct = new Forum();

	$idm_get = retrieve(GET, 'idm', '');
	$msg = $topic = array();

	//Info sur le message.
	try {
		$msg = PersistenceContext::get_querier()->select_single_row_query('SELECT user_id, idtopic FROM ' . PREFIX . 'forum_msg WHERE id=:id', array('id' => $idm_get));
	} catch (RowNotFoundException $e) {}

	//On va chercher les infos sur le topic
	if ($msg)
	{
		try {
			$topic = PersistenceContext::get_querier()->select_single_row_query('SELECT id, user_id, id_category, first_msg_id, last_msg_id, last_timestamp FROM ' . PREFIX . 'forum_topics WHERE id=:id', array('id' => $msg['idtopic']));
		} catch (RowNotFoundException $e) {}
	}

	if ($msg && $topic && !empty($msg['idtopic']) && $topic['first_msg_id'] != $idm_get) //Suppression d'un message.
	{
		if (!empty($topic['id_category']) && (ForumAuthorizationsService::check_authorizations($topic['id_category'])->moderation() || AppContext::get_current_user()->get_id() == $msg['user_id'])) //Autorisé à supprimer?
		{
			list($nbr_msg, $previous_msg_id) = $Forumfct->Del_msg($idm_get, $msg['idtopic'], $topic['id_category'], $topic['first_msg_id'], $topic['last_msg_id'], $topic['last_timestamp'], $msg['user_id']); //Suppression du message.
			if ($nbr_msg === false && $previous_msg_id === false) //Echec de la suppression.
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
elseif (!empty($track) && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Ajout du sujet aux sujets suivis.
{
	//Instanciation de la class du forum.
	$Forumfct = new Forum();
	$Forumfct->Track_topic($track); //Ajout du sujet aux sujets suivis.
	echo 1;
}
elseif (!empty($untrack) && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Retrait du sujet, aux sujets suivis.
{
	//Instanciation de la class du forum.
	$Forumfct = new Forum();

	$Forumfct->Untrack_topic($untrack); //Retrait du sujet aux sujets suivis.
	echo 2;
}
elseif (!empty($track_pm) && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Ajout du sujet aux sujets suivis.
{
	//Instanciation de la class du forum.
	$Forumfct = new Forum();

	$Forumfct->Track_topic($track_pm, FORUM_PM_TRACKING); //Ajout du sujet aux sujets suivis.
	echo 1;
}
elseif (!empty($untrack_pm) && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Retrait du sujet, aux sujets suivis.
{
	//Instanciation de la class du forum.
	$Forumfct = new Forum();
	$Forumfct->Untrack_topic($untrack_pm, FORUM_PM_TRACKING); //Retrait du sujet aux sujets suivis.
	echo 2;
}
elseif (!empty($track_mail) && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Ajout du sujet aux sujets suivis.
{
	//Instanciation de la class du forum.
	$Forumfct = new Forum();

	$Forumfct->Track_topic($track_mail, FORUM_EMAIL_TRACKING); //Ajout du sujet aux sujets suivis.
	echo 1;
}
elseif (!empty($untrack_mail) && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Retrait du sujet, aux sujets suivis.
{
	//Instanciation de la class du forum.
	$Forumfct = new Forum();

	$Forumfct->Untrack_topic($untrack_mail, FORUM_EMAIL_TRACKING); //Retrait du sujet aux sujets suivis.
	echo 2;
}
elseif (!empty($msg_d))
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	$topic = array();

	//Vérification de l'appartenance du sujet au membres, ou modo.
	try {
		$topic = PersistenceContext::get_querier()->select_single_row_query('SELECT id_category, user_id, display_msg FROM ' . PREFIX . 'forum_topics WHERE id=:id', array('id' => $msg_d));
	} catch (RowNotFoundException $e) {}

	if ($topic && ((!empty($topic['user_id']) && AppContext::get_current_user()->get_id() == $topic['user_id']) || ForumAuthorizationsService::check_authorizations($topic['id_category'])->moderation()))
	{
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_topics SET display_msg = 1 - display_msg WHERE id = :id", array('id' => $msg_d));
		echo ($topic['display_msg']) ? 2 : 1;
	}
}
elseif ((bool)retrieve(GET, 'warning_moderation_panel', false) || (bool)retrieve(GET, 'punish_moderation_panel', false)) //Recherche d'un membre
{
	$login = TextHelper::strprotect(utf8_decode(AppContext::get_request()->get_postvalue('login', '')));
	$login = str_replace('*', '%', $login);
	if (!empty($login))
	{
		$i = 0;
		$result = PersistenceContext::get_querier()->select("SELECT user_id, display_name, level, groups FROM " . DB_TABLE_MEMBER . " WHERE display_name LIKE '" . $login . "%'");
		while ($row = $result->fetch())
		{
			$group_color = User::get_group_color($row['groups'], $row['level']);

			if (retrieve(GET, 'warning_moderation_panel', false))
				echo '<a href="moderation_forum.php?action=warning&amp;id=' . $row['user_id'] . '" class="'.UserService::get_level_class($row['level']).'"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $row['display_name'] . '</a><br />';
			elseif (retrieve(GET, 'punish_moderation_panel', false))
				echo '<a href="moderation_forum.php?action=punish&amp;id=' . $row['user_id'] . '" class="'.UserService::get_level_class($row['level']).'"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $row['display_name'] . '</a><br />';

			$i++;
		}

		if ($i == 0) //Aucun membre trouvé.
			echo $LANG['no_result'];
	}
	else
		echo $LANG['no_result'];
}
else
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

include_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>
