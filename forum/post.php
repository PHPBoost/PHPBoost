<?php
/*##################################################
 *                                post.php
 *                            -------------------
 *   begin                : October 27, 2005
 *   copyright          : (C) 2005 Viarre Régis
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
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../includes/begin.php'); 
require_once('../forum/forum_begin.php');
$id_get = !empty($_GET['id']) ? numeric($_GET['id']) : '';
//Récupération de la barre d'arborescence.
speed_bar_generate($SPEED_BAR, $CONFIG_FORUM['forum_name'], 'index.php' . SID);
foreach($CAT_FORUM as $idcat => $array_info_cat)
{
	if( $CAT_FORUM[$id_get]['id_left'] > $array_info_cat['id_left'] && $CAT_FORUM[$id_get]['id_right'] < $array_info_cat['id_right'] && $array_info_cat['level'] < $CAT_FORUM[$id_get]['level'] )
		speed_bar_generate($SPEED_BAR, $array_info_cat['name'], ($array_info_cat['level'] == 0) ? transid('index.php?id=' . $idcat, 'cat-' . $idcat . '+' . url_encode_rewrite($array_info_cat['name']) . '.php') : 'forum' . transid('.php?id=' . $idcat, '-' . $idcat . '+' . url_encode_rewrite($array_info_cat['name']) . '.php'));
}
if( !empty($CAT_FORUM[$id_get]['name']) ) //Nom de la catégorie courante.
	speed_bar_generate($SPEED_BAR, $CAT_FORUM[$id_get]['name'], 'forum' . transid('.php?id=' . $id_get, '-' . $id_get . '+' . url_encode_rewrite($CAT_FORUM[$id_get]['name']) . '.php'));
speed_bar_generate($SPEED_BAR, $LANG['title_post'], '');
define('TITLE', $LANG['title_forum']);
require_once('../includes/header.php'); 

$new_get = !empty($_GET['new']) ? securit($_GET['new']) : '';
$previs = !empty($_POST['prw']) ? true : false; //Prévisualisation des messages.
$idt_get = !empty($_GET['idt']) ? numeric($_GET['idt']) : '';
$error_get = !empty($_GET['error']) ? securit($_GET['error']) : '';
$post_topic = !empty($_POST['post_topic']) ? trim($_POST['post_topic']) : '';
$preview_topic = !empty($_POST['prw_t']) ? trim($_POST['prw_t']) : '';

############# Vérification d'autorisation dans la catégorie envoyée #############
//Existance de la catégorie.
if( !isset($CAT_FORUM[$id_get]) || $CAT_FORUM[$id_get]['aprob'] == 0 || $CAT_FORUM[$id_get]['level'] == 0 )
	$errorh->error_handler('e_unexist_cat', E_USER_REDIRECT);

if( $session->data['user_readonly'] > time() ) //Lecture seule.
	$errorh->error_handler('e_readonly', E_USER_REDIRECT);

//Niveau d'autorisation de la catégorie
if( $groups->check_auth($CAT_FORUM[$id_get]['auth'], READ_CAT_FORUM) )
{
	//Instanciation de la class du forum.
	include_once('../forum/forum.class.php');
	$forumfct = new Forum;
	
	//Mod anti-flood
	$check_time = ($CONFIG['anti_flood'] == 1 && $session->data['user_id'] != -1) ? $sql->query("SELECT MAX(timestamp) as timestamp FROM ".PREFIX."forum_msg WHERE user_id = '" . $session->data['user_id'] . "'", __LINE__, __FILE__) : false;
	
	//Affichage de l'arborescence des catégories.
	$i = 0;
	$forum_cats = '';	
	array_pop($SPEED_BAR);
	foreach($SPEED_BAR as $key => $array)
	{
		if( $i == 2 )
			$forum_cats .= '<a href="' . $array[1] . '">' . $array[0] . '</a>';
		elseif( $i > 2 )		
			$forum_cats .= ' &raquo; <a href="' . $array[1] . '">' . $array[0] . '</a>';
		$i++;
	}
		
	if( $previs ) //Prévisualisation des messages
	{
		if( !$groups->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM) )
			redirect(transid(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');
			
		$topic = $sql->query_array('forum_topics', 'idcat', 'title', 'subtitle', "WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);

		if( empty($topic['idcat']) ) //Topic inexistant.
			$errorh->error_handler('e_unexist_topic_forum', E_USER_REDIRECT);
		
		$template->set_filenames(array(
			'edit_msg' => '../templates/' . $CONFIG['theme'] . '/forum/forum_edit_msg.tpl',
			'forum_top' => '../templates/' . $CONFIG['theme'] . '/forum/forum_top.tpl',
			'forum_bottom' => '../templates/' . $CONFIG['theme'] . '/forum/forum_bottom.tpl'
		));
		
		$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';	
			
		$template->assign_block_vars('show_msg', array(
			'L_PREVIEW' => $LANG['preview'],
			'DATE' => $LANG['on'] . ' ' . gmdate_format('date_format'),
			'CONTENTS' => second_parse(stripslashes(parse($contents)))
		));
		
		$post_update = isset($_POST['p_update']) ? trim($_POST['p_update']) : '';
		
		$update = !empty($post_update) ? $post_update : transid('?new=n_msg&amp;idt=' . $idt_get . '&amp;id=' . $id_get);
		$submit = !empty($post_update) ? $LANG['update'] : $LANG['submit'];
		
		$template->assign_vars(array(
			'THEME' => $CONFIG['theme'],
			'LANG' => $CONFIG['lang'],
			'P_UPDATE' => $post_update,
			'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
			'SID' => SID,
			'DESC' => $topic['subtitle'],
			'CONTENTS' => stripslashes($contents),
			'U_ACTION' => 'post.php' . $update,
			'U_FORUM_CAT' => $forum_cats,
			'U_TITLE_T' => '<a href="topic' . transid('.php?id=' . $idt_get, '-' . $idt_get . '.php') . '">' . ucfirst($topic['title']) . '</a>',
			'U_SEARCH' => '<a class="small_link" href="search.php' . SID . '" title="' . $LANG['search'] . '">' . $LANG['search'] . '</a> &bull;',
			'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a> &bull;',
			'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' . SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . '</a>',
			'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . SID . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a> &bull;',
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
		
		include_once('../includes/bbcode.php');
		
		$template->pparse('edit_msg');
	}
	elseif( $new_get === 'topic' && empty($error_get) )
	{			
		if( !empty($post_topic) && !empty($id_get) )
		{
			$is_modo = $groups->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM);
			if( !$groups->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM) )
				redirect(transid(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');
			
			if( $is_modo )
				$type = isset($_POST['type']) ? numeric($_POST['type']) : 0; 
			else
				$type = 0;
			
			//Verrouillé?
			$check_status = $CAT_FORUM[$id_get]['status'];
			//Déverrouillé pour admin et modo dans tous les cas
			if( $is_modo ) 
				$check_status = 1;
			
			$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : ''; 
			$title = !empty($_POST['title']) ? securit($_POST['title']) : ''; 
			$subtitle = !empty($_POST['desc']) ? securit($_POST['desc']) : ''; 
		
			//Mod anti Flood
			if( $check_time !== false && $check_status != 0 ) 
			{
				$delay_flood = $CONFIG['delay_flood']; //On recupère le delai de flood.
				$delay_expire = time() - $delay_flood; //On calcul la fin du delai.
				
				//Droit de flooder?.
				if( $check_time >= $delay_expire && !$groups->check_auth($CONFIG_FORUM['auth'], FLOOD_FORUM) ) //Flood
					redirect(transid(HOST . SCRIPT . '?error=flood_t&id=' . $id_get, '', '&') . '#errorh');
			}
			
			if( $check_status == 1 )
			{
				if( !empty($contents) && !empty($title) ) //Insertion nouveau topic.
				{
					list($last_topic_id, $last_msg_id) = $forumfct->add_topic($id_get, $title, $subtitle, $contents, $type); //Insertion nouveau topic.
					
					//Ajout d'un sondage en plus du topic.
					$question = isset($_POST['question']) ? securit($_POST['question']) : '';
					if( !empty($question) )
					{
						$poll_type = isset($_POST['poll_type']) ? numeric($_POST['poll_type']) : 0;
						$poll_type = ($poll_type == 0 || $poll_type == 1) ? $poll_type : 0;
						$answers = '';
						$votes = '';
						for($i = 0; $i < 20; $i++)
						{	
							if( !empty($_POST['a'.$i]) )
							{				
								$answers .= securit(str_replace('|', '', $_POST['a'.$i])) . '|';
								$votes .= '0|';
							}
						}
						$forumfct->add_poll($last_topic_id, $question, $answers, 0, $votes, $poll_type); //Ajout du sondage.
					}
					
					redirect(HOST . DIR . '/forum/topic' . transid('.php?id=' . $last_topic_id, '-' . $last_topic_id . '.php', '&') . '#m' . $last_msg_id);
				}
				else
					redirect(transid(HOST . SCRIPT . '?error=incomplete_t&id=' . $id_get, '', '&') . '#errorh');
			}
			else //Verrouillé
				redirect(transid(HOST . SCRIPT . '?error=c_locked&id=' . $id_get, '', '&') . '#errorh');
		}
		elseif( !empty($preview_topic) && !empty($id_get) )
		{
			if( !$groups->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM) )
				redirect(transid(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');
			
			$template->set_filenames(array(
				'forum_post' => '../templates/' . $CONFIG['theme'] . '/forum/forum_post.tpl',
				'forum_top' => '../templates/' . $CONFIG['theme'] . '/forum/forum_top.tpl',
				'forum_bottom' => '../templates/' . $CONFIG['theme'] . '/forum/forum_bottom.tpl'
			));
			
			$title = !empty($_POST['title']) ? trim($_POST['title']) : '';
			$subtitle = !empty($_POST['desc']) ? trim($_POST['desc']) : '';
			$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
			
			$is_modo = $groups->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM);
			if( $is_modo )
				$type = isset($_POST['type']) ? numeric($_POST['type']) : 0; 
			else
			{
				$type = isset($_POST['type']) ? numeric($_POST['type']) : ''; 
				$type = ( $type == 1 || $type == 0 ) ? $type : 0;
			}
			
			if( $is_modo )
			{
				$template->assign_block_vars('type', array(
					'CHECKED_NORMAL' => (($type == '0') ? 'checked="ckecked"' : ''),
					'CHECKED_POSTIT' => (($type == '1') ? 'checked="ckecked"' : ''),
					'CHECKED_ANNONCE' => (($type == '2') ? 'checked="ckecked"' : ''),
					'L_TYPE' => '* ' . $LANG['type'],
					'L_DEFAULT' => $LANG['default'],
					'L_POST_IT' => $LANG['forum_postit'],
					'L_ANOUNCE' => $LANG['forum_announce']
				));
			}

			$template->assign_block_vars('show_msg', array(
				'L_PREVIEW' => $LANG['preview'],
				'DATE' => $LANG['on'] . ' ' . gmdate_format('date_format'),
				'CONTENTS' => second_parse(stripslashes(parse($contents)))
			));
			
			$template->assign_vars(array(
				'THEME' => $CONFIG['theme'],
				'LANG' => $CONFIG['lang'],
				'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
				'SID' => SID,				
				'TITLE' => stripslashes($title),
				'DESC' => stripslashes($subtitle),
				'CONTENTS' => stripslashes($contents),
				'QUESTION' => !empty($_POST['question']) ? stripslashes($_POST['question']) : '',
				'U_ACTION' => 'post.php' . transid('?new=topic&amp;id=' . $id_get),
				'U_FORUM_CAT' => $forum_cats,
				'U_TITLE_T' => '<a href="post' . transid('.php?new=topic&amp;id=' . $id_get) . '">' . stripslashes($title) . '</a>',
				'U_SEARCH' => '<a class="small_link" href="search.php' . SID . '" title="' . $LANG['search'] . '">' . $LANG['search'] . '</a> &bull;',
				'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a> &bull;',
				'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' . SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . '</a>',
				'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . SID . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a> &bull;',
				'L_ACTION' => $LANG['forum_edit_subject'],
				'L_REQUIRE' => $LANG['require'],
				'L_REQUIRE_TEXT' => $LANG['require_text'],
				'L_REQUIRE_TITLE' => $LANG['require_title'],
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
			
			//Liste des choix des sondages => 20 maxi
			for($i = 0; $i < 20; $i++)
				$template->assign_vars(array(
					'ANSWER' . $i => !empty($_POST['a'.$i]) ? stripslashes($_POST['a'.$i]) : ''
				));
			
			//Type de réponses du sondage.
			$poll_type = isset($_POST['poll_type']) ? numeric($_POST['poll_type']) : 0;
			if( $poll_type == 0 )
			{
				$template->assign_vars(array(
					'SELECTED_SIMPLE' => 'checked="ckecked"'
				));
			}
			elseif( $poll_type == 1 )				
			{
				$template->assign_vars(array(
					'SELECTED_MULTIPLE' => 'checked="ckecked"'
				));
			}	
			
			include_once('../includes/bbcode.php');
			
			$template->pparse('forum_post');
		}
		else
		{
			if( !$groups->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM) )
				redirect(transid(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');
			
			$template->set_filenames(array(
				'forum_post' => '../templates/' . $CONFIG['theme'] . '/forum/forum_post.tpl',
				'forum_top' => '../templates/' . $CONFIG['theme'] . '/forum/forum_top.tpl',
				'forum_bottom' => '../templates/' . $CONFIG['theme'] . '/forum/forum_bottom.tpl'
			));

			if( $groups->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM) )
			{
				$template->assign_block_vars('type', array(
					'CHECKED_NORMAL' => 'checked="ckecked"',
					'L_TYPE' => '* ' . $LANG['type'],
					'L_DEFAULT' => $LANG['default'],
					'L_POST_IT' => $LANG['forum_postit'],
					'L_ANOUNCE' => $LANG['forum_announce']
				));
			}
			
			$template->assign_vars(array(
				'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
				'SID' => SID,
				'TITLE' => '',
				'DESC' => '',
				'SELECTED_SIMPLE' => 'checked="ckecked"',
				'U_ACTION' => 'post.php' . transid('?new=topic&amp;id=' . $id_get),
				'U_FORUM_CAT' => $forum_cats,
				'U_TITLE_T' => '<a href="post' . transid('.php?new=topic&amp;id=' . $id_get) . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/post.png" alt="" class="valign_middle" /></a>',
				'U_SEARCH' => '<a class="small_link" href="search.php' . SID . '" title="' . $LANG['search'] . '">' . $LANG['search'] . '</a> &bull;',
				'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a> &bull;',
				'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' . SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . '</a>',
				'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . SID . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a> &bull;',
				'L_ACTION' => $LANG['forum_new_subject'],
				'L_REQUIRE' => $LANG['require'],
				'L_REQUIRE_TEXT' => $LANG['require_text'],
				'L_REQUIRE_TITLE' => $LANG['require_title'],
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
			
			include_once('../includes/bbcode.php');
			
			
			$template->pparse('forum_post');
		}
	}
	elseif( $new_get === 'n_msg' && empty($error_get) ) //Nouveau message
	{
		if( !$groups->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM) )
			redirect(transid(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');
			
		//Verrouillé?
		$topic = $sql->query_array('forum_topics', 'idcat', 'title', 'nbr_msg', 'last_user_id', 'status', "WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);
		if( empty($topic['idcat']) )
			$errorh->error_handler('e_topic_lock_forum', E_USER_REDIRECT);
		
		$is_modo = $groups->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM);
		//Catégorie verrouillée?
		$check_status = $CAT_FORUM[$id_get]['status'];
		//Déverrouillé pour admin et modo dans tous les cas
		if( $is_modo ) 
			$check_status = 1;
		
		if( $check_status == 0 ) //Verrouillée
			redirect(transid(HOST . SCRIPT . '?error=c_locked&id=' . $id_get, '', '&') . '#errorh');
		
		//Mod anti Flood
		if( $check_time !== false ) 
		{
			$delay_expire = time() - $CONFIG['delay_flood']; //On calcul la fin du delai.			
			//Droit de flooder?
			if( $check_time >= $delay_expire && !$groups->check_auth($CONFIG_FORUM['auth'], FLOOD_FORUM) ) //Ok
				redirect( transid(HOST . SCRIPT . '?error=flood&id=' . $id_get . '&idt=' . $idt_get, '', '&') . '#errorh');
		}
		
		$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
		
		//Si le topic n'est pas vérrouilé on ajoute le message.
		if( $topic['status'] != 0 || $is_modo )
		{
			if( !empty($contents) && !empty($idt_get) && empty($update) ) //Nouveau message.
			{
				$last_page = ceil( ($topic['nbr_msg'] + 1) / $CONFIG_FORUM['pagination_msg'] );
				$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
				$last_page = ($last_page > 1) ? '&pt=' . $last_page : ''; 
				
				$last_msg_id = $forumfct->add_msg($idt_get, $topic['idcat'], $contents, $topic['title'], $last_page, $last_page_rewrite);
				
				//Redirection après post.
				redirect(HOST . DIR . '/forum/topic' . transid('.php?id=' . $idt_get . $last_page, '-' . $idt_get . $last_page_rewrite . '.php', '&') . '#m' . $last_msg_id);
			}
			else
				redirect(transid(HOST . SCRIPT . '?error=incomplete&id=' . $id_get . '&idt=' . $idt_get, '', '&') . '#errorh');
		}
		else
			redirect(transid(HOST . SCRIPT . '?error=locked&id=' . $id_get . '&idt=' . $idt_get, '', '&') . '#errorh');
	}
	elseif( $new_get === 'msg' && empty($error_get) ) //Edition d'un message/topic.
	{
		if( !$groups->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM) )
			redirect(transid(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');
			
		$id_m = !empty($_GET['idm']) ? numeric($_GET['idm']) : 0;		
		$update = !empty($_GET['update']) ? true : false;
		$id_first = $sql->query("SELECT MIN(id) FROM ".PREFIX."forum_msg WHERE idtopic = '" . $idt_get . "'", __LINE__, __FILE__);
		$topic = $sql->query_array('forum_topics', 'title', 'subtitle', 'type', 'user_id', 'display_msg', "WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);
		
		if( empty($id_get) || empty($id_first) ) //Topic/message inexistant.
			$errorh->error_handler('e_unexist_topic_forum', E_USER_REDIRECT);
		
		$is_modo = $groups->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM);
		
		//Edition du topic complet
		if( $id_first == $id_m )
		{		
			//User_id du message correspondant à l'utilisateur connecté => autorisation.		
			$user_id_msg = $sql->query("SELECT user_id FROM ".PREFIX."forum_msg WHERE id = '" . $id_m . "'",  __LINE__, __FILE__);
			$check_auth = false;
			if( $user_id_msg == $session->data['user_id'] ) 
				$check_auth = true;
			elseif( $is_modo ) 
				$check_auth = true;
		
			if( !$check_auth )
				$errorh->error_handler('e_auth', E_USER_REDIRECT); 
			
			if( $update && !empty($post_topic) )
			{
				$title = !empty($_POST['title']) ? securit($_POST['title']) : '';
				$subtitle = !empty($_POST['desc']) ? securit($_POST['desc']) : '';
				$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
				$type = ($is_modo && isset($_POST['type'])) ? numeric($_POST['type']) : 0; 
				
				if( !empty($title) && !empty($contents) )
				{				
					$forumfct->update_topic($idt_get, $id_m, $title, $subtitle, $contents, $type, $user_id_msg); //Mise à jour du topic.

					//Mise à jour du sondage en plus du topic.
					$del_poll = isset($_POST['del_poll']) ? trim($_POST['del_poll']) : '';
					$question = isset($_POST['question']) ? securit($_POST['question']) : '';
					if( !empty($question) && empty($del_poll) ) //Enregistrement du sondage.
					{
						//Mise à jour si le sondage existe, sinon création.
						$check_poll = $sql->query("SELECT COUNT(*) FROM ".PREFIX."forum_poll WHERE idtopic = '" . $idt_get . "'",  __LINE__, __FILE__);
						
						$poll_type = isset($_POST['poll_type']) ? numeric($_POST['poll_type']) : 0;
						$poll_type = ($poll_type == 0 || $poll_type == 1) ? $poll_type : 0;
						$answers = '';
						$votes = ($check_poll == 1) ? 'votes = "' : '';
						$check_nbr_answer = 0;
						for($i = 0; $i < 20; $i++)
						{
							if( !empty($_POST['a'.$i]) )
							{				
								$answers .= securit(str_replace('|', '', $_POST['a'.$i])) . '|';
								$votes .= '0|';
								$check_nbr_answer++;
							}
						}
						$votes .= ($check_poll == 1) ? '", ' : '';						

						if( $check_poll == 1 ) //Mise à jour.
							$forumfct->update_poll($idt_get, $question, $answers, $votes, $poll_type);
						elseif( $check_poll == 0 ) //Ajout du sondage.
							$forumfct->add_poll($idt_get, $question, $answers, 0, $votes, $poll_type); 
					}
					elseif( !empty($del_poll) && $groups->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM) ) //Suppression du sondage, admin et modo seulement biensûr...
						$forumfct->del_poll($idt_get);
					
					//Redirection après post.
					redirect(HOST . DIR . '/forum/topic' . transid('.php?id=' . $idt_get, '-' . $idt_get . '.php', '&'));
				}
				else
					redirect(HOST . DIR . '/forum/post' . transid('.php?new=msg&idm=' . $id_m . '&id=' . $id_get . '&idt=' . $idt_get . '&errore=incomplete_t', '', '&') . '#errorh');
			}
			elseif( !empty($preview_topic) )
			{
				$template->set_filenames(array(
					'forum_post' => '../templates/' . $CONFIG['theme'] . '/forum/forum_post.tpl',
					'forum_top' => '../templates/' . $CONFIG['theme'] . '/forum/forum_top.tpl',
					'forum_bottom' => '../templates/' . $CONFIG['theme'] . '/forum/forum_bottom.tpl'
				));
				
				$title = !empty($_POST['title']) ? trim($_POST['title']) : '';
				$subtitle = !empty($_POST['desc']) ? trim($_POST['desc']) : '';
				$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
				
				if( $is_modo )
					$type = isset($_POST['type']) ? numeric($_POST['type']) : 0; 
				else
				{
					$type = isset($_POST['type']) ? numeric($_POST['type']) : 0; 
					$type = ($type == 1 || $type == 0) ? $type : 0;
				}

				if( $is_modo )
				{
					$template->assign_block_vars('type', array(
						'CHECKED_NORMAL' => (($type == 0) ? 'checked="ckecked"' : ''),
						'CHECKED_POSTIT' => (($type == 1) ? 'checked="ckecked"' : ''),
						'CHECKED_ANNONCE' => (($type == 2) ? 'checked="ckecked"' : ''),
						'L_TYPE' => '* ' . $LANG['type'],
						'L_DEFAULT' => $LANG['default'],
						'L_POST_IT' => $LANG['forum_postit'],
						'L_ANOUNCE' => $LANG['forum_announce']
					));
				}
					
				$template->assign_block_vars('show_msg', array(
					'L_PREVIEW' => $LANG['preview'],
					'DATE' => $LANG['on'] . ' ' . gmdate_format('date_format'),
					'CONTENTS' => second_parse(stripslashes(parse($contents)))
				));
				
				//Suppression d'un sondage => modo uniquement.
				if( $is_modo )
					$template->assign_block_vars('delete_poll', array(
					));
				
				$template->assign_vars(array(
					'THEME' => $CONFIG['theme'],
					'LANG' => $CONFIG['lang'],
					'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
					'SID' => SID,
					'TITLE' => stripslashes($title),
					'DESC' => stripslashes($subtitle),
					'CONTENTS' => stripslashes($contents),
					'QUESTION' => !empty($_POST['question']) ? stripslashes($_POST['question']) : '',
					'SELECTED_SIMPLE' => 'checked="ckecked"',
					'U_ACTION' => 'post.php' . transid('?update=1&amp;new=msg&amp;id=' . $id_get . '&amp;idt=' . $idt_get . '&amp;idm=' . $id_m),
					'U_FORUM_CAT' => '<a href="forum' . transid('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $CAT_FORUM[$id_get]['name'] . '</a>',
					'U_TITLE_T' => '<a href="topic' . transid('.php?id=' . $idt_get, '-' . $idt_get . '.php') . '">' . stripslashes($title) . '</a>',
					'U_SEARCH' => '<a class="small_link" href="search.php' . SID . '" title="' . $LANG['search'] . '">' . $LANG['search'] . '</a> &bull;',
					'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a> &bull;',
					'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' . SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . '</a>',
					'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . SID . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a> &bull;',
					'L_ACTION' => $LANG['forum_edit_subject'],
					'L_REQUIRE' => $LANG['require'],
					'L_REQUIRE_TEXT' => $LANG['require_text'],
					'L_REQUIRE_TITLE' => $LANG['require_title'],
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
				
				//Liste des choix des sondages => 20 maxi
				for($i = 0; $i < 20; $i++)
					$template->assign_vars(array(
						'ANSWER' . $i => !empty($_POST['a'.$i]) ? stripslashes($_POST['a'.$i]) : ''
					));
					
				//Type de réponses du sondage.
				$poll_type = isset($_POST['poll_type']) ? numeric($_POST['poll_type']) : 0;
				if( $poll_type == 0 )
				{
					$template->assign_vars(array(
						'SELECTED_SIMPLE' => 'checked="ckecked"'
					));
				}
				elseif( $poll_type == 1 )				
				{
					$template->assign_vars(array(
						'SELECTED_MULTIPLE' => 'checked="ckecked"'
					));
				}
				
				include_once('../includes/bbcode.php');
				
				
				$template->pparse('forum_post');
			}
			else
			{
				$template->set_filenames(array(
					'forum_post' => '../templates/' . $CONFIG['theme'] . '/forum/forum_post.tpl',
					'forum_top' => '../templates/' . $CONFIG['theme'] . '/forum/forum_top.tpl',
					'forum_bottom' => '../templates/' . $CONFIG['theme'] . '/forum/forum_bottom.tpl'
				));
				
				$contents = $sql->query("SELECT contents FROM ".PREFIX."forum_msg WHERE id = '" . $id_first . "'", __LINE__, __FILE__);
							
				//Gestion des erreurs à l'édition.
				$get_error_e = !empty($_GET['errore']) ? trim($_GET['errore']) : '';
				if( $get_error_e == 'incomplete_t' )
					$errorh->error_handler($LANG['e_incomplete'], E_USER_NOTICE);

				if( $is_modo )
				{
					$template->assign_block_vars('type', array(
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
				$poll = $sql->query_array('forum_poll', 'question', 'answers', 'type', "WHERE idtopic = '" . $idt_get . "'", __LINE__, __FILE__);
				$array_answer = explode('|', $poll['answers']);
	
				$module_data_path = $template->module_data_path('forum');
				
				//Affichage du lien pour changer le display_msg du topic et autorisation d'édition.
				if( $CONFIG_FORUM['activ_display_msg'] == 1 && ($is_modo || $session->data['user_id'] == $topic['user_id']) )
				{
					$img_display = $topic['display_msg'] ? 'msg_display2.png' : 'msg_display.png';
					$template->assign_vars(array(
						'C_DISPLAY_MSG' => true,
						'ICON_DISPLAY_MSG' => $CONFIG_FORUM['icon_activ_display_msg'] ? '<img src="' . $module_data_path . '/images/' . $img_display . '" alt="" class="valign_middle" />' : '',
						'ICON_DISPLAY_MSG2' => $CONFIG_FORUM['icon_activ_display_msg'] ? '<img src="' . $module_data_path . '/images/' . $img_display . '" alt="" class="valign_middle" id="forum_change_img" />' : '',
						'L_EXPLAIN_DISPLAY_MSG_DEFAULT' => $topic['display_msg'] ? $CONFIG_FORUM['explain_display_msg_bis'] : $CONFIG_FORUM['explain_display_msg'],
						'L_EXPLAIN_DISPLAY_MSG' => $CONFIG_FORUM['explain_display_msg'],
						'L_EXPLAIN_DISPLAY_MSG_BIS' => $CONFIG_FORUM['explain_display_msg_bis'],
						'U_ACTION_MSG_DISPLAY' => transid('.php?msg_d=1&amp;id=' . $id_get)
					));
				}
				
				//Suppression d'un sondage => modo uniquement.
				if( $is_modo )
					$template->assign_block_vars('delete_poll', array(
					));
					
				$template->assign_vars(array(
					'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
					'SID' => SID,
					'TITLE' => $topic['title'],
					'DESC' => $topic['subtitle'],
					'CONTENTS' => unparse($contents),
					'QUESTION' => !empty($poll['question']) ? $poll['question'] : '',
					'SELECTED_SIMPLE' => 'checked="ckecked"',
					'MODULE_DATA_PATH' => $module_data_path,
					'IDTOPIC' => $idt_get,
					'U_ACTION' => 'post.php' . transid('?update=1&amp;new=msg&amp;id=' . $id_get . '&amp;idt=' . $idt_get . '&amp;idm=' . $id_m),
					'U_FORUM_CAT' => '<a href="forum' . transid('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $CAT_FORUM[$id_get]['name'] . '</a>',
					'U_TITLE_T' => '<a href="topic' . transid('.php?id=' . $idt_get, '-' . $idt_get . '.php') . '">' . $topic['title'] . '</a>',
					'U_SEARCH' => '<a class="small_link" href="search.php' . SID . '" title="' . $LANG['search'] . '">' . $LANG['search'] . '</a> &bull;',
					'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a> &bull;',
					'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' . SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . '</a>',
					'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . SID . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a> &bull;',
					'L_ACTION' => $LANG['forum_edit_subject'],
					'L_REQUIRE' => $LANG['require'],
					'L_REQUIRE_TEXT' => $LANG['require_text'],
					'L_REQUIRE_TITLE' => $LANG['require_title'],
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
				
				//Liste des choix des sondages => 20 maxi
				for($i = 0; $i < 20; $i++)
					$template->assign_vars(array(
						'ANSWER' . $i => !empty($array_answer[$i]) ? $array_answer[$i] : ''
					));
				
				//Type de réponses du sondage.
				if( isset($poll['type']) && $poll['type'] == '0' )
				{
					$template->assign_vars(array(
						'SELECTED_SIMPLE' => 'checked="ckecked"'
					));
				}
				elseif( isset($poll['type']) && $poll['type'] == '1' )				
				{
					$template->assign_vars(array(
						'SELECTED_MULTIPLE' => 'checked="ckecked"'
					));
				}	
				
				include_once('../includes/bbcode.php');
				
				
				$template->pparse('forum_post');
			}
		}
		//Sinon on édite simplement le message
		elseif( $id_m > $id_first )
		{
			//User_id du message correspondant à l'utilisateur connecté => autorisation.		
			$user_id_msg = $sql->query("SELECT user_id FROM ".PREFIX."forum_msg WHERE id = '" . $id_m . "'", __LINE__, __FILE__);
			$check_auth = false;
			if( $user_id_msg == $session->data['user_id'] ) 
				$check_auth = true;
			elseif( $is_modo ) 
				$check_auth = true;
	
			if( !$check_auth ) //Non autorisé!
				$errorh->error_handler('e_auth', E_USER_REDIRECT); 
			
			if( $update && !empty($_POST['edit_msg']) )
			{
				$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
				if( !empty($contents) )
				{		
					$nbr_msg_before = $forumfct->update_msg($idt_get, $id_m, $contents, $user_id_msg);
					
					//Calcul de la page sur laquelle se situe le message.
					$msg_page = ceil( ($nbr_msg_before + 1) / $CONFIG_FORUM['pagination_msg'] );
					$msg_page_rewrite = ($msg_page > 1) ? '-' . $msg_page : '';
					$msg_page = ($msg_page > 1) ? '&pt=' . $msg_page : '';
					
					//Redirection après édition.
					redirect(HOST . DIR . '/forum/topic' . transid('.php?id=' . $idt_get . $msg_page, '-' . $idt_get .  $msg_page_rewrite . '.php', '&') . '#m' . $id_m);
				}
				else
					redirect(HOST . DIR . '/forum/post' . transid('.php?new=msg&idm=' . $id_m . '&id=' . $id_get . '&idt=' . $idt_get . '&errore=incomplete', '', '&') . '#errorh');
			}
			else
			{
				$template->set_filenames(array(
					'edit_msg' => '../templates/' . $CONFIG['theme'] . '/forum/forum_edit_msg.tpl',
					'forum_top' => '../templates/' . $CONFIG['theme'] . '/forum/forum_top.tpl',
					'forum_bottom' => '../templates/' . $CONFIG['theme'] . '/forum/forum_bottom.tpl'
				));
				
				$contents = $sql->query("SELECT contents FROM ".PREFIX."forum_msg WHERE id = '" . $id_m . "'", __LINE__, __FILE__);
				//Gestion des erreurs à l'édition.
				$get_error_e = !empty($_GET['errore']) ? trim($_GET['errore']) : '';
				if( $get_error_e == 'incomplete' )
					$errorh->error_handler($LANG['e_incomplete'], E_USER_NOTICE);
					
				$template->assign_vars(array(
					'P_UPDATE' => transid('?update=1&amp;new=msg&amp;id=' . $id_get . '&amp;idt=' . $idt_get . '&amp;idm=' . $id_m),
					'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
					'SID' => SID,
					'DESC' => $topic['subtitle'],
					'CONTENTS' => unparse($contents),
					'U_ACTION' => 'post.php' . transid('?update=1&amp;new=msg&amp;id=' . $id_get . '&amp;idt=' . $idt_get . '&amp;idm=' . $id_m),
					'U_FORUM_CAT' => '<a href="forum' . transid('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $CAT_FORUM[$id_get]['name'] . '</a>',
					'U_TITLE_T' => '<a href="topic' . transid('.php?id=' . $idt_get, '-' . $idt_get . '.php') . '">' . $topic['title'] . '</a>',
					'U_SEARCH' => '<a class="small_link" href="search.php' . SID . '" title="' . $LANG['search'] . '">' . $LANG['search'] . '</a> &bull;',
					'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a> &bull;',
					'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' . SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . '</a>',
					'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . SID . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a> &bull;',
					'L_REQUIRE' => $LANG['require'],
					'L_REQUIRE_TEXT' => $LANG['require_text'],
					'L_FORUM_INDEX' => $LANG['forum_index'],
					'L_EDIT_MESSAGE' => $LANG['edit_message'],
					'L_MESSAGE' => $LANG['message'],
					'L_SUBMIT' => $LANG['update'],
					'L_PREVIEW' => $LANG['preview'],
					'L_RESET' => $LANG['reset'],
				));
				
				include_once('../includes/bbcode.php');
				
				$template->pparse('edit_msg');
			}
		}
	}
	elseif( !empty($error_get) && (!empty($idt_get) || !empty($id_get)) )
	{
		if( !empty($id_get) && !empty($idt_get) && ($error_get === 'flood' || $error_get === 'incomplete' || $error_get === 'locked') )
		{
			$topic = $sql->query_array('forum_topics', 'idcat', 'title', 'subtitle', "WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);
			if( empty($topic['idcat']) ) //Topic inexistant.
				$errorh->error_handler('e_unexist_topic_forum', E_USER_REDIRECT);
			
			$template->set_filenames(array(
				'error_post' => '../templates/' . $CONFIG['theme'] . '/forum/forum_edit_msg.tpl',
				'forum_top' => '../templates/' . $CONFIG['theme'] . '/forum/forum_top.tpl',
				'forum_bottom' => '../templates/' . $CONFIG['theme'] . '/forum/forum_bottom.tpl'
			));

			//Gestion erreur.
			switch($error_get)
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
				$errstr = $LANG['e_topic_lock'];
				$type = E_USER_WARNING; 
				break;
				default:
				$errstr = '';
			}
			if( !empty($errstr) )
				$errorh->error_handler($errstr, $type);
	
			$template->assign_vars(array(
				'P_UPDATE' => '',
				'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
				'SID' => SID,
				'DESC' => $topic['subtitle'],
				'U_ACTION' => 'post.php' . transid('?new=n_msg&amp;idt=' . $idt_get . '&amp;id=' . $id_get),
				'U_FORUM_CAT' => '<a href="forum' . transid('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $CAT_FORUM[$id_get]['name'] . '</a>',
				'U_TITLE_T' => '<a href="topic' . transid('.php?id=' . $idt_get, '-' . $idt_get . '.php') . '">' . $topic['title'] . '</a>',
				'U_SEARCH' => '<a class="small_link" href="search.php' . SID . '" title="' . $LANG['search'] . '">' . $LANG['search'] . '</a> &bull;',
				'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a> &bull;',
				'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' . SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . '</a>',
				'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . SID . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a> &bull;',
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
		elseif( !empty($id_get) && ($error_get === 'c_locked' || $error_get === 'c_write' || $error_get === 'incomplete_t' || $error_get === 'false_t') )
		{
			$template->set_filenames(array(
				'error_post' => '../templates/' . $CONFIG['theme'] . '/forum/forum_post.tpl',
				'forum_top' => '../templates/' . $CONFIG['theme'] . '/forum/forum_top.tpl',
				'forum_bottom' => '../templates/' . $CONFIG['theme'] . '/forum/forum_bottom.tpl'
			));
		
			if( $groups->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM) )
			{
				$template->assign_block_vars('type', array(
					'L_TYPE' => '* ' . $LANG['type'],
					'L_DEFAULT' => $LANG['default'],
					'L_POST_IT' => $LANG['forum_postit'],
					'L_ANOUNCE' => $LANG['forum_announce']
				));
			}
			
			//Gestion erreur.
			switch($error_get)
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
			if( !empty($errstr) )
				$errorh->error_handler($errstr, $type);
				
			$template->assign_vars(array(
				'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
				'SID' => SID,
				'TITLE' => '',
				'SELECTED_SIMPLE' => 'checked="checked"',
				'U_ACTION' => 'post.php' . transid('?new=topic&amp;id=' . $id_get),
				'U_FORUM_CAT' => '<a href="forum' . transid('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $CAT_FORUM[$id_get]['name'] . '</a>',
				'U_TITLE_T' => '<a href="post' . transid('.php?new=topic&amp;id=' . $id_get) . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/post.png" alt="" /></a>',
				'U_SEARCH' => '<a class="small_link" href="search.php' . SID . '" title="' . $LANG['search'] . '">' . $LANG['search'] . '</a> &bull;',
				'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a> &bull;',
				'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' . SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . '</a>',
				'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . SID . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a> &bull;',
				'L_ACTION' => $LANG['forum_new_subject'],
				'L_REQUIRE' => $LANG['require'],
				'L_REQUIRE_TEXT' => $LANG['require_text'],
				'L_REQUIRE_TITLE' => $LANG['require_title'],
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
			$errorh->error_handler('unknow_error', E_USER_REDIRECT);
			
		include_once('../includes/bbcode.php');
		
		$template->pparse('error_post');
	}
	else
		$errorh->error_handler('unknow_error', E_USER_REDIRECT); 
}
else
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 

include('../includes/footer.php');

?>