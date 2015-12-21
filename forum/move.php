<?php
/*##################################################
 *                               move.php
 *                            -------------------
 *   begin                : October 30, 2005
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
require_once('../forum/forum_tools.php');
$Bread_crumb->add($config->get_forum_name(), 'index.php');
define('TITLE', $LANG['title_forum']);
require_once('../kernel/header.php');

$request = AppContext::get_request();

$id_get = $request->get_getint('id', 0); //Id du topic à déplacer.
$id_post = $request->get_postint('id', 0); //Id du topic à déplacer.
$id_get_msg = $request->get_getint('idm', 0); //Id du message à partir duquel il faut scinder le topic.
$id_post_msg = $request->get_postint('idm', 0); //Id du message à partir duquel il faut scinder le topic.
$error_get = $request->get_getvalue('error', '');  //Gestion des erreurs.
$post_topic = $request->get_postvalue('post_topic', ''); //Création du topic scindé.
$preview_topic = $request->get_postvalue('prw_t', ''); //Prévisualisation du topic scindé.

if (!empty($id_get)) //Déplacement du sujet.
{
	$tpl = new FileTemplate('forum/forum_move.tpl');

	try {
		$topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array('idcat', 'title'), 'WHERE id=:id', array('id' => $id_get));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}
	
	if (!ForumAuthorizationsService::check_authorizations($topic['idcat'])->moderation()) //Accès en édition
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	try {
		$cat = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_cats', array('id', 'name'), 'WHERE id=:id', array('id' => $topic['idcat']));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}
	
	//Listing des catégories disponibles, sauf celle qui va être supprimée.
	$search_category_children_options = new SearchCategoryChildrensOptions();
	$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
	$categories_tree = ForumService::get_categories_manager()->get_select_categories_form_field('cats', '', Category::ROOT_CATEGORY, $search_category_children_options);
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
			$option_cat = ForumService::get_categories_manager()->get_categories_cache()->get_category($option->get_raw_value());
			if ($option_cat->get_type() == ForumCategory::TYPE_CATEGORY || $option_cat->get_id() == $topic['idcat'])
				$option->set_disable(true);
			
			if (!$option_cat->get_url())
				$cat_list .= $option->display()->render();
		}
	}

	//Listes les utilisateurs en lignes.
	list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script LIKE '" ."/forum/%'");

	$vars_tpl = array(
		'C_USER_CONNECTED' => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
		'TOTAL_ONLINE' => $total_online,
		'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
		'ADMIN' => $total_admin,
		'MODO' => $total_modo,
		'MEMBER' => $total_member,
		'GUEST' => $total_visit,
		'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
		'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
		'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
		'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
		'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
		'L_AND' => $LANG['and'],
		'L_ONLINE' => strtolower($LANG['online']),
		'FORUM_NAME' => $config->get_forum_name() . ' : ' . $LANG['move_topic'],
		'ID' => $id_get,
		'TITLE' => stripslashes($topic['title']),
		'CATEGORIES' => $cat_list,
		'U_FORUM_CAT' => '<a href="forum' . url('.php?id=' . $cat['id'], '-' . $cat['id'] . '.php') . '">' . $cat['name'] . '</a>',
		'U_TITLE_T' => '<a href="topic' . url('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . stripslashes($topic['title']) . '</a>',
		'L_SELECT_SUBCAT' => $LANG['require_subcat'],
		'L_MOVE_SUBJECT' => $LANG['forum_move_subject'],
		'L_CAT' => $LANG['category'],
		'L_FORUM_INDEX' => $LANG['forum_index'],
		'L_SUBMIT' => $LANG['submit']
	);

	$tpl->put_all($vars_tpl);
	$tpl_top->put_all($vars_tpl);
	$tpl_bottom->put_all($vars_tpl);
		
	$tpl->put('forum_top', $tpl_top);
	$tpl->put('forum_bottom', $tpl_bottom);
		
	$tpl->display();
}
elseif (!empty($id_post)) //Déplacement du topic
{
	$idcat = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_topics", 'idcat', 'WHERE id = :id', array('id' => $id_post));
	if (ForumAuthorizationsService::check_authorizations($idcat)->moderation()) //Accès en édition
	{
		$to = retrieve(POST, 'to', $idcat); //Catégorie cible.
		$category_to = ForumService::get_categories_manager()->get_categories_cache()->get_category($to);
		if (!empty($to) && $category_to->get_id_parent() != Category::ROOT_CATEGORY && $idcat != $to)
		{
			//Instanciation de la class du forum.
			$Forumfct = new Forum();

			$Forumfct->Move_topic($id_post, $idcat, $to); //Déplacement du topic
			
			AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $id_post, '-' .$id_post  . '.php', '&'));
		}
		else
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), 
                $LANG['e_incomplete']);
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
	$tpl = new FileTemplate('forum/forum_post.tpl');

	$idm = !empty($id_get_msg) ? $id_get_msg : $id_post_msg;
	
	try {
		$msg = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_msg', array('idtopic', 'contents'), 'WHERE id=:id', array('id' => $idm));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}
	
	try {
		$topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array('idcat', 'title'), 'WHERE id=:id', array('id' => $msg['idtopic']));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}
	
	if (!ForumAuthorizationsService::check_authorizations($topic['idcat'])->moderation()) //Accès en édition
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	$id_first = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_msg", 'MIN(id)', 'WHERE idtopic = :id', array('id' => $msg['idtopic']));
	//Scindage du premier message interdite.
	if ($id_first == $idm)
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $LANG['e_unable_cut_forum']);
		DispatchManager::redirect($controller);
	}

	try {
		$cat = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_cats', array('id', 'name'), 'WHERE id=:id', array('id' => $topic['idcat']));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}
	
	$to = retrieve(POST, 'to', $cat['id']); //Catégorie cible.

	//Listing des catégories disponibles, sauf celle qui va être supprimée.
	$search_category_children_options = new SearchCategoryChildrensOptions();
	$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
	$categories_tree = ForumService::get_categories_manager()->get_select_categories_form_field('cats', '', Category::ROOT_CATEGORY, $search_category_children_options);
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
			$option_cat = ForumService::get_categories_manager()->get_categories_cache()->get_category($option->get_raw_value());
			if ($option_cat->get_type() == ForumCategory::TYPE_CATEGORY)
				$option->set_disable(true);
			
			if ($option_cat->get_id() == $topic['idcat'])
				$option->set_active(true);
			
			if (!$option_cat->get_url())
				$cat_list .= $option->display()->render();
		}
	}

	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('contents');
		
	$vars_tpl = array(
		'C_FORUM_CUT_CAT' => true,
		'CATEGORIES' => $cat_list,
		'KERNEL_EDITOR' => $editor->display(),
		'FORUM_NAME' => $config->get_forum_name() . ' : ' . $LANG['cut_topic'],
		'IDTOPIC' => 0,
		'U_TITLE_T' => '<a href="topic' . url('.php?id=' . $msg['idtopic'], '-' . $msg['idtopic'] . '.php') . '">' . stripslashes($topic['title']) . '</a>',
		'U_FORUM_CAT' => '<a href="forum' . url('.php?id=' . $cat['id'], '-' . $cat['id'] . '.php') . '">' . $cat['name'] . '</a>',
		'L_ACTION' => $LANG['forum_cut_subject'] . ' : ' . $topic['title'],
		'L_FORUM_INDEX' => $LANG['forum_index'],
		'L_CAT' => $LANG['category'],
		'L_TITLE' => $LANG['title'],
		'L_DESC' => $LANG['description'],
		'L_MESSAGE' => $LANG['message'],
		'L_SUBMIT' => $LANG['forum_cut_subject'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'L_POLL' => $LANG['poll'],
		'L_OPEN_MENU_POLL' => $LANG['open_menu_poll'],
		'L_QUESTION' => $LANG['question'],
		'L_ANSWERS' => $LANG['answers'],
		'L_POLL_TYPE' => $LANG['poll_type'],
		'L_SINGLE' => $LANG['simple_answer'],
		'L_MULTIPLE' => $LANG['multiple_answer']
	);

	if (empty($post_topic) && empty($preview_topic))
	{
		//Liste des choix des sondages => 20 maxi
		$nbr_poll_field = 0;
		for ($i = 0; $i < 5; $i++)
		{
			$tpl->assign_block_vars('answers_poll', array(
				'ID' => $i,
				'ANSWER' => ''
			));
			$nbr_poll_field++;
		}

		$tpl->put_all(array(
			'TITLE' => '',
			'DESC' => '',
			'CONTENTS' => FormatingHelper::unparse(stripslashes($msg['contents'])),
			'IDM' => $id_get_msg,
			'CHECKED_NORMAL' => 'checked="checked"',
			'SELECTED_SIMPLE' => 'checked="checked"',
			'NO_DISPLAY_POLL' => 'true',
			'NBR_POLL_FIELD' => $nbr_poll_field,
			'L_TYPE' => '* ' . $LANG['type'],
			'L_DEFAULT' => $LANG['default'],
			'L_POST_IT' => $LANG['forum_postit'],
			'L_ANOUNCE' => $LANG['forum_announce'],
			'C_FORUM_POST_TYPE' => true,
			'C_ADD_POLL_FIELD' => true
		));
	}
	elseif (!empty($preview_topic) && !empty($id_post_msg))
	{
		$title = retrieve(POST, 'title', '', TSTRING_UNCHANGE);
		$subtitle = retrieve(POST, 'desc', '', TSTRING_UNCHANGE);
		$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
		$question = retrieve(POST, 'question', '', TSTRING_UNCHANGE);
		$type = retrieve(POST, 'type', 0);

		$checked_normal = ($type == 0) ? 'checked="ckecked"' : '';
		$checked_postit = ($type == 1) ? 'checked="ckecked"' : '';
		$checked_annonce = ($type == 2) ? 'checked="ckecked"' : '';

		//Liste des choix des sondages => 20 maxi
		$nbr_poll_field = 0;
		for ($i = 0; $i < 20; $i++)
		{
			$answer = retrieve(POST, 'a'.$i, '', TSTRING_UNCHANGE);
			if (!empty($answer))
			{
				$tpl->assign_block_vars('answers_poll', array(
					'ID' => $i,
					'ANSWER' => $answer
				));
				$nbr_poll_field++;
			}
			elseif ($i <= 5) //On complète s'il y a moins de 5 réponses.
			{
				$tpl->assign_block_vars('answers_poll', array(
					'ID' => $i,
					'ANSWER' => ''
				));
				$nbr_poll_field++;
			}
		}

		//Type de réponses du sondage.
		$poll_type = retrieve(POST, 'poll_type', 0);

		$vars_tpl = array_merge($vars_tpl, array(
			'TITLE' => $title,
			'DESC' => stripslashes($subtitle),
			'CONTENTS' => $contents,
			'QUESTION' => $question,
			'IDM' => $id_post_msg,
			'DATE' => $LANG['on'] . ' ' . Date::to_format(Date::DATE_NOW, Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
			'CONTENTS_PREVIEW' => FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($contents))),
			'CHECKED_NORMAL' => $checked_normal,
			'CHECKED_POSTIT' => $checked_postit,
			'CHECKED_ANNONCE' => $checked_annonce,
			'SELECTED_SIMPLE' => ($poll_type == 0) ? 'checked="ckecked"' : '',
			'SELECTED_MULTIPLE' => ($poll_type == 1) ? 'checked="ckecked"' : '',
			'NO_DISPLAY_POLL' => !empty($question) ? 'false' : 'true',
			'NBR_POLL_FIELD' => $nbr_poll_field,
			'C_FORUM_PREVIEW_MSG' => true,
			'C_ADD_POLL_FIELD' => $nbr_poll_field <= 18,
			'C_FORUM_POST_TYPE' => true,
			'L_PREVIEW' => $LANG['preview'],
			'L_TYPE' => '* ' . $LANG['type'],
			'L_DEFAULT' => $LANG['default'],
			'L_POST_IT' => $LANG['forum_postit'],
			'L_ANOUNCE' => $LANG['forum_announce']
		));
	}

	//Listes les utilisateurs en lignes.
	list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script LIKE '" ."/forum/%'");

	$vars_tpl = array_merge($vars_tpl, array(
		'C_USER_CONNECTED' => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
		'TOTAL_ONLINE' => $total_online,
		'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
		'ADMIN' => $total_admin,
		'MODO' => $total_modo,
		'MEMBER' => $total_member,
		'GUEST' => $total_visit,
		'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
		'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
		'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
		'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
		'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
		'L_AND' => $LANG['and'],
		'L_ONLINE' => strtolower($LANG['online'])
	));

	$tpl->put_all($vars_tpl);
	$tpl_top->put_all($vars_tpl);
	$tpl_bottom->put_all($vars_tpl);
		
	$tpl->put('forum_top', $tpl_top);
	$tpl->put('forum_bottom', $tpl_bottom);
		
	$tpl->display();
}
elseif (!empty($id_post_msg) && !empty($post_topic)) //Scindage du topic
{
	try {
		$msg = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_msg', array('idtopic', 'user_id', 'timestamp', 'contents'), 'WHERE id=:id', array('id' => $id_post_msg));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}
	
	try {
		$topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array('idcat', 'title', 'last_user_id', 'last_msg_id', 'last_timestamp'), 'WHERE id=:id', array('id' => $msg['idtopic']));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}
	
	$to = retrieve(POST, 'to', 0); //Catégorie cible.

	if (!ForumAuthorizationsService::check_authorizations($topic['idcat'])->moderation()) //Accès en édition
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	$id_first = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_msg", 'MIN(id)', 'WHERE idtopic = :id', array('id' => $msg['idtopic']));
	//Scindage du premier message interdite.
	if ($id_first == $id_post_msg)
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), 
            $LANG['e_unable_cut_forum']);
        DispatchManager::redirect($controller);
	}
	
	$category_to = ForumService::get_categories_manager()->get_categories_cache()->get_category($to);
	if (!empty($to) && $category_to->get_id_parent() != Category::ROOT_CATEGORY)
	{
		$title = retrieve(POST, 'title', '');
		$subtitle = retrieve(POST, 'desc', '');
		$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
		$type = retrieve(POST, 'type', 0);

		//Requête de "scindage" du topic.
		if (!empty($to) && !empty($contents) && !empty($title))
		{
			//Instanciation de la class du forum.
			$Forumfct = new Forum();

			$last_topic_id = $Forumfct->Cut_topic($id_post_msg, $msg['idtopic'], $topic['idcat'], $to, $title, $subtitle, $contents, $type, $msg['user_id'], $topic['last_user_id'], $topic['last_msg_id']); //Scindement du topic

			//Ajout d'un sondage en plus du topic.
			$question = retrieve(POST, 'question', '');
			if (!empty($question))
			{
				$poll_type = retrieve(POST, 'poll_type', 0);
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
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), 
            $LANG['e_incomplete']);
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