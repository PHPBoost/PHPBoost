<?php
/*##################################################
 *                                post.php
 *                            -------------------
 *   begin                : October 27, 2005
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

$id_get = retrieve(GET, 'id', 0);

//Existance de la catégorie.
if (!isset($CAT_FORUM[$id_get]) || $CAT_FORUM[$id_get]['aprob'] == 0 || $CAT_FORUM[$id_get]['level'] == 0)
{
	$controller = PHPBoostErrors::unexisting_category();
    DispatchManager::redirect($controller);
}

if ($User->get_attribute('user_readonly') > time()) //Lecture seule.
{
	$controller = PHPBoostErrors::user_in_read_only();
    DispatchManager::redirect($controller);
}

//Récupération de la barre d'arborescence.
$Bread_crumb->add($CONFIG_FORUM['forum_name'], 'index.php' . SID);
foreach ($CAT_FORUM as $idcat => $array_info_cat)
{
	if ($CAT_FORUM[$id_get]['id_left'] > $array_info_cat['id_left'] && $CAT_FORUM[$id_get]['id_right'] < $array_info_cat['id_right'] && $array_info_cat['level'] < $CAT_FORUM[$id_get]['level'])
		$Bread_crumb->add($array_info_cat['name'], ($array_info_cat['level'] == 0) ? url('index.php?id=' . $idcat, 'cat-' . $idcat . '+' . Url::encode_rewrite($array_info_cat['name']) . '.php') : 'forum' . url('.php?id=' . $idcat, '-' . $idcat . '+' . Url::encode_rewrite($array_info_cat['name']) . '.php'));
}
if (!empty($CAT_FORUM[$id_get]['name'])) //Nom de la catégorie courante.
	$Bread_crumb->add($CAT_FORUM[$id_get]['name'], 'forum' . url('.php?id=' . $id_get, '-' . $id_get . '+' . Url::encode_rewrite($CAT_FORUM[$id_get]['name']) . '.php'));
$Bread_crumb->add($LANG['title_post'], '');
define('TITLE', $LANG['title_forum']);
require_once('../kernel/header.php');

$new_get = retrieve(GET, 'new', '');
$idt_get = retrieve(GET, 'idt', '');
$error_get = retrieve(GET, 'error', '');
$previs = retrieve(POST, 'prw', false); //Prévisualisation des messages.
$post_topic = retrieve(POST, 'post_topic', false);
$preview_topic = retrieve(POST, 'prw_t', '');

//Niveau d'autorisation de la catégorie
if ($User->check_auth($CAT_FORUM[$id_get]['auth'], READ_CAT_FORUM))
{
	$Forumfct = new Forum();

	//Mod anti-flood
	$check_time = (ContentManagementConfig::load()->is_anti_flood_enabled() && $User->get_attribute('user_id') != -1) ? $Sql->query("SELECT MAX(timestamp) as timestamp FROM " . PREFIX . "forum_msg WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__) : false;

	//Affichage de l'arborescence des catégories.
	$i = 0;
	$forum_cats = '';
	$Bread_crumb->remove_last();
	foreach ($Bread_crumb->get_links() as $key => $array)
	{
		if ($i == 2)
			$forum_cats .= '<a href="' . $array[1] . '">' . $array[0] . '</a>';
		elseif ($i > 2)
			$forum_cats .= ' &raquo; <a href="' . $array[1] . '">' . $array[0] . '</a>';
		$i++;
	}

	if ($previs) //Prévisualisation des messages
	{
		if (!$User->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM))
			AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');

		$topic = $Sql->query_array(PREFIX . 'forum_topics', 'idcat', 'title', 'subtitle', "WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);

		if (empty($topic['idcat'])) //Topic inexistant.
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                $LANG['e_unexist_topic_forum']);
            DispatchManager::redirect($controller);
		}

		$Template->set_filenames(array(
			'edit_msg'=> 'forum/forum_edit_msg.tpl',
			'forum_top'=> 'forum/forum_top.tpl',
			'forum_bottom'=> 'forum/forum_bottom.tpl'
		));

		$contents = retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED);
		$post_update = retrieve(POST, 'p_update', '', TSTRING_UNCHANGE);

		$update = !empty($post_update) ? $post_update : url('?new=n_msg&amp;idt=' . $idt_get . '&amp;id=' . $id_get . '&amp;token=' . $Session->get_token());
		$submit = !empty($post_update) ? $LANG['update'] : $LANG['submit'];

		$Template->put_all(array(
			'THEME' => get_utheme(),
			'LANG' => get_ulang(),
			'P_UPDATE' => $post_update,
			'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
			'SID' => SID,
			'KERNEL_EDITOR' => display_editor(),
			'DESC' => $topic['subtitle'],
			'CONTENTS' => $contents,
			'DATE' => $LANG['on'] . ' ' . gmdate_format('date_format'),
			'CONTENTS_PREVIEW' => FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($contents))),
			'C_FORUM_PREVIEW_MSG' => true,
			'U_ACTION' => 'post.php' . $update . '&amp;token=' . $Session->get_token(),
			'U_FORUM_CAT' => $forum_cats,
			'U_TITLE_T' => '<a href="topic' . url('.php?id=' . $idt_get, '-' . $idt_get . '.php') . '">' . ucfirst($topic['title']) . '</a>',
			'L_REQUIRE' => $LANG['require'],
			'L_REQUIRE_TEXT' => $LANG['require_text'],
			'L_REQUIRE_TITLE' => $LANG['require_title'],
			'L_FORUM_INDEX' => $LANG['forum_index'],
			'L_EDIT_MESSAGE' => $LANG['preview'],
			'L_MESSAGE' => $LANG['message'],
			'L_SUBMIT' => $submit,
			'L_PREVIEW' => $LANG['preview'],
			'L_RESET' => $LANG['reset']
		));

		$Template->pparse('edit_msg');
	}
	elseif ($new_get === 'topic' && empty($error_get)) //Nouveau topic.
	{
		if ($post_topic && !empty($id_get))
		{
			$is_modo = $User->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM);
			if (!$User->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM))
				AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');

			if ($is_modo)
				$type = retrieve(POST, 'type', 0);
			else
				$type = 0;

			//Verrouillé?
			$check_status = $CAT_FORUM[$id_get]['status'];
			//Déverrouillé pour admin et modo dans tous les cas
			if ($is_modo)
				$check_status = 1;

			$contents = retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED);
			$title = retrieve(POST, 'title', '');
			$subtitle = retrieve(POST, 'desc', '');

			//Mod anti Flood
			if ($check_time !== false && $check_status != 0)
			{
				$delay_flood = ContentManagementConfig::load()->get_anti_flood_duration(); //On recupère le delai de flood.
				$delay_expire = time() - $delay_flood; //On calcul la fin du delai.

				//Droit de flooder?.
				if ($check_time >= $delay_expire && !$User->check_auth($CONFIG_FORUM['auth'], FLOOD_FORUM)) //Flood
					AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=flood_t&id=' . $id_get, '', '&') . '#errorh');
			}

			if ($check_status == 1)
			{
				if (!empty($contents) && !empty($title)) //Insertion nouveau topic.
				{
					list($last_topic_id, $last_msg_id) = $Forumfct->Add_topic($id_get, $title, $subtitle, $contents, $type); //Insertion nouveau topic.

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

					AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $last_topic_id, '-' . $last_topic_id . '.php', '&') . '#m' . $last_msg_id);
				}
				else
					AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=incomplete_t&id=' . $id_get, '', '&') . '#errorh');
			}
			else //Verrouillé
				AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=c_locked&id=' . $id_get, '', '&') . '#errorh');
		}
		elseif (!empty($preview_topic) && !empty($id_get))
		{
			if (!$User->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM))
				AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');

			$Template->set_filenames(array(
				'forum_post'=> 'forum/forum_post.tpl',
				'forum_top'=> 'forum/forum_top.tpl',
				'forum_bottom'=> 'forum/forum_bottom.tpl'
			));

			$title = retrieve(POST, 'title', '', TSTRING_UNCHANGE);
			$subtitle = retrieve(POST, 'desc', '', TSTRING_UNCHANGE);
			$contents = retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED);
			$question = retrieve(POST, 'question', '', TSTRING_UNCHANGE);

			$is_modo = $User->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM);
			$type = retrieve(POST, 'type', 0);

			if (!$is_modo)
				$type = ( $type == 1 || $type == 0 ) ? $type : 0;
			else
			{
				$Template->put_all(array(
					'C_FORUM_POST_TYPE' => true,
					'CHECKED_NORMAL' => (($type == '0') ? 'checked="ckecked"' : ''),
					'CHECKED_POSTIT' => (($type == '1') ? 'checked="ckecked"' : ''),
					'CHECKED_ANNONCE' => (($type == '2') ? 'checked="ckecked"' : ''),
					'L_TYPE' => '* ' . $LANG['type'],
					'L_DEFAULT' => $LANG['default'],
					'L_POST_IT' => $LANG['forum_postit'],
					'L_ANOUNCE' => $LANG['forum_announce']
				));
			}

			//Liste des choix des sondages => 20 maxi
			$nbr_poll_field = 0;
			for ($i = 0; $i < 20; $i++)
			{
				$answer = retrieve(POST, 'a'.$i, '');
				if (!empty($answer))
				{
					$Template->assign_block_vars('answers_poll', array(
						'ID' => $i,
						'ANSWER' => stripslashes($answer)
					));
					$nbr_poll_field++;
				}
			}
			for ($i = $nbr_poll_field; $i < 5; $i++) //On complète s'il y a moins de 5 réponses.
			{
				$Template->assign_block_vars('answers_poll', array(
					'ID' => $i,
					'ANSWER' => ''
				));
				$nbr_poll_field++;
			}

			//Type de réponses du sondage.
			$poll_type = retrieve(POST, 'poll_type', 0);

			$Template->put_all(array(
				'THEME' => get_utheme(),
				'LANG' => get_ulang(),
				'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
				'SID' => SID,
				'TITLE' => $title,
				'DESC' => $subtitle,
				'CONTENTS' => $contents,
				'KERNEL_EDITOR' => display_editor(),
				'POLL_QUESTION' => $question,
				'IDTOPIC' => 0,
				'SELECTED_SIMPLE' => ($poll_type == 0) ? 'checked="ckecked"' : '',
				'SELECTED_MULTIPLE' => ($poll_type == 1) ? 'checked="ckecked"' : '',
				'NO_DISPLAY_POLL' => 'true',
				'NBR_POLL_FIELD' => $nbr_poll_field,
				'DATE' => $LANG['on'] . ' ' . gmdate_format('date_format'),
				'CONTENTS_PREVIEW' => FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($contents))),
				'C_FORUM_PREVIEW_MSG' => true,
				'C_ADD_POLL_FIELD' => ($nbr_poll_field <= 19) ? true : false,
				'U_ACTION' => 'post.php' . url('?new=topic&amp;id=' . $id_get . '&amp;token=' . $Session->get_token()),
				'U_FORUM_CAT' => $forum_cats,
				'U_TITLE_T' => '<a href="post' . url('.php?new=topic&amp;id=' . $id_get) . '">' . $title . '</a>',
				'L_ACTION' => $LANG['forum_edit_subject'],
				'L_REQUIRE' => $LANG['require'],
				'L_REQUIRE_TEXT' => $LANG['require_text'],
				'L_REQUIRE_TITLE' => $LANG['require_title'],
				'L_REQUIRE_TITLE_POLL' => $LANG['require_title_poll'],
				'L_FORUM_INDEX' => $LANG['forum_index'],
				'L_TITLE' => $LANG['title'],
				'L_DESC' => $LANG['description'],
				'L_MESSAGE' => $LANG['message'],
				'L_SUBMIT' => $LANG['submit'],
				'L_PREVIEW' => $LANG['preview'],
				'L_RESET' => $LANG['reset'],
				'L_POLL' => $LANG['poll'],
				'L_OPEN_MENU_POLL' => $LANG['open_menu_poll'],
				'L_QUESTION' => $LANG['question'],
				'L_POLL_TYPE' => $LANG['poll_type'],
				'L_ANSWERS' => $LANG['answers'],
				'L_SINGLE' => $LANG['simple_answer'],
				'L_MULTIPLE' => $LANG['multiple_answer']
			));

			$Template->pparse('forum_post');
		}
		else
		{
			if (!$User->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM))
				AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');

			$Template->set_filenames(array(
				'forum_post'=> 'forum/forum_post.tpl',
				'forum_top'=> 'forum/forum_top.tpl',
				'forum_bottom'=> 'forum/forum_bottom.tpl'
			));

			if ($User->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM))
			{
				$Template->put_all(array(
					'C_FORUM_POST_TYPE' => true,
					'CHECKED_NORMAL' => 'checked="ckecked"',
					'L_TYPE' => '* ' . $LANG['type'],
					'L_DEFAULT' => $LANG['default'],
					'L_POST_IT' => $LANG['forum_postit'],
					'L_ANOUNCE' => $LANG['forum_announce']
				));
			}

			//Liste des choix des sondages => 20 maxi
			$nbr_poll_field = 0;
			for ($i = 0; $i < 5; $i++)
			{
				$Template->assign_block_vars('answers_poll', array(
					'ID' => $i,
					'ANSWER' => ''
				));
				$nbr_poll_field++;
			}

			$Template->put_all(array(
				'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
				'SID' => SID,
				'TITLE' => '',
				'DESC' => '',
				'SELECTED_SIMPLE' => 'checked="ckecked"',
				'IDTOPIC' => 0,
				'KERNEL_EDITOR' => display_editor(),
				'NO_DISPLAY_POLL' => 'true',
				'NBR_POLL_FIELD' => $nbr_poll_field,
				'C_ADD_POLL_FIELD' => true,
				'U_ACTION' => 'post.php' . url('?new=topic&amp;id=' . $id_get . '&amp;token=' . $Session->get_token()),
				'U_FORUM_CAT' => $forum_cats,
				'U_TITLE_T' => '<a href="post' . url('.php?new=topic&amp;id=' . $id_get) . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/post.png" alt="" class="valign_middle" /></a>',
				'L_ACTION' => $LANG['forum_new_subject'],
				'L_REQUIRE' => $LANG['require'],
				'L_REQUIRE_TEXT' => $LANG['require_text'],
				'L_REQUIRE_TITLE' => $LANG['require_title'],
				'L_REQUIRE_TITLE_POLL' => $LANG['require_title_poll'],
				'L_FORUM_INDEX' => $LANG['forum_index'],
				'L_TITLE' => $LANG['title'],
				'L_DESC' => $LANG['description'],
				'L_MESSAGE' => $LANG['message'],
				'L_SUBMIT' => $LANG['submit'],
				'L_PREVIEW' => $LANG['preview'],
				'L_RESET' => $LANG['reset'],
				'L_POLL' => $LANG['poll'],
				'L_OPEN_MENU_POLL' => $LANG['open_menu_poll'],
				'L_QUESTION' => $LANG['question'],
				'L_POLL_TYPE' => $LANG['poll_type'],
				'L_ANSWERS' => $LANG['answers'],
				'L_SINGLE' => $LANG['simple_answer'],
				'L_MULTIPLE' => $LANG['multiple_answer']
			));

			$Template->pparse('forum_post');
		}
	}
	elseif ($new_get === 'n_msg' && empty($error_get)) //Nouveau message
	{
		if (!$User->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM))
			AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');

		//Verrouillé?
		$topic = $Sql->query_array(PREFIX . 'forum_topics', 'idcat', 'title', 'nbr_msg', 'last_user_id', 'status', "WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);
		if (empty($topic['idcat']))
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                $LANG['e_topic_lock_forum']);
            DispatchManager::redirect($controller);
		}

		$is_modo = $User->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM);
		//Catégorie verrouillée?
		$check_status = $CAT_FORUM[$id_get]['status'];
		//Déverrouillé pour admin et modo dans tous les cas
		if ($is_modo)
			$check_status = 1;

		if ($check_status == 0) //Verrouillée
			AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=c_locked&id=' . $id_get, '', '&') . '#errorh');

		//Mod anti Flood
		if ($check_time !== false)
		{
			$delay_expire = time() - ContentManagementConfig::load()->get_anti_flood_duration(); //On calcul la fin du delai.
			//Droit de flooder?
			if ($check_time >= $delay_expire && !$User->check_auth($CONFIG_FORUM['auth'], FLOOD_FORUM)) //Ok
				AppContext::get_response()->redirect( url(HOST . SCRIPT . '?error=flood&id=' . $id_get . '&idt=' . $idt_get, '', '&') . '#errorh');
		}

		$contents = retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED);

		//Si le topic n'est pas vérrouilé on ajoute le message.
		if ($topic['status'] != 0 || $is_modo)
		{
			if (!empty($contents) && !empty($idt_get) && empty($update)) //Nouveau message.
			{
				$last_page = ceil( ($topic['nbr_msg'] + 1) / $CONFIG_FORUM['pagination_msg'] );
				$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
				$last_page = ($last_page > 1) ? '&pt=' . $last_page : '';

				$last_msg_id = $Forumfct->Add_msg($idt_get, $topic['idcat'], $contents, $topic['title'], $last_page, $last_page_rewrite);

				//Redirection après post.
				AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $idt_get . $last_page, '-' . $idt_get . $last_page_rewrite . '.php', '&') . '#m' . $last_msg_id);
			}
			else
				AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=incomplete&id=' . $id_get . '&idt=' . $idt_get, '', '&') . '#errorh');
		}
		else
			AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=locked&id=' . $id_get . '&idt=' . $idt_get, '', '&') . '#errorh');
	}
	elseif ($new_get === 'msg' && empty($error_get)) //Edition d'un message/topic.
	{
		if (!$User->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM))
			AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');

		$id_m = retrieve(GET, 'idm', 0);
		$update = retrieve(GET, 'update', false);
		$id_first = $Sql->query("SELECT MIN(id) FROM " . PREFIX . "forum_msg WHERE idtopic = '" . $idt_get . "'", __LINE__, __FILE__);
		$topic = $Sql->query_array(PREFIX . 'forum_topics', 'title', 'subtitle', 'type', 'user_id', 'display_msg', "WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);

		if (empty($id_get) || empty($id_first)) //Topic/message inexistant.
		{
            $controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                $LANG['e_unexist_topic_forum']);
            DispatchManager::redirect($controller);
		}

		$is_modo = $User->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM);

		//Edition du topic complet
		if ($id_first == $id_m)
		{
			//User_id du message correspondant à l'utilisateur connecté => autorisation.
			$user_id_msg = $Sql->query("SELECT user_id FROM " . PREFIX . "forum_msg WHERE id = '" . $id_m . "'",  __LINE__, __FILE__);
			$check_auth = false;
			if ($user_id_msg == $User->get_attribute('user_id'))
				$check_auth = true;
			elseif ($is_modo)
				$check_auth = true;

			if (!$check_auth)
			{
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}

			if ($update && $post_topic)
			{
				$title = retrieve(POST, 'title', '');
				$subtitle = retrieve(POST, 'desc', '');
				$contents = retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED);
				$type = $is_modo ? retrieve(POST, 'type', 0) : 0;

				if (!empty($title) && !empty($contents))
				{
					$Forumfct->Update_topic($idt_get, $id_m, $title, $subtitle, $contents, $type, $user_id_msg); //Mise à jour du topic.

					//Mise à jour du sondage en plus du topic.
					$del_poll = retrieve(POST, 'del_poll', false);
					$question = retrieve(POST, 'question', '');
					if (!empty($question) && !$del_poll) //Enregistrement du sondage.
					{
						//Mise à jour si le sondage existe, sinon création.
						$check_poll = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "forum_poll WHERE idtopic = '" . $idt_get . "'",  __LINE__, __FILE__);

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

						if ($check_poll == 1) //Mise à jour.
							$Forumfct->Update_poll($idt_get, $question, $answers, $poll_type);
						elseif ($check_poll == 0) //Ajout du sondage.
							$Forumfct->Add_poll($idt_get, $question, $answers, $nbr_votes, $poll_type);
					}
					elseif ($del_poll && $User->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM)) //Suppression du sondage, admin et modo seulement biensûr...
						$Forumfct->Del_poll($idt_get);

					//Redirection après post.
					AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $idt_get, '-' . $idt_get . '.php', '&'));
				}
				else
					AppContext::get_response()->redirect('/forum/post' . url('.php?new=msg&idm=' . $id_m . '&id=' . $id_get . '&idt=' . $idt_get . '&errore=incomplete_t', '', '&') . '#errorh');
			}
			elseif (!empty($preview_topic))
			{
				$Template->set_filenames(array(
					'forum_post'=> 'forum/forum_post.tpl',
					'forum_top'=> 'forum/forum_top.tpl',
					'forum_bottom'=> 'forum/forum_bottom.tpl'
				));

				$title = retrieve(POST, 'title', '', TSTRING_UNCHANGE);
				$subtitle = retrieve(POST, 'desc', '', TSTRING_UNCHANGE);
				$contents = retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED);
				$question = retrieve(POST, 'question', '', TSTRING_UNCHANGE);

				$type = retrieve(POST, 'type', 0);
				if (!$is_modo)
					$type = ($type == 1 || $type == 0) ? $type : 0;
				else
				{
					$Template->put_all(array(
						'C_FORUM_POST_TYPE' => true,
						'CHECKED_NORMAL' => (($type == 0) ? 'checked="ckecked"' : ''),
						'CHECKED_POSTIT' => (($type == 1) ? 'checked="ckecked"' : ''),
						'CHECKED_ANNONCE' => (($type == 2) ? 'checked="ckecked"' : ''),
						'L_TYPE' => '* ' . $LANG['type'],
						'L_DEFAULT' => $LANG['default'],
						'L_POST_IT' => $LANG['forum_postit'],
						'L_ANOUNCE' => $LANG['forum_announce']
					));
				}

				//Liste des choix des sondages => 20 maxi
				$nbr_poll_field = 0;
				for ($i = 0; $i < 20; $i++)
				{
					$answer = retrieve(POST, 'a'.$i, '');
					if (!empty($anwser))
					{
						$Template->assign_block_vars('answers_poll', array(
							'ID' => $i,
							'ANSWER' => stripslashes($anwser)
						));
						$nbr_poll_field++;
					}
				}
				for ($i = $nbr_poll_field; $i < 5; $i++) //On complète s'il y a moins de 5 réponses.
				{
					$Template->assign_block_vars('answers_poll', array(
						'ID' => $i,
						'ANSWER' => ''
					));
					$nbr_poll_field++;
				}

				//Type de réponses du sondage.
				$poll_type = retrieve(POST, 'poll_type', 0);

				$Template->put_all(array(
					'THEME' => get_utheme(),
					'LANG' => get_ulang(),
					'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
					'SID' => SID,
					'TITLE' => $title,
					'DESC' => $subtitle,
					'CONTENTS' => $contents,
					'KERNEL_EDITOR' => display_editor(),
					'POLL_QUESTION' => $question,
					'IDTOPIC' => 0,
					'SELECTED_SIMPLE' => 'checked="ckecked"',
					'NO_DISPLAY_POLL' => !empty($question) ? 'false' : 'true',
					'NBR_POLL_FIELD' => $nbr_poll_field,
					'SELECTED_SIMPLE' => ($poll_type == 0) ? 'checked="ckecked"' : '',
					'SELECTED_MULTIPLE' => ($poll_type == 1) ? 'checked="ckecked"' : '',
					'DATE' => $LANG['on'] . ' ' . gmdate_format('date_format'),
					'CONTENTS_PREVIEW' => FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($contents))),
					'C_FORUM_PREVIEW_MSG' => true,
					'C_DELETE_POLL' => ($is_modo) ? true : false, //Suppression d'un sondage => modo uniquement.
					'C_ADD_POLL_FIELD' => ($nbr_poll_field <= 19) ? true : false,
					'U_ACTION' => 'post.php' . url('?update=1&amp;new=msg&amp;id=' . $id_get . '&amp;idt=' . $idt_get . '&amp;idm=' . $id_m . '&amp;token=' . $Session->get_token()),
					'U_FORUM_CAT' => '<a href="forum' . url('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $CAT_FORUM[$id_get]['name'] . '</a>',
					'U_TITLE_T' => '<a href="topic' . url('.php?id=' . $idt_get, '-' . $idt_get . '.php') . '">' . $title . '</a>',
					'L_ACTION' => $LANG['forum_edit_subject'],
					'L_REQUIRE' => $LANG['require'],
					'L_REQUIRE_TEXT' => $LANG['require_text'],
					'L_REQUIRE_TITLE' => $LANG['require_title'],
					'L_REQUIRE_TITLE_POLL' => $LANG['require_title_poll'],
					'L_FORUM_INDEX' => $LANG['forum_index'],
					'L_TITLE' => $LANG['title'],
					'L_DESC' => $LANG['description'],
					'L_MESSAGE' => $LANG['message'],
					'L_SUBMIT' => $LANG['update'],
					'L_PREVIEW' => $LANG['preview'],
					'L_RESET' => $LANG['reset'],
					'L_POLL' => $LANG['poll'],
					'L_OPEN_MENU_POLL' => $LANG['open_menu_poll'],
					'L_QUESTION' => $LANG['question'],
					'L_POLL_TYPE' => $LANG['poll_type'],
					'L_ANSWERS' => $LANG['answers'],
					'L_SINGLE' => $LANG['simple_answer'],
					'L_MULTIPLE' => $LANG['multiple_answer'],
					'L_DELETE_POLL' => $LANG['delete_poll']
				));

				$Template->pparse('forum_post');
			}
			else
			{
				$Template->set_filenames(array(
					'forum_post'=> 'forum/forum_post.tpl',
					'forum_top'=> 'forum/forum_top.tpl',
					'forum_bottom'=> 'forum/forum_bottom.tpl'
				));

				$contents = $Sql->query("SELECT contents FROM " . PREFIX . "forum_msg WHERE id = '" . $id_first . "'", __LINE__, __FILE__);

				//Gestion des erreurs à l'édition.
				$get_error_e = retrieve(GET, 'errore', '');
				if ($get_error_e == 'incomplete_t')
					$Template->put('message_helper', MessageHelper::display($LANG['e_incomplete'], E_USER_NOTICE));

				if ($is_modo)
				{
					$Template->put_all(array(
						'C_FORUM_POST_TYPE' => true,
						'CHECKED_NORMAL' => (($topic['type'] == '0') ? 'checked="ckecked"' : ''),
						'CHECKED_POSTIT' => (($topic['type'] == '1') ? 'checked="ckecked"' : ''),
						'CHECKED_ANNONCE' => (($topic['type'] == '2') ? 'checked="ckecked"' : ''),
						'L_TYPE' => '* ' . $LANG['type'],
						'L_DEFAULT' => $LANG['default'],
						'L_POST_IT' => $LANG['forum_postit'],
						'L_ANOUNCE' => $LANG['forum_announce']
					));
				}

				//Récupération des infos du sondage associé si il existe
				$poll = $Sql->query_array(PREFIX . 'forum_poll', 'question', 'answers', 'votes', 'type', "WHERE idtopic = '" . $idt_get . "'", __LINE__, __FILE__);
				$array_answer = explode('|', $poll['answers']);
				$array_votes = explode('|', $poll['votes']);

				$TmpTemplate = new FileTemplate('forum/forum_generic_results.tpl');
				$module_data_path = $TmpTemplate->get_pictures_data_path();

				//Affichage du lien pour changer le display_msg du topic et autorisation d'édition.
				if ($CONFIG_FORUM['activ_display_msg'] == 1 && ($is_modo || $User->get_attribute('user_id') == $topic['user_id']))
				{
					$img_display = $topic['display_msg'] ? 'msg_display2.png' : 'msg_display.png';
					$Template->put_all(array(
						'C_DISPLAY_MSG' => true,
						'ICON_DISPLAY_MSG' => $CONFIG_FORUM['icon_activ_display_msg'] ? '<img src="' . $module_data_path . '/images/' . $img_display . '" alt="" class="valign_middle" />' : '',
						'ICON_DISPLAY_MSG2' => $CONFIG_FORUM['icon_activ_display_msg'] ? '<img src="' . $module_data_path . '/images/' . $img_display . '" alt="" class="valign_middle" id="forum_change_img" />' : '',
						'L_EXPLAIN_DISPLAY_MSG_DEFAULT' => $topic['display_msg'] ? $CONFIG_FORUM['explain_display_msg_bis'] : $CONFIG_FORUM['explain_display_msg'],
						'L_EXPLAIN_DISPLAY_MSG' => $CONFIG_FORUM['explain_display_msg'],
						'L_EXPLAIN_DISPLAY_MSG_BIS' => $CONFIG_FORUM['explain_display_msg_bis'],
						'U_ACTION_MSG_DISPLAY' => url('.php?msg_d=1&amp;id=' . $id_get . '&amp;token=' . $Session->get_token())
					));
				}

				//Liste des choix des sondages => 20 maxi
				$nbr_poll_field = 0;
				foreach ($array_answer as $key => $answer)
				{
					if (!empty($answer))
					{
						$nbr_votes = isset($array_votes[$key]) ? $array_votes[$key] : 0;
						$Template->assign_block_vars('answers_poll', array(
							'ID' => $nbr_poll_field,
							'ANSWER' => $answer,
							'NBR_VOTES' => $nbr_votes,
							'L_VOTES' => ($nbr_votes > 1) ? $LANG['votes'] : $LANG['vote']
						));
						$nbr_poll_field++;
					}
				}
				for ($i = $nbr_poll_field; $i < 5; $i++) //On complète s'il y a moins de 5 réponses.
				{
					$Template->assign_block_vars('answers_poll', array(
						'ID' => $i,
						'ANSWER' => ''
					));
					$nbr_poll_field++;
				}

				$Template->put_all(array(
					'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
					'SID' => SID,
					'TITLE' => $topic['title'],
					'DESC' => $topic['subtitle'],
					'CONTENTS' => FormatingHelper::unparse($contents),
					'POLL_QUESTION' => !empty($poll['question']) ? $poll['question'] : '',
					'SELECTED_SIMPLE' => 'checked="ckecked"',
					'MODULE_DATA_PATH' => $module_data_path,
					'IDTOPIC' => $idt_get,
					'KERNEL_EDITOR' => display_editor(),
					'NBR_POLL_FIELD' => $nbr_poll_field,
					'NO_DISPLAY_POLL' => !empty($poll['question']) ? 'false' : 'true',
					'C_DELETE_POLL' => ($is_modo) ? true : false, //Suppression d'un sondage => modo uniquement.
					'C_ADD_POLL_FIELD' => ($nbr_poll_field <= 19) ? true : false,
					'U_ACTION' => 'post.php' . url('?update=1&amp;new=msg&amp;id=' . $id_get . '&amp;idt=' . $idt_get . '&amp;idm=' . $id_m . '&amp;token=' . $Session->get_token()),
					'U_FORUM_CAT' => '<a href="forum' . url('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $CAT_FORUM[$id_get]['name'] . '</a>',
					'U_TITLE_T' => '<a href="topic' . url('.php?id=' . $idt_get, '-' . $idt_get . '.php') . '">' . $topic['title'] . '</a>',
					'L_ACTION' => $LANG['forum_edit_subject'],
					'L_REQUIRE' => $LANG['require'],
					'L_REQUIRE_TEXT' => $LANG['require_text'],
					'L_REQUIRE_TITLE' => $LANG['require_title'],
					'L_REQUIRE_TITLE_POLL' => $LANG['require_title_poll'],
					'L_FORUM_INDEX' => $LANG['forum_index'],
					'L_TITLE' => $LANG['title'],
					'L_DESC' => $LANG['description'],
					'L_MESSAGE' => $LANG['message'],
					'L_SUBMIT' => $LANG['update'],
					'L_PREVIEW' => $LANG['preview'],
					'L_RESET' => $LANG['reset'],
					'L_POLL' => $LANG['poll'],
					'L_OPEN_MENU_POLL' => $LANG['open_menu_poll'],
					'L_QUESTION' => $LANG['question'],
					'L_POLL_TYPE' => $LANG['poll_type'],
					'L_ANSWERS' => $LANG['answers'],
					'L_SINGLE' => $LANG['simple_answer'],
					'L_MULTIPLE' => $LANG['multiple_answer'],
					'L_DELETE_POLL' => $LANG['delete_poll']
				));

				//Type de réponses du sondage.
				if (isset($poll['type']) && $poll['type'] == '0')
				{
					$Template->put_all(array(
						'SELECTED_SIMPLE' => 'checked="ckecked"'
					));
				}
				elseif (isset($poll['type']) && $poll['type'] == '1')
				{
					$Template->put_all(array(
						'SELECTED_MULTIPLE' => 'checked="ckecked"'
					));
				}

				$Template->pparse('forum_post');
			}
		}
		//Sinon on édite simplement le message
		elseif ($id_m > $id_first)
		{
			//User_id du message correspondant à l'utilisateur connecté => autorisation.
			$user_id_msg = $Sql->query("SELECT user_id FROM " . PREFIX . "forum_msg WHERE id = '" . $id_m . "'", __LINE__, __FILE__);
			$check_auth = false;
			if ($user_id_msg == $User->get_attribute('user_id'))
				$check_auth = true;
			elseif ($is_modo)
				$check_auth = true;

			if (!$check_auth) //Non autorisé!
			{
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}

			if ($update && retrieve(POST, 'edit_msg', false))
			{
				$contents = retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED);
				if (!empty($contents))
				{
					$nbr_msg_before = $Forumfct->Update_msg($idt_get, $id_m, $contents, $user_id_msg);

					//Calcul de la page sur laquelle se situe le message.
					$msg_page = ceil( ($nbr_msg_before + 1) / $CONFIG_FORUM['pagination_msg'] );
					$msg_page_rewrite = ($msg_page > 1) ? '-' . $msg_page : '';
					$msg_page = ($msg_page > 1) ? '&pt=' . $msg_page : '';

					//Redirection après édition.
					AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $idt_get . $msg_page, '-' . $idt_get .  $msg_page_rewrite . '.php', '&') . '#m' . $id_m);
				}
				else
					AppContext::get_response()->redirect('/forum/post' . url('.php?new=msg&idm=' . $id_m . '&id=' . $id_get . '&idt=' . $idt_get . '&errore=incomplete', '', '&') . '#errorh');
			}
			else
			{
				$Template->set_filenames(array(
					'edit_msg'=> 'forum/forum_edit_msg.tpl',
					'forum_top'=> 'forum/forum_top.tpl',
					'forum_bottom'=> 'forum/forum_bottom.tpl'
				));

				$contents = $Sql->query("SELECT contents FROM " . PREFIX . "forum_msg WHERE id = '" . $id_m . "'", __LINE__, __FILE__);
				//Gestion des erreurs à l'édition.
				$get_error_e = retrieve(GET, 'errore', '');
				if ($get_error_e == 'incomplete')
					$Template->put('message_helper', MessageHelper::display($LANG['e_incomplete'], E_USER_NOTICE));

				$Template->put_all(array(
					'P_UPDATE' => url('?update=1&amp;new=msg&amp;id=' . $id_get . '&amp;idt=' . $idt_get . '&amp;idm=' . $id_m),
					'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
					'SID' => SID,
					'DESC' => $topic['subtitle'],
					'CONTENTS' => FormatingHelper::unparse($contents),
					'KERNEL_EDITOR' => display_editor(),
					'U_ACTION' => 'post.php' . url('?update=1&amp;new=msg&amp;id=' . $id_get . '&amp;idt=' . $idt_get . '&amp;idm=' . $id_m . '&amp;token=' . $Session->get_token()),
					'U_FORUM_CAT' => '<a href="forum' . url('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $CAT_FORUM[$id_get]['name'] . '</a>',
					'U_TITLE_T' => '<a href="topic' . url('.php?id=' . $idt_get, '-' . $idt_get . '.php') . '">' . $topic['title'] . '</a>',
					'L_REQUIRE' => $LANG['require'],
					'L_REQUIRE_TEXT' => $LANG['require_text'],
					'L_FORUM_INDEX' => $LANG['forum_index'],
					'L_EDIT_MESSAGE' => $LANG['edit_message'],
					'L_MESSAGE' => $LANG['message'],
					'L_SUBMIT' => $LANG['update'],
					'L_PREVIEW' => $LANG['preview'],
					'L_RESET' => $LANG['reset'],
				));

				$Template->pparse('edit_msg');
			}
		}
	}
	elseif (!empty($error_get) && (!empty($idt_get) || !empty($id_get)))
	{
		if (!empty($id_get) && !empty($idt_get) && ($error_get === 'flood' || $error_get === 'incomplete' || $error_get === 'locked'))
		{
			$topic = $Sql->query_array(PREFIX . 'forum_topics', 'idcat', 'title', 'subtitle', "WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);
			if (empty($topic['idcat'])) //Topic inexistant.
			{
				$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                    $LANG['e_unexist_topic_forum']);
                DispatchManager::redirect($controller);
			}

			$Template->set_filenames(array(
				'error_post'=> 'forum/forum_edit_msg.tpl',
				'forum_top'=> 'forum/forum_top.tpl',
				'forum_bottom'=> 'forum/forum_bottom.tpl'
			));

			//Gestion erreur.
			switch ($error_get)
			{
				case 'flood':
				$errstr = $LANG['e_flood'];
				$type = E_USER_WARNING;
				break;
				case 'incomplete':
				$errstr = $LANG['e_incomplete'];
				$type = E_USER_NOTICE;
				break;
				case 'locked':
				$errstr = $LANG['e_topic_lock_forum'];
				$type = E_USER_WARNING;
				break;
				default:
				$errstr = '';
			}
			if (!empty($errstr))
				$Template->put('message_helper', MessageHelper::display($errstr, $type));

			$Template->put_all(array(
				'P_UPDATE' => '',
				'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
				'SID' => SID,
				'DESC' => $topic['subtitle'],
				'KERNEL_EDITOR' => display_editor(),
				'U_ACTION' => 'post.php' . url('?new=n_msg&amp;idt=' . $idt_get . '&amp;id=' . $id_get . '&amp;token=' . $Session->get_token()),
				'U_FORUM_CAT' => '<a href="forum' . url('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $CAT_FORUM[$id_get]['name'] . '</a>',
				'U_TITLE_T' => '<a href="topic' . url('.php?id=' . $idt_get, '-' . $idt_get . '.php') . '">' . $topic['title'] . '</a>',
				'L_ACTION' => $LANG['respond'],
				'L_REQUIRE' => $LANG['require'],
				'L_REQUIRE_TEXT' => $LANG['require_text'],
				'L_FORUM_INDEX' => $LANG['forum_index'],
				'L_EDIT_MESSAGE' => $LANG['respond'],
				'L_MESSAGE' => $LANG['message'],
				'L_SUBMIT' => $LANG['submit'],
				'L_PREVIEW' => $LANG['preview'],
				'L_RESET' => $LANG['reset']
			));
		}
		elseif (!empty($id_get) && ($error_get === 'c_locked' || $error_get === 'c_write' || $error_get === 'incomplete_t' || $error_get === 'false_t'))
		{
			$Template->set_filenames(array(
				'error_post'=> 'forum/forum_post.tpl',
				'forum_top'=> 'forum/forum_top.tpl',
				'forum_bottom'=> 'forum/forum_bottom.tpl'
			));

			if ($User->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM))
			{
				$Template->put_all(array(
					'C_FORUM_POST_TYPE' => true,
					'CHECKED_NORMAL' => 'checked="ckecked"',
					'L_TYPE' => '* ' . $LANG['type'],
					'L_DEFAULT' => $LANG['default'],
					'L_POST_IT' => $LANG['forum_postit'],
					'L_ANOUNCE' => $LANG['forum_announce']
				));
			}

			//Gestion erreur.
			switch ($error_get)
			{
				case 'flood_t':
				$errstr = $LANG['e_flood'];
				$type = E_USER_WARNING;
				break;
				case 'incomplete_t':
				$errstr = $LANG['e_incomplete'];
				$type = E_USER_NOTICE;
				break;
				case 'c_locked':
				$errstr = $LANG['e_cat_lock_forum'];
				$type = E_USER_WARNING;
				break;
				case 'c_write':
				$errstr = $LANG['e_cat_write'];
				$type = E_USER_WARNING;
				break;
				default:
				$errstr = '';
			}
			if (!empty($errstr))
				$Template->put('message_helper', MessageHelper::display($errstr, $type));

			//Liste des choix des sondages => 20 maxi
			$nbr_poll_field = 0;
			for ($i = 0; $i < 5; $i++)
			{
				$Template->assign_block_vars('answers_poll', array(
					'ID' => $i,
					'ANSWER' => ''
				));
				$nbr_poll_field++;
			}

			$Template->put_all(array(
				'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
				'SID' => SID,
				'TITLE' => '',
				'SELECTED_SIMPLE' => 'checked="checked"',
				'IDTOPIC' => 0,
				'KERNEL_EDITOR' => display_editor(),
				'NO_DISPLAY_POLL' => 'true',
				'NBR_POLL_FIELD' => $nbr_poll_field,
				'C_ADD_POLL_FIELD' => true,
				'U_ACTION' => 'post.php' . url('?new=topic&amp;id=' . $id_get . '&amp;token=' . $Session->get_token()),
				'U_FORUM_CAT' => '<a href="forum' . url('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $CAT_FORUM[$id_get]['name'] . '</a>',
				'U_TITLE_T' => '<a href="post' . url('.php?new=topic&amp;id=' . $id_get) . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/post.png" alt="" /></a>',
				'L_ACTION' => $LANG['forum_new_subject'],
				'L_REQUIRE' => $LANG['require'],
				'L_REQUIRE_TEXT' => $LANG['require_text'],
				'L_REQUIRE_TITLE' => $LANG['require_title'],
				'L_REQUIRE_TITLE_POLL' => $LANG['require_title_poll'],
				'L_FORUM_INDEX' => $LANG['forum_index'],
				'L_TITLE' => $LANG['title'],
				'L_DESC' => $LANG['description'],
				'L_MESSAGE' => $LANG['message'],
				'L_SUBMIT' => $LANG['submit'],
				'L_PREVIEW' => $LANG['preview'],
				'L_RESET' => $LANG['reset'],
				'L_POLL' => $LANG['poll'],
				'L_OPEN_MENU_POLL' => $LANG['open_menu_poll'],
				'L_QUESTION' => $LANG['question'],
				'L_POLL_TYPE' => $LANG['poll_type'],
				'L_ANSWERS' => $LANG['answers'],
				'L_SINGLE' => $LANG['simple_answer'],
				'L_MULTIPLE' => $LANG['multiple_answer']
			));
		}
		else
		{
			$controller = PHPBoostErrors::unknow();
            DispatchManager::redirect($controller);
		}

		$Template->pparse('error_post');
	}
	else
	{
		$controller = PHPBoostErrors::unknow();
        DispatchManager::redirect($controller);
	}
}
else
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

include('../kernel/footer.php');

?>