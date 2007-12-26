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

include_once('../includes/begin.php'); 
include_once('../forum/lang/' . $CONFIG['lang'] . '/forum_' . $CONFIG['lang'] . '.php'); //Chargement de la langue du module.
include_once('../forum/forum_auth.php');

$id_get = !empty($_GET['id']) ? numeric($_GET['id']) : '';

//Récupération de la barre d'arborescence.
$speed_bar = array($CONFIG_FORUM['forum_name'] => 'index.php' . SID);
foreach($CAT_FORUM as $idcat => $array_info_cat)
{
	if( $CAT_FORUM[$id_get]['id_left'] > $array_info_cat['id_left'] && $CAT_FORUM[$id_get]['id_right'] < $array_info_cat['id_right'] && $array_info_cat['level'] < $CAT_FORUM[$id_get]['level'] )
		$speed_bar[$array_info_cat['name']] = ($array_info_cat['level'] == 0) ? transid('index.php?id=' . $idcat, 'cat-' . $idcat . '+' . url_encode_rewrite($array_info_cat['name']) . '.php') : 'forum' . transid('.php?id=' . $idcat, '-' . $idcat . '+' . url_encode_rewrite($array_info_cat['name']) . '.php');
}
if( !empty($CAT_FORUM[$id_get]['name']) ) //Nom de la catégorie courante.
	$speed_bar[$CAT_FORUM[$id_get]['name']] = 'forum' . transid('.php?id=' . $id_get, '-' . $id_get . '+' . url_encode_rewrite($CAT_FORUM[$id_get]['name']) . '.php');
$speed_bar[$LANG['title_post']] = '';

define('TITLE', $LANG['title_forum']);
define('ALTERNATIVE_CSS', 'forum');
include_once('../includes/header.php'); 

$page = !empty($_GET['p']) ? numeric($_GET['p']) : 1;
$new_get = !empty($_GET['new']) ? securit($_GET['new']) : '';
$previs = !empty($_POST['prw']) ? $_POST['prw'] : '' ; //Prévisualisation des messages.
$idt_get = !empty($_GET['idt']) ? numeric($_GET['idt']) : '';
$error_get = !empty($_GET['error']) ? securit($_GET['error']) : '';
$post_topic = !empty($_POST['post_topic']) ? trim($_POST['post_topic']) : '';
$preview_topic = !empty($_POST['prw_t']) ? trim($_POST['prw_t']) : '';

if( !$groups->check_auth($SECURE_MODULE['forum'], ACCESS_MODULE) )
{
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

############# Vérification d'autorisation dans la catégorie envoyée #############
//Existance de la catégorie.
if( !isset($CAT_FORUM[$id_get]) || $CAT_FORUM[$id_get]['aprob'] == 0 || $CAT_FORUM[$id_get]['level'] == 0 )
{
	$errorh->error_handler('e_unexist_cat', E_USER_REDIRECT);
	exit;
}

if( $session->data['user_readonly'] > time() ) //Lecture seule.
{
	$errorh->error_handler('e_readonly', E_USER_REDIRECT);
	exit;
}

//Niveau d'autorisation de la catégorie
if( $groups->check_auth($CAT_FORUM[$id_get]['auth'], READ_CAT_FORUM) )
{
	//Mod anti-flood
	$check_time = ($CONFIG['anti_flood'] == 1 && $session->data['user_id'] != -1) ? $sql->query("SELECT MAX(timestamp) as timestamp FROM ".PREFIX."forum_msg WHERE user_id = '" . $session->data['user_id'] . "'", __LINE__, __FILE__) : false;
	
	//Affichage de l'arborescence des catégories.
	$i = 0;
	$forum_cats = '';	
	array_pop($speed_bar);
	foreach($speed_bar as $cat_name => $cat_url)
	{
		if( $i == 2 )
			$forum_cats .= '<a href="' . $cat_url . '">' . $cat_name . '</a>';
		elseif( $i > 2 )		
			$forum_cats .= ' &raquo; <a href="' . $cat_url . '">' . $cat_name . '</a>';
		$i++;
	}
		
	if( !empty($previs) ) //Prévisualisation des messages
	{
		if( !$groups->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM) )
		{
			header('location:' . transid(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');
			exit;
		}
			
		$topic = $sql->query_array('forum_topics', 'idcat', 'title', 'subtitle', "WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);

		if( empty($topic['idcat']) ) //Topic inexistant.
		{
			$errorh->error_handler('e_unexist_topic_forum', E_USER_REDIRECT);
			exit;
		}
		
		$template->set_filenames(array(
			'edit_msg' => '../templates/' . $CONFIG['theme'] . '/forum/forum_edit_msg.tpl'
		));
		
		$contents = !empty($_POST['contents']) ? $_POST['contents'] : '';	
			
		$template->assign_block_vars('show_msg', array(
			'L_PREVIEW' => $LANG['preview'],
			'DATE' => $LANG['on'] . ' ' . date('d/m/Y ' . $LANG['at'] . ' H\hi', time()),
			'CONTENTS' => second_parse(stripslashes(parse($contents)))
		));
		
		$post_update = isset($_POST['p_update']) ? $_POST['p_update'] : '';
		
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
		$template->assign_var_from_handle('BBCODE', 'bbcode');
		
		$template->pparse('edit_msg');
	}
	elseif( $new_get === 'topic' && empty($error_get) )
	{			
		if( !empty($post_topic) && !empty($id_get) )
		{
			$is_modo = $groups->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM);
			if( !$groups->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM) )
			{
				header('location:' . transid(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');
				exit;
			}	
			
			if( $is_modo )
				$type = isset($_POST['type']) ? numeric($_POST['type']) : 0; 
			else
				$type = 0;
					
			//Verrouillé?
			$check_status = $CAT_FORUM[$id_get]['status'];
			//Déverrouillé pour admin et modo dans tous les cas
			if( $is_modo ) 
				$check_status = 1;
						
			$contents = !empty($_POST['contents']) ? parse($_POST['contents']) : ''; 
			$title = !empty($_POST['title']) ? securit($_POST['title']) : ''; 
			$subtitle = !empty($_POST['desc']) ? securit($_POST['desc']) : ''; 
		
			//Mod anti Flood
			if( $check_time !== false && $check_status != 0 ) 
			{
				$delay_flood = $CONFIG['delay_flood']; //On recupère le delai de flood.
				$delay_expire = time() - $delay_flood; //On calcul la fin du delai.
				
				//Droit de flooder?.
				if( $check_time >= $delay_expire && !$groups->check_auth($CONFIG_FORUM['auth'], FLOOD_FORUM) ) //Flood
				{
					header('location:' . transid(HOST . SCRIPT . '?error=flood_t&id=' . $id_get, '', '&') . '#errorh');
					exit;
				}
			}
			
			if( $check_status == 1 )
			{
				if( !empty($contents) && !empty($title) ) //Insertion nouveau topic.
				{
					$sql->query_inject("INSERT INTO ".PREFIX."forum_topics (idcat,title,subtitle,user_id,nbr_msg,nbr_views,last_user_id,last_msg_id,last_timestamp,first_msg_id,type,status,aprob,display_msg) VALUES ('" . $id_get . "', '" . $title . "', '" . $subtitle . "', '" . $session->data['user_id'] . "', 1, 0, '" . $session->data['user_id'] . "', '0', '" . time() . "', 0, '" . $type . "', 1, 0, 0)", __LINE__, __FILE__);
					$last_topic_id = $sql->sql_insert_id("SELECT MAX(id) FROM ".PREFIX."forum_topics");	//Dernier topic inseré
					
					$sql->query_inject("INSERT INTO ".PREFIX."forum_msg (idtopic, user_id, contents, timestamp, timestamp_edit, user_id_edit, user_ip) VALUES('" . $last_topic_id . "', '" . $session->data['user_id'] . "', '" . $contents . "', '" . time() . "', '0', '0', '" . USER_IP . "')", __LINE__, __FILE__);
					
					$last_msg_id = $sql->sql_insert_id("SELECT MAX(id) FROM ".PREFIX."forum_msg"); //Dernier message inseré, on met à jour le topic.
					$sql->query_inject("UPDATE ".PREFIX."forum_topics SET last_msg_id = '" . $last_msg_id . "', first_msg_id='" . $last_msg_id . "' WHERE id = '" . $last_topic_id . "'", __LINE__, __FILE__);
					
					//Mise à jour du nombre de messages de la catégorie, ainsi que du last_topic_id.
					$sql->query_inject("UPDATE ".PREFIX."forum_cats SET nbr_topic = nbr_topic + 1, nbr_msg = nbr_msg + 1, last_topic_id = '" . $last_topic_id . "' WHERE id_left <= '" . $CAT_FORUM[$id_get]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$id_get]['id_right'] ."' AND level <= '" . $CAT_FORUM[$id_get]['level'] . "'", __LINE__, __FILE__);
					
					//Mise à jour du nombre de messages du membre.
					$sql->query_inject("UPDATE ".PREFIX."member SET user_msg = user_msg + 1 WHERE user_id= '" . $session->data['user_id'] . "'", __LINE__, __FILE__);
					
					
					//Ajout d'un sondage en plus du topic.
					$question = isset($_POST['question']) ? securit($_POST['question']) : '';
					if( !empty($question) )
					{
						$poll_type = (isset($_POST['poll_type']) && ($_POST['poll_type'] == 0 || $_POST['poll_type'] == 1)) ? numeric($_POST['poll_type']) : '0';
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
						
						$sql->query_inject("INSERT INTO ".PREFIX."forum_poll (idtopic,question,answers,voter_id,votes,type) VALUES ('" . $last_topic_id . "', '" . $question . "', '" . substr($answers, 0, strlen($answers) - 1) . "', 0, '" . substr($votes, 0, strlen($votes) - 1) . "', '" . $poll_type . "')", __LINE__, __FILE__);
					}
					
					//Regénération du flux rss.
					include_once('../includes/rss.class.php');
					$rss = new Rss('forum/rss.php');
					$rss->cache_path('../cache/');
					$rss->generate_file('javascript', 'rss_forum');
					$rss->generate_file('php', 'rss2_forum');
					
					header('location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $last_topic_id, '-' . $last_topic_id . '.php', '&') . '#m' . $last_msg_id);
					exit;
				}
				else
				{
					header('location:' . transid(HOST . SCRIPT . '?error=incomplete_t&id=' . $id_get, '', '&') . '#errorh');
					exit;
				}
			}
			else //Verrouillé
			{
				header('location:' . transid(HOST . SCRIPT . '?error=c_locked&id=' . $id_get, '', '&') . '#errorh');
				exit;
			}
		}
		elseif( !empty($preview_topic) && !empty($id_get) )
		{
			if( !$groups->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM) )
			{
				header('location:' . transid(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');
				exit;
			}	
			
			$template->set_filenames(array(
				'forum_post' => '../templates/' . $CONFIG['theme'] . '/forum/forum_post.tpl'
			));
			
			$title = !empty($_POST['title']) ? $_POST['title'] : '';
			$subtitle = !empty($_POST['desc']) ? $_POST['desc'] : '';
			$contents = !empty($_POST['contents']) ? $_POST['contents'] : '';
			
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
				'DATE' => $LANG['on'] . ' ' . date('d/m/Y ' . $LANG['at'] . ' H\hi', time()),
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
			if( isset($_POST['poll_type']) && $_POST['poll_type'] == '0' )
			{
				$template->assign_vars(array(
					'SELECTED_SIMPLE' => 'checked="ckecked"'
				));
			}
			elseif( isset($_POST['poll_type']) && $_POST['poll_type'] == '1' )				
			{
				$template->assign_vars(array(
					'SELECTED_MULTIPLE' => 'checked="ckecked"'
				));
			}	
			
			include_once('../includes/bbcode.php');
			$template->assign_var_from_handle('BBCODE', 'bbcode');
			
			$template->pparse('forum_post');
		}
		else
		{
			if( !$groups->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM) )
			{
				header('location:' . transid(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');
				exit;
			}	
			
			$template->set_filenames(array(
				'forum_post' => '../templates/' . $CONFIG['theme'] . '/forum/forum_post.tpl'
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
			$template->assign_var_from_handle('BBCODE', 'bbcode');
			
			$template->pparse('forum_post');
		}
	}
	elseif( $new_get === 'n_msg' && empty($error_get) ) //Nouveau message
	{
		if( !$groups->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM) )
		{
			header('location:' . transid(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');
			exit;
		}	
			
		//Verrouillé?
		$topic = $sql->query_array('forum_topics', 'idcat', 'title', 'nbr_msg', 'last_user_id', 'status', "WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);
		if( empty($topic['idcat']) )
		{
			$errorh->error_handler('e_topic_lock_forum', E_USER_REDIRECT);
			exit;
		}
		
		$is_modo = $groups->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM);
		//Catégorie verrouillée?
		$check_status = $CAT_FORUM[$id_get]['status'];
		//Déverrouillé pour admin et modo dans tous les cas
		if( $is_modo ) 
			$check_status = 1;
		
		if( $check_status == 0 ) //Verrouillée
		{
			header('location:' . transid(HOST . SCRIPT . '?error=c_locked&id=' . $id_get, '', '&') . '#errorh');
			exit;
		}
		
		//Mod anti Flood
		if( $check_time !== false ) 
		{
			$delay_expire = time() - $CONFIG['delay_flood']; //On calcul la fin du delai.			
			//Droit de flooder?
			if( $check_time >= $delay_expire && !$groups->check_auth($CONFIG_FORUM['auth'], FLOOD_FORUM) ) //Ok
			{			
				header('location:' .  transid(HOST . SCRIPT . '?error=flood&id=' . $id_get . '&idt=' . $idt_get, '', '&') . '#errorh');
				exit;
			}
		}
		
		$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
		
		//Si le topic n'est pas vérrouilé on ajoute le message.
		if( $topic['status'] != 0 || $is_modo )
		{
			if( !empty($contents) && !empty($idt_get) && empty($update) ) //Nouveau message.
			{
				#####Gestion message#####
				$sql->query_inject("INSERT INTO ".PREFIX."forum_msg (idtopic, user_id, contents, timestamp, timestamp_edit, user_id_edit, user_ip) VALUES ('" . $idt_get . "', '" . $session->data['user_id'] . "', '" . parse($contents) . "', '" . time() . "', '0', '0', '" . USER_IP . "')", __LINE__, __FILE__);
				$last_msg_id = $sql->sql_insert_id("SELECT MAX(id) FROM ".PREFIX."forum_msg"); 
				
				//Topic
				$sql->query_inject("UPDATE ".PREFIX."forum_topics SET nbr_msg = nbr_msg + 1, last_user_id='" . $session->data['user_id'] . "', last_msg_id='" . $last_msg_id . "', last_timestamp='" . time() . "' WHERE id='" . $idt_get . "'", __LINE__, __FILE__);
				
				//Catégorie
				//On met à jour le last_topic_id dans la catégorie dans le lequel le message a été posté, et le nombre de messages..
				$sql->query_inject("UPDATE ".PREFIX."forum_cats SET last_topic_id = '" . $idt_get . "', nbr_msg = nbr_msg + 1 WHERE id_left <= '" . $CAT_FORUM[$topic['idcat']]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$topic['idcat']]['id_right'] ."' AND level <= '" . $CAT_FORUM[$topic['idcat']]['level'] . "'", __LINE__, __FILE__);
								
				//Mise à jour du nombre de messages du membre.
				$sql->query_inject("UPDATE ".PREFIX."member SET user_msg = user_msg + 1 WHERE user_id = '" . $session->data['user_id'] . "'", __LINE__, __FILE__);
				
				$last_page = ceil( ($topic['nbr_msg'] + 1) / $CONFIG_FORUM['pagination_msg'] );
				$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
				$last_page = ($last_page > 1) ? '&pt=' . $last_page : ''; 
			
				#####Gestion suivi mail/mp#####
				//Message précédent ce nouveau message.
				$previous_msg_id = $sql->query("SELECT id FROM ".PREFIX."forum_msg WHERE idtopic = '" . $idt_get . "' AND id < '" . $last_msg_id . "' ORDER BY timestamp DESC " . $sql->sql_limit(0, 1), __LINE__, __FILE__);
				
				$title_subject = html_entity_decode($topic['title']);
				$title_subject_pm = '[url=' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $idt_get . $last_page, '-' . $idt_get . $last_page_rewrite . '.php') . '#m' . $previous_msg_id . ']' . $title_subject . '[/url]';			
				$title_subject_mail = "\n" . HOST . DIR . '/forum/topic' . transid('.php?id=' . $idt_get . $last_page, '-' . $idt_get . $last_page_rewrite . '.php') . '#m' . $previous_msg_id;				
				if( $session->data['user_id'] > 0 )
				{
					$pseudo = $sql->query("SELECT login FROM ".PREFIX."member WHERE user_id = '" . $session->data['user_id'] . "'", __LINE__, __FILE__); 
					$pseudo_pm = '[url=' . HOST . DIR . '/member/member.php?id=' . $session->data['user_id'] . ']' . $pseudo . '[/url]';
				}
				else
				{	
					$pseudo = $LANG['guest'];
					$pseudo_pm = $LANG['guest'];				
				}	
					
				$next_pm = '[url=' . HOST . DIR . '/forum/topic.php?id=' . $idt_get . $last_page . '#m' . $previous_msg_id . '][' . $LANG['next'] . '][/url]';
				$preview_contents = substr($contents, 0, 300);			
												
				include_once('../includes/mail.class.php');
				$mail = new Mail();				
				include_once('../includes/pm.class.php');
				$privatemsg = new Privatemsg();
				
				//Récupération des membres suivant le sujet.
				$max_time = time() - $CONFIG['site_session_invit'];
				$result = $sql->query_while("SELECT m.user_id, m.user_mail, tr.pm, tr.mail, v.last_view_id, s.session_time 
				FROM ".PREFIX."forum_track AS tr
				LEFT JOIN ".PREFIX."member AS m ON m.user_id = tr.user_id
				LEFT JOIN ".PREFIX."forum_view AS v ON v.idtopic = '" . $idt_get . "' AND v.user_id = tr.user_id
				LEFT JOIN ".PREFIX."sessions AS s ON s.user_id = tr.user_id
				WHERE tr.idtopic = '" . $idt_get . "' AND v.last_view_id IS NOT NULL AND m.user_id != '" . $session->data['user_id'] . "'", __LINE__, __FILE__);
				while($row = $sql->sql_fetch_assoc($result) )
				{
					//Envoi un Mail à ceux dont le last_view_id est le message précedent, et qui ne sont pas connectés sur le site.
					if( $row['last_view_id'] == $previous_msg_id && $row['mail'] == '1' && $row['session_time'] < $max_time ) 
						$mail->send_mail($row['user_mail'], $LANG['forum_mail_title_new_post'], sprintf($LANG['forum_mail_new_post'], $title_subject, $pseudo, $preview_contents, $title_subject_mail), $CONFIG['mail']);

					//Envoi un MP à ceux dont le last_view_id est le message précedent.
					if( $row['last_view_id'] == $previous_msg_id && $row['pm'] == '1' ) 
						$privatemsg->send_pm($row['user_id'], addslashes($LANG['forum_mail_title_new_post']), sprintf(addslashes($LANG['forum_mail_new_post']), addslashes($title_subject_pm), addslashes($pseudo_pm), $preview_contents, addslashes($next_pm)), '-1', CHECK_PM_BOX, SYSTEM_PM);
				}
				
				//Regénération du flux rss.
				include_once('../includes/rss.class.php');
				$rss = new Rss('forum/rss.php');
				$rss->cache_path('../cache/');
				$rss->generate_file('javascript', 'rss_forum');
				$rss->generate_file('php', 'rss2_forum');
				
				//Redirection après post.
				header('location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $idt_get . $last_page, '-' . $idt_get . $last_page_rewrite . '.php', '&') . '#m' . $last_msg_id);
				exit;
			}
			else
			{
				header('location:' . transid(HOST . SCRIPT . '?error=incomplete&id=' . $id_get . '&idt=' . $idt_get, '', '&') . '#errorh');
				exit;
			}
		}
		else
		{
			header('location:' . transid(HOST . SCRIPT . '?error=locked&id=' . $id_get . '&idt=' . $idt_get, '', '&') . '#errorh');
			exit;
		}
	}
	elseif( $new_get === 'msg' && empty($error_get) ) //Edition d'un message/topic.
	{
		if( !$groups->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM) )
		{
			header('location:' . transid(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#errorh');
			exit;
		}	
			
		$id_m = !empty($_GET['idm']) ? numeric($_GET['idm']) : '';		
		$update = ( !empty($_GET['update'])) ? $_GET['update'] : '';
		$id_first = $sql->query("SELECT MIN(id) FROM ".PREFIX."forum_msg WHERE idtopic = '" . $idt_get . "'", __LINE__, __FILE__);
		$topic = $sql->query_array('forum_topics', 'title', 'subtitle', 'type', 'user_id', 'display_msg', "WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);
		
		if( empty($id_get) || empty($id_first) ) //Topic/message inexistant.
		{
			$errorh->error_handler('e_unexist_topic_forum', E_USER_REDIRECT);
			exit;
		}
		
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
			{
				$errorh->error_handler('e_auth', E_USER_REDIRECT); 
				exit;
			}
			
			if( $update === 'true' && !empty($post_topic) )
			{
				$title = !empty($_POST['title']) ? securit($_POST['title']) : '';
				$subtitle = !empty($_POST['desc']) ? securit($_POST['desc']) : '';
				$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
				
				if( !empty($title) && !empty($contents) )
				{
					if( $is_modo )
						$type = isset($_POST['type']) ? numeric($_POST['type']) : 0; 
					else
						$type = 0;				
					
					$sql->query_inject("UPDATE ".PREFIX."forum_topics SET title = '" . $title . "', subtitle = '" . $subtitle . "', type = '" . $type . "' WHERE id = '" . $idt_get . "'", __LINE__, __FILE__);
								
					//Marqueur d'édition du message?
					$edit_mark = (!$groups->check_auth($CONFIG_FORUM['auth'], EDIT_MARK_FORUM)) ? ", timestamp_edit='" . time() . "', user_id_edit = '" . $session->data['user_id'] . "'" : '';
					$sql->query_inject("UPDATE ".PREFIX."forum_msg SET contents = '" . parse($contents) . "'" . $edit_mark . " WHERE id = '" . $id_m . "'", __LINE__, __FILE__);
					
					//Insertion de l'action dans l'historique.
					if($session->data['user_id'] != $user_id_msg) $sql->query_inject("INSERT INTO ".PREFIX."forum_history (action,user_id,timestamp) VALUES(11, '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
					
					//Mise à jour du sondage en plus du topic.
					$del_poll = isset($_POST['del_poll']) ? trim($_POST['del_poll']) : '';
					$question = isset($_POST['question']) ? securit($_POST['question']) : '';
					if( !empty($question) && empty($del_poll) ) //Enregistrement du sondage.
					{
						//Mise à jour si le sondage existe, sinon création.
						$check_poll = $sql->query("SELECT COUNT(*) FROM ".PREFIX."forum_poll WHERE idtopic = '" . $idt_get . "'",  __LINE__, __FILE__);
						
						$poll_type = (isset($_POST['poll_type']) && ($_POST['poll_type'] == 0 || $_POST['poll_type'] == 1)) ? numeric($_POST['poll_type']) : '0';
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
						{
							//Vérification => vérifie si il n'y a pas de nouvelle réponses à ajouter.
							$array_answer = explode('|', $sql->query("SELECT answers FROM ".PREFIX."forum_poll WHERE idtopic = '" . $idt_get . "'", __LINE__, __FILE__));
							$nbr_answer	= count($array_answer);

							$new_nbr_answer = $check_nbr_answer - $nbr_answer;
							if( $new_nbr_answer > 0 ) //Insertion de nouvelles réponses => ajout de nouveaux 0 dans le champ vote.
							{
								$votes = "votes = CONCAT(votes, '";
								for($i = $nbr_answer; $i < $check_nbr_answer; $i++) $votes .= '|0';
								$votes .= "'), ";							
							}
							elseif( $new_nbr_answer < 0 ) //Suppression d'une réponse => suppréssion des votes associés.
							{							
								//On coupe la chaîne du nombre de réponses en moins.
								$votes = "votes = SUBSTRING(votes FROM 1 FOR (CHAR_LENGTH(votes) " . ($new_nbr_answer * 2) . ")), ";
							}
							elseif( $check_poll == 1  )
								$votes = '';

							$sql->query_inject("UPDATE ".PREFIX."forum_poll SET question = '" . $question . "', answers = '" . substr($answers, 0, strlen($answers) - 1) . "', " . $votes . "type = '" . $poll_type . "' WHERE idtopic = '" . $idt_get . "'", __LINE__, __FILE__);
						}
						elseif( $check_poll == 0 ) //Création
						{
							$sql->query_inject("INSERT INTO ".PREFIX."forum_poll (idtopic,question,answers,voter_id,votes,type) VALUES ('" . $idt_get . "', '" . $question . "', '" . substr($answers, 0, strlen($answers) - 1) . "', 0, '" . substr($votes, 0, strlen($votes) - 1) . "', '" . $poll_type . "')", __LINE__, __FILE__);
						}
					}
					elseif( !empty($del_poll) && $groups->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM) ) //Suppression du sondage, admin et modo seulement biensûr...
					{
						$sql->query_inject("DELETE FROM ".PREFIX."forum_poll WHERE idtopic = '" . $idt_get . "'", __LINE__, __FILE__);
					}					
					
					//Redirection après post.
					header('location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $idt_get, '-' . $idt_get . '.php', '&'));
					exit;
				}
				else
				{
					header('location:' . HOST . DIR . '/forum/post' . transid('.php?new=msg&idm=' . $id_m . '&id=' . $id_get . '&idt=' . $idt_get . '&errore=incomplete_t', '', '&') . '#errorh');
					exit;
				}
			}
			elseif( !empty($preview_topic) )
			{
				$template->set_filenames(array(
					'forum_post' => '../templates/' . $CONFIG['theme'] . '/forum/forum_post.tpl
				'));
				
				$title = !empty($_POST['title']) ? $_POST['title'] : '';
				$subtitle = !empty($_POST['desc']) ? $_POST['desc'] : '';
				$contents = !empty($_POST['contents']) ? $_POST['contents'] : '';
				
				if( $is_modo )
					$type = isset($_POST['type']) ? numeric($_POST['type']) : '0'; 
				else
				{
					$type = isset($_POST['type']) ? numeric($_POST['type']) : ''; 
					$type = ( $type == '1' || $type == '0' ) ? $type : '0';
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
					'DATE' => $LANG['on'] . ' ' . date('d/m/Y ' . $LANG['at'] . ' H\hi', time()),
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
					'U_ACTION' => 'post.php' . transid('?update=true&amp;new=msg&amp;id=' . $id_get . '&amp;idt=' . $idt_get . '&amp;idm=' . $id_m),
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
				if( isset($_POST['poll_type']) && $_POST['poll_type'] == '0' )
				{
					$template->assign_vars(array(
						'SELECTED_SIMPLE' => 'checked="ckecked"'
					));
				}
				elseif( isset($_POST['poll_type']) && $_POST['poll_type'] == '1' )				
				{
					$template->assign_vars(array(
						'SELECTED_MULTIPLE' => 'checked="ckecked"'
					));
				}
				
				include_once('../includes/bbcode.php');
				$template->assign_var_from_handle('BBCODE', 'bbcode');
				
				$template->pparse('forum_post');
			}
			else
			{
				$template->set_filenames(array(
					'forum_post' => '../templates/' . $CONFIG['theme'] . '/forum/forum_post.tpl'
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
	
				//Affichage du lien pour changer le display_msg du topic et autorisation d'édition.
				if( $CONFIG_FORUM['activ_display_msg'] == 1 && ($is_modo || $session->data['user_id'] == $topic['user_id']) )
				{
					$template->assign_block_vars('display', array(
						'ICON_DISPLAY_MSG' => $CONFIG_FORUM['icon_activ_display_msg'] ? '<img src="' . $template->module_data_path('forum') . '/images/msg_display.png" alt="" style="vertical-align:middle;" />' : '',
						'L_DISPLAY_MSG' => $CONFIG_FORUM['display_msg'],
						'L_EXPLAIN_DISPLAY_MSG' => $topic['display_msg'] ? $CONFIG_FORUM['explain_display_msg_bis'] : $CONFIG_FORUM['explain_display_msg'],
						'U_ACTION_MSG_DISPLAY' => transid('.php?msg_d=1&amp;id=' . $idt_get)
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
					'U_ACTION' => 'post.php' . transid('?update=true&amp;new=msg&amp;id=' . $id_get . '&amp;idt=' . $idt_get . '&amp;idm=' . $id_m),
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
				$template->assign_var_from_handle('BBCODE', 'bbcode');
				
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
			{
				$errorh->error_handler('e_auth', E_USER_REDIRECT); 
				exit;
			}
			
			if( $update === 'true' && !empty($_POST['edit_msg']) )
			{
				$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
				if( !empty($contents) )
				{
					//Marqueur d'édition du message?					
					$edit_mark = (!$groups->check_auth($CONFIG_FORUM['auth'], EDIT_MARK_FORUM)) ? ", timestamp_edit = '" . time() . "', user_id_edit = '" . $session->data['user_id'] . "'" : '';
					
					$sql->query_inject("UPDATE ".PREFIX."forum_msg SET contents = '" . parse($contents) . "'" . $edit_mark . " WHERE id = '" . $id_m . "'", __LINE__, __FILE__);
					
					//Insertion de l'action dans l'historique.
					if($session->data['user_id'] != $user_id_msg) $sql->query_inject("INSERT INTO ".PREFIX."forum_history (action,user_id,timestamp) VALUES(10, '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
					
					$nbr_msg_before = $sql->query("SELECT COUNT(*) FROM ".PREFIX."forum_msg WHERE idtopic = '" . $idt_get . "' AND id < '" . $id_m . "'", __LINE__, __FILE__);
					
					//Calcul de la page sur laquelle se situe le message.
					$msg_page = ceil( ($nbr_msg_before + 1) / $CONFIG_FORUM['pagination_msg'] );
					$msg_page_rewrite = ($msg_page > 1) ? '-' . $msg_page : '';
					$msg_page = ($msg_page > 1) ? '&pt=' . $msg_page : '';
					
					//Redirection après édition.
					header('location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $idt_get . $msg_page, '-' . $idt_get .  $msg_page_rewrite . '.php', '&') . '#m' . $id_m);
					exit;
				}
				else
				{
					header('location:' . HOST . DIR . '/forum/post' . transid('.php?new=msg&idm=' . $id_m . '&id=' . $id_get . '&idt=' . $idt_get . '&errore=incomplete', '', '&') . '#errorh');
					exit;
				}
			}
			else
			{
				$template->set_filenames(array(
					'edit_msg' => '../templates/' . $CONFIG['theme'] . '/forum/forum_edit_msg.tpl'
				));
				
				$contents = $sql->query("SELECT contents FROM ".PREFIX."forum_msg WHERE id = '" . $id_m . "'", __LINE__, __FILE__);
				//Gestion des erreurs à l'édition.
				$get_error_e = !empty($_GET['errore']) ? trim($_GET['errore']) : '';
				if( $get_error_e == 'incomplete' )
					$errorh->error_handler($LANG['e_incomplete'], E_USER_NOTICE);
					
				$template->assign_vars(array(
					'P_UPDATE' => transid('?update=true&amp;new=msg&amp;id=' . $id_get . '&amp;idt=' . $idt_get . '&amp;idm=' . $id_m),
					'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
					'SID' => SID,
					'DESC' => $topic['subtitle'],
					'CONTENTS' => unparse($contents),
					'U_ACTION' => 'post.php' . transid('?update=true&amp;new=msg&amp;id=' . $id_get . '&amp;idt=' . $idt_get . '&amp;idm=' . $id_m),
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
				$template->assign_var_from_handle('BBCODE', 'bbcode');
				
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
			{
				$errorh->error_handler('e_unexist_topic_forum', E_USER_REDIRECT);
				exit;
			}
			
			$template->set_filenames(array(
				'error_post' => '../templates/' . $CONFIG['theme'] . '/forum/forum_edit_msg.tpl'
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
				'error_post' => '../templates/' . $CONFIG['theme'] . '/forum/forum_post.tpl'
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
		{
			$errorh->error_handler('unknow_error', E_USER_REDIRECT);
			exit;
		}		
			
		include_once('../includes/bbcode.php');
		$template->assign_var_from_handle('BBCODE', 'bbcode');
		$template->pparse('error_post');
	}
	else
	{
		$errorh->error_handler('unknow_error', E_USER_REDIRECT); 
		exit;
	}
}
else
{
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

include('../includes/footer.php');

?>