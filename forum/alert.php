<?php
/*##################################################
 *                                alert.php
 *                            -------------------
 *   begin                : August 7, 2006
 *   copyright            : (C) 2006 Viarre Régis / Sautel Benoît
 *   email                : crowkait@phpboost.com / ben.popeye@phpboost.com
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
require_once('../forum/forum_tools.php');

$alert = retrieve(GET, 'id', 0);
$alert_post = retrieve(POST, 'id', 0);
$topic_id = !empty($alert) ? $alert : $alert_post;

try {
	$topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array('idcat', 'title', 'subtitle'), 'WHERE id = :id', array('id' => $topic_id));
} catch (RowNotFoundException $e) {
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$category = ForumService::get_categories_manager()->get_categories_cache()->get_category($topic['idcat']);

$topic_name = !empty($topic['title']) ? stripslashes($topic['title']) : '';
$Bread_crumb->add($config->get_forum_name(), 'index.php');
$Bread_crumb->add($category->get_name(), 'forum' . url('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '+' . $category->get_rewrited_name() . '.php'));
$Bread_crumb->add($topic['title'], 'topic' . url('.php?id=' . $alert, '-' . $alert . '-' . Url::encode_rewrite($topic_name) . '.php'));
$Bread_crumb->add($LANG['alert_topic'], '');

define('TITLE', $LANG['alert_topic']);
require_once('../kernel/header.php');

if (empty($alert) && empty($alert_post) || empty($topic['idcat']))
	AppContext::get_response()->redirect('/forum/index' . url('.php'));

if (!AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Si c'est un invité
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

$tpl = new FileTemplate('forum/forum_alert.tpl');

//On fait un formulaire d'alerte
if (!empty($alert) && empty($alert_post))
{
	//On vérifie qu'une alerte sur le même sujet n'ait pas été postée
	$nbr_alert = PersistenceContext::get_querier()->count(PREFIX . 'forum_alerts', 'WHERE idtopic=:idtopic AND status = 0', array('idtopic' => $alert));
	if (empty($nbr_alert)) //On affiche le formulaire
	{
		$editor = AppContext::get_content_formatting_service()->get_default_editor();
		$editor->set_identifier('contents');
	
		$tpl->put_all(array(
			'KERNEL_EDITOR' => $editor->display(),
			'L_ALERT' => $LANG['alert_topic'],
			'L_ALERT_EXPLAIN' => $LANG['alert_modo_explain'],
			'L_ALERT_TITLE' => $LANG['alert_title'],
			'L_ALERT_CONTENTS' => $LANG['alert_contents'],
			'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
			'L_REQUIRE_TEXT' => $LANG['require_text'],
			'L_REQUIRE_TITLE' => $LANG['require_title']
		));

		$tpl->assign_block_vars('alert_form', array(
			'TITLE' => $topic_name,
			'U_TOPIC' => 'topic' . url('.php?id=' . $alert, '-' . $alert . '-' . Url::encode_rewrite($topic_name) . '.php'),
			'ID_ALERT' => $alert,
		));
	}
	else //Une alerte a déjà été postée
	{
		$tpl->put_all(array(
			'L_ALERT' => $LANG['alert_topic'],
			'L_BACK_TOPIC' => $LANG['alert_back'],
			'URL_TOPIC' => 'topic' . url('.php?id=' . $alert, '-' . $alert . '-' . Url::encode_rewrite($topic_name) . '.php')
		));

		$tpl->assign_block_vars('alert_confirm', array(
			'MSG' => $LANG['alert_topic_already_done']
		));
	}
}

//Si on enregistre une alerte
if (!empty($alert_post))
{
	$tpl->put_all(array(
		'L_ALERT' => $LANG['alert_topic'],
		'L_BACK_TOPIC' => $LANG['alert_back'],
		'URL_TOPIC' => 'topic' . url('.php?id=' . $alert_post, '-' . $alert_post . '-' . Url::encode_rewrite($topic_name) . '.php')
	));

	//On vérifie qu'une alerte sur le même sujet n'ait pas été postée
	$nbr_alert = PersistenceContext::get_querier()->count(PREFIX . 'forum_alerts', 'WHERE idtopic=:idtopic AND status = 0', array('idtopic' => $alert_post));
	if (empty($nbr_alert)) //On enregistre
	{
		$alert_title = retrieve(POST, 'title', '');
		$alert_contents = retrieve(POST, 'contents', '', TSTRING_PARSE);

		//Instanciation de la class du forum.
		$Forumfct = new Forum;

		$Forumfct->Alert_topic($alert_post, $alert_title, $alert_contents);

		$tpl->assign_block_vars('alert_confirm', array(
			'MSG' => str_replace('%title', $topic_name, $LANG['alert_success'])
		));
	}
	else //Une alerte a déjà été postée
	{
		$tpl->assign_block_vars('alert_confirm', array(
			'MSG' => $LANG['alert_topic_already_done']
		));
	}

}

//Listes les utilisateurs en lignes.
list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script LIKE '/forum/%'");

$vars_tpl = array(
	'FORUM_NAME' => $config->get_forum_name() . ' : ' . $LANG['alert_topic'],
	'DESC' => stripslashes($topic['subtitle']),
	'C_USER_CONNECTED' => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
	'TOTAL_ONLINE' => $total_online,
	'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
	'ADMIN' => $total_admin,
	'MODO' => $total_modo,
	'MEMBER' => $total_member,
	'GUEST' => $total_visit,
	'U_FORUM_CAT' => '<a href="forum' . url('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '.php') . '">' . $category->get_name() . '</a>',
	'U_TITLE_T' => '<a href="topic' . url('.php?id=' . $topic_id, '-' . $topic_id . '.php') . '">' . stripslashes($topic['title']) . '</a>',
	'L_FORUM_INDEX' => $LANG['forum_index'],
	'L_SUBMIT' => $LANG['submit'],
	'L_PREVIEW' => $LANG['preview'],
	'L_RESET' => $LANG['reset'],
	'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
	'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
	'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
	'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
	'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
	'L_AND' => $LANG['and'],
	'L_ONLINE' => strtolower($LANG['online'])
);

$tpl->put_all($vars_tpl);
$tpl_top->put_all($vars_tpl);
$tpl_bottom->put_all($vars_tpl);
	
$tpl->put('forum_top', $tpl_top);
$tpl->put('forum_bottom', $tpl_bottom);
	
$tpl->display();

include('../kernel/footer.php');

?>