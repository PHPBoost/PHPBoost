<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 01
 * @since       PHPBoost 1.5 - 2006 08 07
 * @contributor Benoit SAUTEL <ben.popeye@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$lang = LangLoader::get('common', 'forum');

$request = AppContext::get_request();

$alert = $request->get_getint('id', 0);
$alert_post = $request->get_postint('id', 0);
$topic_id = !empty($alert) ? $alert : $alert_post;

try {
	$topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array('id_category', 'title', 'subtitle'), 'WHERE id = :id', array('id' => $topic_id));
} catch (RowNotFoundException $e) {
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$category = CategoriesService::get_categories_manager('forum')->get_categories_cache()->get_category($topic['id_category']);

$topic_name = !empty($topic['title']) ? stripslashes($topic['title']) : '';
$Bread_crumb->add($config->get_forum_name(), 'index.php');
$Bread_crumb->add($category->get_name(), 'forum' . url('.php?id=' . $topic['id_category'], '-' . $topic['id_category'] . '+' . $category->get_rewrited_name() . '.php'));
$Bread_crumb->add($topic['title'], 'topic' . url('.php?id=' . $alert, '-' . $alert . '-' . Url::encode_rewrite($topic_name) . '.php'));
$Bread_crumb->add($LANG['alert_topic'], '');

define('TITLE', $LANG['alert_topic']);
require_once('../kernel/header.php');

if (empty($alert) && empty($alert_post) || empty($topic['id_category']))
	AppContext::get_response()->redirect('/forum/index' . url('.php'));

if (!AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Si c'est un invité
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

$view = new FileTemplate('forum/forum_alert.tpl');
$view->add_lang(array_merge(
	LangLoader::get('common', 'forum'),
	LangLoader::get('common-lang'),
	LangLoader::get('form-lang'),
	LangLoader::get('warning-lang')
));

//On fait un formulaire d'alerte
if (!empty($alert) && empty($alert_post))
{
	//On vérifie qu'une alerte sur le même sujet n'ait pas été postée
	$nbr_alert = PersistenceContext::get_querier()->count(PREFIX . 'forum_alerts', 'WHERE idtopic=:idtopic AND status = 0', array('idtopic' => $alert));
	if (empty($nbr_alert)) //On affiche le formulaire
	{
		$editor = AppContext::get_content_formatting_service()->get_default_editor();
		$editor->set_identifier('content');

		$view->put_all(array(
			'KERNEL_EDITOR'    => $editor->display(),
			'L_ALERT'          => $LANG['alert_topic'],
			'L_ALERT_EXPLAIN'  => $LANG['alert_modo_explain'],
			'L_ALERT_TITLE'    => $LANG['alert_title'],
			'L_ALERT_CONTENT'  => $LANG['alert_content'],
			'L_REQUIRE'        => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
			'L_REQUIRE_TEXT'   => $LANG['require_text'],
			'L_REQUIRE_TITLE'  => $LANG['require_title']
		));

		$view->assign_block_vars('alert_form', array(
			'TITLE'     => $topic_name,
			'U_TOPIC'   => 'topic' . url('.php?id=' . $alert, '-' . $alert . '-' . Url::encode_rewrite($topic_name) . '.php'),
			'REPORT_ID' => $alert
		));
	}
	else //Une alerte a déjà été postée
	{
		$view->put_all(array(
			'URL_TOPIC'    => 'topic' . url('.php?id=' . $alert, '-' . $alert . '-' . Url::encode_rewrite($topic_name) . '.php'),
			'L_ALERT'      => $LANG['alert_topic'],
			'L_BACK_TOPIC' => $LANG['alert_back']
		));

		$view->assign_block_vars('alert_confirm', array(
			'MSG' => $LANG['alert_topic_already_done']
		));
	}
}

//Si on enregistre une alerte
if (!empty($alert_post))
{
	$view->put_all(array(
		'URL_TOPIC'    => 'topic' . url('.php?id=' . $alert_post, '-' . $alert_post . '-' . Url::encode_rewrite($topic_name) . '.php'),
		'L_ALERT'      => $LANG['alert_topic'],
		'L_BACK_TOPIC' => $LANG['alert_back']
	));

	//On vérifie qu'une alerte sur le même sujet n'ait pas été postée
	$nbr_alert = PersistenceContext::get_querier()->count(PREFIX . 'forum_alerts', 'WHERE idtopic=:idtopic AND status = 0', array('idtopic' => $alert_post));
	if (empty($nbr_alert)) //On enregistre
	{
		$alert_title = $request->get_poststring('title', '');
		$alert_content = retrieve(POST, 'content', '', TSTRING_PARSE);

		//Instanciation de la class du forum.
		$Forumfct = new Forum;

		$Forumfct->Alert_topic($alert_post, $alert_title, $alert_content);

		$view->assign_block_vars('alert_confirm', array(
			'MSG' => str_replace('%title', $topic_name, $LANG['alert_success'])
		));
	}
	else //Une alerte a déjà été postée
	{
		$view->assign_block_vars('alert_confirm', array(
			'MSG' => $LANG['alert_topic_already_done']
		));
	}

}

//Listes les utilisateurs en ligne.
list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script LIKE '/forum/%'");

$vars_tpl = array(
	'C_USER_CONNECTED'      => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
	'C_NO_USER_ONLINE'      => (($total_online - $total_visit) == 0),

	'CATEGORY_NAME'         => $category->get_name(),
	'FORUM_NAME'            => $config->get_forum_name() . ' : ' . $LANG['alert_topic'],
	'TITLE_T'               => stripslashes($topic['title']),
	'TOTAL_ONLINE'          => $total_online,
	'ONLINE_USERS_LIST'     => $users_list,
	'ADMINISTRATORS_NUMBER' => $total_admin,
	'MODERATORS_NUMBER'     => $total_modo,
	'MEMBERS_NUMBER'        => $total_member,
	'GUESTS_NUMBER'         => $total_visit,

	'U_CATEGORY'            => 'forum' . url('.php?id=' . $topic['id_category'], '-' . $topic['id_category'] . '.php'),
	'U_TITLE_T'             => 'topic' . url('.php?id=' . $topic_id, '-' . $topic_id . '.php'),

	'L_USER'                => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
	'L_ADMIN'               => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
	'L_MODO'                => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
	'L_MEMBER'              => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
	'L_GUEST'               => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
	//
	'L_FORUM_INDEX'         => $LANG['forum_index'],
	'L_SUBMIT'              => $LANG['submit'],
	'L_PREVIEW'             => $LANG['preview'],
	'L_RESET'               => $LANG['reset'],
	'L_AND'                 => $LANG['and'],
	'L_ONLINE'              => TextHelper::strtolower($LANG['online'])
);

$view->put_all($vars_tpl);
$top_view->put_all($vars_tpl);
$bottom_view->put_all($vars_tpl);

$view->put('FORUM_TOP', $top_view);
$view->put('FORUM_BOTTOM', $bottom_view);

$view->display();

include('../kernel/footer.php');

?>
