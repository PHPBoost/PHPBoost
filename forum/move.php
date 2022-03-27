<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 27
 * @since       PHPBoost 1.2 - 2005 10 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$lang = LangLoader::get_all_langs('forum');

$Bread_crumb->add($config->get_forum_name(), 'index.php');
define('TITLE', $lang['forum.module.title']);
require_once('../kernel/header.php');

$request = AppContext::get_request();

$id_get = $request->get_getint('id', 0); //Id du topic à déplacer.
$id_post = $request->get_postint('id', 0); //Id du topic à déplacer.
$id_get_msg = $request->get_getint('idm', 0); //Id du message à partir duquel il faut scinder le topic.
$id_post_msg = $request->get_postint('idm', 0); //Id du message à partir duquel il faut scinder le topic.
$error_get = $request->get_getvalue('error', '');  //Gestion des erreurs.
$post_topic = $request->get_postvalue('post_topic', ''); //Création du topic scindé.

if (!empty($id_get)) //Déplacement du sujet.
{
	$view = new FileTemplate('forum/forum_move.tpl');
	$view->add_lang($lang);

	try {
		$topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array('id_category', 'title'), 'WHERE id=:id', array('id' => $id_get));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	if (!ForumAuthorizationsService::check_authorizations($topic['id_category'])->moderation()) //Accès en édition
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	try {
		$cat = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_cats', array('id', 'name'), 'WHERE id=:id', array('id' => $topic['id_category']));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	//Listing des catégories disponibles, sauf celle qui va être supprimée.
	$search_category_children_options = new SearchCategoryChildrensOptions();
	$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
	$categories_tree = CategoriesService::get_categories_manager('forum')->get_select_categories_form_field('cats', '', Category::ROOT_CATEGORY, $search_category_children_options);
	$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
	$method->setAccessible(true);
	$categories_tree_options = $method->invoke($categories_tree);
	$cat_list = '';
	foreach ($categories_tree_options as $option)
	{
		if (!$option->get_raw_value())
		{
			$option->set_label('');
			$cat_list .= $option->display()->render();
		}
		else
		{
			$option_cat = CategoriesService::get_categories_manager('forum')->get_categories_cache()->get_category($option->get_raw_value());
			if ($option_cat->get_type() == ForumCategory::TYPE_CATEGORY || $option_cat->get_id() == $topic['id_category'])
				$option->set_disable(true);

			if (!$option_cat->get_url())
				$cat_list .= $option->display()->render();
		}
	}

	//Listes les utilisateurs en ligne.
	list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script LIKE '" ."/forum/%'");

	$vars_tpl = array(
		'C_USER_CONNECTED'      => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
		'C_NO_USER_ONLINE'      => (($total_online - $total_visit) == 0),

		'FORUM_NAME'            => $config->get_forum_name() . ' : ' . $lang['move_topic'],
		'CATEGORY_NAME'         => $cat['name'],
		'CATEGORIES'            => $cat_list,
		'ID'                    => $id_get,
		'TITLE'                 => stripslashes($topic['title']),
		'TITLE_T'               => stripslashes($topic['title']),
		'TOTAL_ONLINE'          => $total_online,
		'ONLINE_USERS_LIST'     => $users_list,
		'ADMINISTRATORS_NUMBER' => $total_admin,
		'MODERATORS_NUMBER'     => $total_modo,
		'MEMBERS_NUMBER'        => $total_member,
		'GUESTS_NUMBER'         => $total_visit,

		'U_CATEGORY'            => 'forum' . url('.php?id=' . $cat['id'], '-' . $cat['id'] . '.php'),
		'U_TITLE_T'             => 'topic' . url('.php?id=' . $id_get, '-' . $id_get . '.php'),

		'L_USER'   => ($total_online > 1) ? $lang['user.users'] : $lang['user.user'],
		'L_ADMIN'  => ($total_admin > 1) ? $lang['user.administrators'] : $lang['user.administrator'],
		'L_MODO'   => ($total_modo > 1) ? $lang['user.moderators']    : $lang['user.moderator'],
		'L_MEMBER' => ($total_member > 1) ? $lang['user.members'] : $lang['user.member'],
		'L_GUEST'  => ($total_visit > 1) ? $lang['user.guests'] : $lang['user.guest'],
	);

	$view->put_all($vars_tpl);
	$top_view->put_all($vars_tpl);
	$bottom_view->put_all($vars_tpl);

	$view->put('FORUM_TOP', $top_view);
	$view->put('FORUM_BOTTOM', $bottom_view);

	$view->display();
}
elseif (!empty($id_post)) //Déplacement du topic
{
	$id_category = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_topics", 'id_category', 'WHERE id = :id', array('id' => $id_post));
	if (ForumAuthorizationsService::check_authorizations($id_category)->moderation()) //Accès en édition
	{
		$to = (int)retrieve(POST, 'to', $id_category); //Catégorie cible.
		$category_to = CategoriesService::get_categories_manager('forum')->get_categories_cache()->get_category($to);
		if (!empty($to) && $category_to->get_id_parent() != Category::ROOT_CATEGORY && $id_category != $to)
		{
			//Instanciation de la class du forum.
			$Forumfct = new Forum();

			$Forumfct->Move_topic($id_post, $id_category, $to); //Déplacement du topic

			AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $id_post, '-' .$id_post  . '.php', '&'));
		}
		else
		{
			$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), LangLoader::get_message('warning.incomplete', 'warning-lang'));
            DispatchManager::redirect($controller);
		}
	}
	else
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}
}
elseif ((!empty($id_get_msg) || !empty($id_post_msg)) && empty($post_topic)) //Choix de la nouvelle catégorie, titre, sous-titre du topic à scinder.
{
	$view = new FileTemplate('forum/forum_post.tpl');
	$view->add_lang($lang);

	$idm = !empty($id_get_msg) ? $id_get_msg : $id_post_msg;

	try {
		$msg = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_msg', array('idtopic', 'content'), 'WHERE id=:id', array('id' => $idm));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	try {
		$topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array('id_category', 'title'), 'WHERE id=:id', array('id' => $msg['idtopic']));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	if (!ForumAuthorizationsService::check_authorizations($topic['id_category'])->moderation()) //Accès en édition
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	$id_first = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_msg", 'MIN(id)', 'WHERE idtopic = :id', array('id' => $msg['idtopic']));
	//Scindage du premier message interdite.
	if ($id_first == $idm)
	{
		$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), $lang['forum.error.non.cuttable.topic']);
		DispatchManager::redirect($controller);
	}

	try {
		$cat = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_cats', array('id', 'name'), 'WHERE id=:id', array('id' => $topic['id_category']));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	$to = (int)retrieve(POST, 'to', $cat['id']); //Catégorie cible.

	//Listing des catégories disponibles, sauf celle qui va être supprimée.
	$search_category_children_options = new SearchCategoryChildrensOptions();
	$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
	$categories_tree = CategoriesService::get_categories_manager('forum')->get_select_categories_form_field('cats', '', Category::ROOT_CATEGORY, $search_category_children_options);
	$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
	$method->setAccessible(true);
	$categories_tree_options = $method->invoke($categories_tree);
	$cat_list = '';
	foreach ($categories_tree_options as $option)
	{
		if (!$option->get_raw_value())
		{
			$option->set_label('');
			$cat_list .= $option->display()->render();
		}
		else
		{
			$option_cat = CategoriesService::get_categories_manager('forum')->get_categories_cache()->get_category($option->get_raw_value());
			if ($option_cat->get_type() == ForumCategory::TYPE_CATEGORY)
				$option->set_disable(true);

			if ($option_cat->get_id() == $topic['id_category'])
				$option->set_active(true);

			if (!$option_cat->get_url())
				$cat_list .= $option->display()->render();
		}
	}

	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('content');

	$vars_tpl = array(
		'C_FORUM_CUT_CAT'  => true,

		'FORUM_NAME'       => $config->get_forum_name() . ' : ' . $lang['cut_topic'],
		'CATEGORY_NAME'    => $cat['name'],
		'CATEGORIES'       => $cat_list,
		'KERNEL_EDITOR'    => $editor->display(),
		'IDTOPIC'          => 0,
		'TITLE_T'          => stripslashes($topic['title']),

		'U_CATEGORY'       => 'forum' . url('.php?id=' . $cat['id'], '-' . $cat['id'] . '.php'),
		'U_TITLE_T'        => 'topic' . url('.php?id=' . $msg['idtopic'], '-' . $msg['idtopic'] . '.php'),
	);

	if (empty($post_topic))
	{
		//Liste des choix des sondages => 20 maxi
		$nbr_poll_field = 0;
		for ($i = 0; $i < 5; $i++)
		{
			$view->assign_block_vars('answers_poll', array(
				'ID'     => $i,
				'ANSWER' => ''
			));
			$nbr_poll_field++;
		}

		$view->put_all(array(
			'C_FORUM_POST_TYPE'      => true,
			'C_ADD_POLL_FIELD'       => true,
			'C_SIMPLE_POLL_SELECTED' => true,
			'C_NORMAL_TYPE_SELECTED' => true,
			'C_DISPLAY_POLL'         => false,

			'TITLE'                  => '',
			'DESCRIPTION'            => '',
			'CONTENT'                => FormatingHelper::unparse(stripslashes($msg['content'])),
			'IDM'                    => $id_get_msg,
			'NBR_POLL_FIELD'         => $nbr_poll_field,
		));
	}

	//Listes les utilisateurs en ligne.
	list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script LIKE '" ."/forum/%'");

	$vars_tpl = array_merge($vars_tpl, array(
		'C_USER_CONNECTED'      => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),

		'TOTAL_ONLINE'          => $total_online,
		'C_NO_USER_ONLINE'      => (($total_online - $total_visit) == 0),
		'ONLINE_USERS_LIST'     => $users_list,
		'ADMINISTRATORS_NUMBER' => $total_admin,
		'MODERATORS_NUMBER'     => $total_modo,
		'MEMBERS_NUMBER'        => $total_member,
		'GUESTS_NUMBER'         => $total_visit,

		'L_USER'   => ($total_online > 1) ? $lang['user.users'] : $lang['user.user'],
		'L_ADMIN'  => ($total_admin > 1) ? $lang['user.administrators'] : $lang['user.administrator'],
		'L_MODO'   => ($total_modo > 1) ? $lang['user.moderators']    : $lang['user.moderator'],
		'L_MEMBER' => ($total_member > 1) ? $lang['user.members'] : $lang['user.member'],
		'L_GUEST'  => ($total_visit > 1) ? $lang['user.guests'] : $lang['user.guest'],
	));

	$view->put_all($vars_tpl);
	$top_view->put_all($vars_tpl);
	$bottom_view->put_all($vars_tpl);

	$view->put('FORUM_TOP', $top_view);
	$view->put('FORUM_BOTTOM', $bottom_view);

	$view->display();
}
elseif (!empty($id_post_msg) && !empty($post_topic)) //Scindage du topic
{
	try {
		$msg = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_msg', array('idtopic', 'user_id', 'timestamp', 'content'), 'WHERE id=:id', array('id' => $id_post_msg));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	try {
		$topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array('id_category', 'title', 'last_user_id', 'last_msg_id', 'last_timestamp'), 'WHERE id=:id', array('id' => $msg['idtopic']));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	$to = (int)retrieve(POST, 'to', 0); //Catégorie cible.

	if (!ForumAuthorizationsService::check_authorizations($topic['id_category'])->moderation()) //Accès en édition
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	$id_first = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_msg", 'MIN(id)', 'WHERE idtopic = :id', array('id' => $msg['idtopic']));
	//Scindage du premier message interdite.
	if ($id_first == $id_post_msg)
	{
		$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), $lang['forum.error.non.cuttable.topic']);
        DispatchManager::redirect($controller);
	}

	$category_to = CategoriesService::get_categories_manager('forum')->get_categories_cache()->get_category($to);
	if (!empty($to) && $category_to->get_id_parent() != Category::ROOT_CATEGORY)
	{
		$title = retrieve(POST, 'title', '');
		$subtitle = retrieve(POST, 'desc', '');
		$content = retrieve(POST, 'content', '', TSTRING_PARSE);
		$type = (int)retrieve(POST, 'type', 0);

		//Requête de "scindage" du topic.
		if (!empty($to) && !empty($content) && !empty($title))
		{
			//Instanciation de la class du forum.
			$Forumfct = new Forum();

			$last_topic_id = $Forumfct->Cut_topic($id_post_msg, $msg['idtopic'], $topic['id_category'], $to, $title, $subtitle, $content, $type, $msg['user_id'], $topic['last_user_id'], $topic['last_msg_id']); //Scindement du topic

			//Ajout d'un sondage en plus du topic.
			$question = retrieve(POST, 'question', '');
			if (!empty($question))
			{
				$poll_type = (int)retrieve(POST, 'poll_type', 0);
				$poll_type = ($poll_type == 0 || $poll_type == 1) ? $poll_type : 0;

				$answers = array();
				$nbr_votes = 0;
				for ($i = 0; $i < 20; $i++)
				{
					$answer = str_replace('|', '', retrieve(POST, 'a'.$i, ''));
					if (!empty($answer))
					{
						$answers[$i] = $answer;
						$nbr_votes++;
					}
				}
				$Forumfct->Add_poll($last_topic_id, $question, $answers, $nbr_votes, $poll_type); //Ajout du sondage.
			}

			AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $last_topic_id, '-' . $last_topic_id . '.php', '&'));
		}
		else
			AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=false_t&idm=' . $id_post_msg, '', '&') . '#message_helper');
	}
	else
	{
		$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), LangLoader::get_message('warning.incomplete', 'warning-lang'));
        DispatchManager::redirect($controller);
	}
}
else
{
	$controller = PHPBoostErrors::unknow();
    DispatchManager::redirect($controller);
}

include('../kernel/footer.php');

?>
