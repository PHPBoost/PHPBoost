<?php
/*##################################################
 *                               move.php
 *                            -------------------
 *   begin                : October 30, 2005
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
speed_bar_generate($SPEED_BAR, $CONFIG_FORUM['forum_name'], 'index.php' . SID);
define('TITLE', $LANG['title_forum']);
require_once('../includes/header.php'); 

//Variables $_GET.
$id_get = !empty($_GET['id']) ? numeric($_GET['id']) : ''; //Id du topic à déplacer.
$id_post = !empty($_POST['id']) ? numeric($_POST['id']) : ''; //Id du topic à déplacer.
$id_get_msg = !empty($_GET['idm']) ? numeric($_GET['idm']) : ''; //Id du message à partir duquel il faut scinder le topic.
$id_post_msg = !empty($_POST['idm']) ? numeric($_POST['idm']) : ''; //Id du message à partir duquel il faut scinder le topic.
$error_get = !empty($_GET['error']) ? securit($_GET['error']) : ''; //Gestion des erreurs.
$post_topic = !empty($_POST['post_topic']) ? trim($_POST['post_topic']) : ''; //Création du topic scindé.
$preview_topic = !empty($_POST['prw_t']) ? trim($_POST['prw_t']) : ''; //Prévisualisation du topic scindé.

if( !empty($id_get) ) //Déplacement du sujet.
{
	$template->set_filenames(array(
		'forum_move' => '../templates/' . $CONFIG['theme'] . '/forum/forum_move.tpl',
		'forum_top' => '../templates/' . $CONFIG['theme'] . '/forum/forum_top.tpl',
		'forum_bottom' => '../templates/' . $CONFIG['theme'] . '/forum/forum_bottom.tpl'
	));

	$topic = $sql->query_array('forum_topics', 'idcat', 'title', "WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
	if( !$groups->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM) ) //Accès en édition
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	
	$template->assign_block_vars('move', array(
	));
	
	$cat = $sql->query_array('forum_cats', 'id', 'name', "WHERE id = '" . $topic['idcat'] . "'", __LINE__, __FILE__);
	
	$auth_cats = '';
	if( is_array($CAT_FORUM) )
	{
		foreach($CAT_FORUM as $idcat => $key)
		{
			if( !$groups->check_auth($CAT_FORUM[$idcat]['auth'], READ_CAT_FORUM) )
				$auth_cats .= $idcat . ',';
		}
		$auth_cats = !empty($auth_cats) ? "WHERE id NOT IN (" . trim($auth_cats, ',') . ")" : '';
	}

	//Listing des catégories disponibles, sauf celle qui va être supprimée.			
	$cat_forum = '<option value="0" checked="checked">' . $LANG['root'] . '</option>';
	$result = $sql->query_while("SELECT id, name, level
	FROM ".PREFIX."forum_cats 
	" . $auth_cats . "
	ORDER BY id_left", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{	
		$disabled = ($row['id'] == $topic['idcat']) ? ' disabled="disabled"' : '';
		$cat_forum .= ($row['level'] > 0) ? '<option value="' . $row['id'] . '"' . $disabled . '>' . str_repeat('--------', $row['level']) . ' ' . $row['name'] . '</option>' : '<option value="' . $row['id'] . '" disabled="disabled">-- ' . $row['name'] . '</option>';
	}
	$sql->close($result);
	
	$template->assign_vars(array(
		'SID' => SID,
		'MODULE_DATA_PATH' => $template->module_data_path('forum'),
		'FORUM_NAME' => $CONFIG_FORUM['forum_name'] . ' : ' . $LANG['move_topic'],
		'ID' => $id_get,
		'TITLE' => $topic['title'],
		'CATEGORIES' => $cat_forum,
		'U_FORUM_CAT' => '<a href="forum' . transid('.php?id=' . $cat['id'], '-' . $cat['id'] . '.php') . '">' . $cat['name'] . '</a>',
		'U_TITLE_T' => '<a href="topic' . transid('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $topic['title'] . '</a>',
		'L_SELECT_SUBCAT' => $LANG['require_subcat'],
		'L_MOVE_SUBJECT' => $LANG['forum_move_subject'],
		'L_CAT' => $LANG['category'],
		'L_FORUM_INDEX' => $LANG['forum_index'],
		'L_SUBMIT' => $LANG['submit']
	));	

	//Listes les utilisateurs en lignes.	
	list($total_admin, $total_modo, $total_member, $total_visit, $users_list) = array(0, 0, 0, 0, '');
	$result = $sql->query_while("SELECT s.user_id, s.level, m.login 
	FROM ".PREFIX."sessions s 
	LEFT JOIN ".PREFIX."member m ON m.user_id = s.user_id 
	WHERE s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' AND s.session_script LIKE '/forum/%'
	ORDER BY s.session_time DESC", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		switch( $row['level'] ) //Coloration du membre suivant son level d'autorisation. 
		{ 		
			case -1:
			$status = 'visiteur';
			$total_visit++;
			break;			
			case 0:
			$status = 'member';
			$total_member++;
			break;			
			case 1: 
			$status = 'modo';
			$total_modo++;
			break;			
			case 2: 
			$status = 'admin';
			$total_admin++;
			break;
		} 
		$coma = !empty($users_list) && $row['level'] != -1 ? ', ' : '';
		$users_list .= (!empty($row['login']) && $row['level'] != -1) ?  $coma . '<a href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" class="' . $status . '">' . $row['login'] . '</a>' : '';
	}
	$sql->close($result);

	$total_online = $total_admin + $total_modo + $total_member + $total_visit;
	$template->assign_vars(array(
		'TOTAL_ONLINE' => $total_online,
		'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
		'ADMIN' => $total_admin,
		'MODO' => $total_modo,
		'MEMBER' => $total_member,
		'GUEST' => $total_visit,
		'L_CONFIRM_READ_TOPICS' => $LANG['confirm_mark_as_read'],
		'L_SEARCH' => $LANG['search'],
		'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
		'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
		'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
		'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
		'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
		'L_AND' => $LANG['and'],
		'L_ONLINE' => strtolower($LANG['online']),
		'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a> &bull;',
		'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' . SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . '</a> &bull;',
		'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . SID . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a> &bull;',
		'U_MSG_SET_VIEW' => '<a class="small_link" href="../forum/action' . transid('.php?read=1', '') . '" title="' . $LANG['mark_as_read'] . '" onClick="javascript:return Confirm_read_topics();">' . $LANG['mark_as_read'] . '</a>'
	));
	
	$template->pparse('forum_move');
}
elseif( !empty($id_post) ) //Déplacement du topic
{
	$idcat = $sql->query("SELECT idcat FROM ".PREFIX."forum_topics WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
	if( $groups->check_auth($CAT_FORUM[$idcat]['auth'], EDIT_CAT_FORUM) ) //Accès en édition
	{		
		$to = !empty($_POST['to']) ? numeric($_POST['to']) : $idcat; //Catégorie cible.
		$level = $sql->query("SELECT level FROM ".PREFIX."forum_cats WHERE id = '" . $to . "'", __LINE__, __FILE__);
		if( !empty($to) && $level > 0 && $idcat != $to )
		{
			//Instanciation de la class du forum.
			include_once('../forum/forum.class.php');
			$forumfct = new Forum;

			$forumfct->move_topic($id_post, $idcat, $to); //Déplacement du topic

			redirect(HOST . DIR . '/forum/topic' . transid('.php?id=' . $id_post, '-' .$id_post  . '.php', '&'));
		}
		else
			$errorh->error_handler('e_incomplete', E_USER_REDIRECT); 
	}
	else
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
}
elseif( (!empty($id_get_msg) || !empty($id_post_msg)) && empty($post_topic) ) //Choix de la nouvelle catégorie, titre, sous-titre du topic à scinder.
{
	$template->set_filenames(array(
		'forum_move' => '../templates/' . $CONFIG['theme'] . '/forum/forum_post.tpl',
		'forum_top' => '../templates/' . $CONFIG['theme'] . '/forum/forum_top.tpl',
		'forum_bottom' => '../templates/' . $CONFIG['theme'] . '/forum/forum_bottom.tpl'
	));

	$idm = !empty($id_get_msg) ? $id_get_msg : $id_post_msg;
	$msg =  $sql->query_array('forum_msg', 'idtopic', 'contents', "WHERE id = '" . $idm . "'", __LINE__, __FILE__);
	$topic = $sql->query_array('forum_topics', 'idcat', 'title', "WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
	
	if( !$groups->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM) ) //Accès en édition
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 

	$id_first = $sql->query("SELECT MIN(id) as id FROM ".PREFIX."forum_msg WHERE idtopic = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
	//Scindage du premier message interdite.
	if( $id_first == $idm )
		$errorh->error_handler('e_unable_cut_forum', E_USER_REDIRECT); 
			
	$cat = $sql->query_array('forum_cats', 'id', 'name', "WHERE id = '" . $topic['idcat'] . "'", __LINE__, __FILE__);
	$to = !empty($_POST['to']) ? numeric($_POST['to']) : $cat['id']; //Catégorie cible.
	
	$auth_cats = '';
	if( is_array($CAT_FORUM) )
	{	
		foreach($CAT_FORUM as $idcat => $key)
		{
			if( !$groups->check_auth($CAT_FORUM[$idcat]['auth'], READ_CAT_FORUM) )
				$auth_cats .= $idcat . ',';
		}
		$auth_cats = !empty($auth_cats) ? "WHERE id NOT IN (" . trim($auth_cats, ',') . ")" : '';
	}
	
	//Listing des catégories disponibles, sauf celle qui va être supprimée.			
	$cat_forum = '<option value="0" checked="checked">' . $LANG['root'] . '</option>';
	$result = $sql->query_while("SELECT id, name, level
	FROM ".PREFIX."forum_cats 
	" . $auth_cats . "
	ORDER BY id_left", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{	
		$cat_forum .= ($row['level'] > 0) ? '<option value="' . $row['id'] . '">' . str_repeat('--------', $row['level']) . ' ' . $row['name'] . '</option>' : '<option value="' . $row['id'] . '" disabled="disabled">-- ' . $row['name'] . '</option>';
	}
	$sql->close($result);
	
	$template->assign_block_vars('cut_cat', array(
		'CATEGORIES' => $cat_forum
	));
	
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'LANG' => $CONFIG['lang'],
		'MODULE_DATA_PATH' => $template->module_data_path('forum'),
		'FORUM_NAME' => $CONFIG_FORUM['forum_name'] . ' : ' . $LANG['cut_topic'],
		'SID' => SID,			
		'U_ACTION' => 'move.php',
		'U_TITLE_T' => '<a href="topic' . transid('.php?id=' . $msg['idtopic'], '-' . $msg['idtopic'] . '.php') . '">' . ucfirst($topic['title']) . '</a>',	
		'U_FORUM_CAT' => '<a href="forum' . transid('.php?id=' . $cat['id'], '-' . $cat['id'] . '.php') . '">' . $cat['name'] . '</a>',
		'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a> &bull;',
		'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' . SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . '</a> &bull;',
		'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . SID . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a> &bull;',
		'U_MSG_SET_VIEW' => '<a class="small_link" href="../forum/action' . transid('.php?read=1', '') . '" title="' . $LANG['mark_as_read'] . '" onClick="javascript:return Confirm_read_topics();">' . $LANG['mark_as_read'] . '</a>',
		'L_ACTION' => $LANG['forum_cut_subject'] . ' : ' . $topic['title'],
		'L_REQUIRE' => $LANG['require'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_FORUM_INDEX' => $LANG['forum_index'],
		'L_CONFIRM_READ_TOPICS' => $LANG['confirm_mark_as_read'],
		'L_SEARCH' => $LANG['search'],
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
	));
		
	if( empty($post_topic) && empty($preview_topic) )
	{
		$template->assign_block_vars('type', array(
			'CHECKED_NORMAL' => 'checked="checked"',
			'L_TYPE' => '* ' . $LANG['type'],
			'L_DEFAULT' => $LANG['default'],
			'L_POST_IT' => $LANG['forum_postit'],
			'L_ANOUNCE' => $LANG['forum_announce']
		));
		
		$template->assign_vars(array(
			'TITLE' => '',
			'DESC' => '',
			'CONTENTS' => unparse($msg['contents']),
			'SELECTED_SIMPLE' => 'checked="ckecked"',
			'IDM' => $id_get_msg,
		));
	}
	elseif( !empty($preview_topic) && !empty($id_post_msg) )
	{
		$title = !empty($_POST['title']) ? trim($_POST['title']) : '';
		$subtitle = !empty($_POST['desc']) ? trim($_POST['desc']) : '';
		$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
		$type = isset($_POST['type']) ? numeric($_POST['type']) : 0; 
		
		$checked_normal = ($type == 0) ? 'checked="ckecked"' : '';
		$checked_postit = ($type == 1) ? 'checked="ckecked"' : '';
		$checked_annonce = ($type == 2) ? 'checked="ckecked"' : '';
		
		$template->assign_block_vars('type', array(
			'CHECKED_NORMAL' => $checked_normal,
			'CHECKED_POSTIT' => $checked_postit,
			'CHECKED_ANNONCE' => $checked_annonce,
			'L_TYPE' => '* ' . $LANG['type'],
			'L_DEFAULT' => $LANG['default'],
			'L_POST_IT' => $LANG['forum_postit'],
			'L_ANOUNCE' => $LANG['forum_announce']
		));

		$template->assign_block_vars('show_msg', array(
			'L_PREVIEW' => $LANG['preview'],
			'DATE' => $LANG['on'] . ' ' . gmdate_format('date_format'),
			'CONTENTS' => second_parse(stripslashes(parse($contents)))
		));
		
		$template->assign_vars(array(	
			'TITLE' => stripslashes($title),
			'DESC' => stripslashes($subtitle),
			'CONTENTS' => stripslashes($contents),
			'QUESTION' => !empty($_POST['question']) ? stripslashes($_POST['question']) : '',
			'IDM' => $id_post_msg,
		));
		
		//Liste des choix des sondages => 20 maxi
		for($i = 0; $i < 20; $i++)
			$template->assign_vars(array(
				'ANSWER' . $i => !empty($_POST['a'.$i]) ? stripslashes($_POST['a'.$i]) : ''
			));
		
		//Type de réponses du sondage.
		if( isset($_POST['poll_type']) && $_POST['poll_type'] == '0' )
			$template->assign_vars(array(
				'SELECTED_SIMPLE' => 'checked="ckecked"'
			));
		elseif( isset($_POST['poll_type']) && $_POST['poll_type'] == '1' )				
			$template->assign_vars(array(
				'SELECTED_MULTIPLE' => 'checked="ckecked"'
			));
	}
			
	include_once('../includes/bbcode.php');
	
	//Listes les utilisateurs en lignes.
	list($total_admin, $total_modo, $total_member, $total_visit, $users_list) = array(0, 0, 0, 0, '');
	$result = $sql->query_while("SELECT s.user_id, s.level, m.login 
	FROM ".PREFIX."sessions s 
	LEFT JOIN ".PREFIX."member m ON m.user_id = s.user_id 
	WHERE s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' AND s.session_script LIKE '/forum/%'
	ORDER BY s.session_time DESC", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		switch( $row['level'] ) //Coloration du membre suivant son level d'autorisation. 
		{ 		
			case -1:
			$status = 'visiteur';
			$total_visit++;
			break;			
			case 0:
			$status = 'member';
			$total_member++;
			break;			
			case 1: 
			$status = 'modo';
			$total_modo++;
			break;			
			case 2: 
			$status = 'admin';
			$total_admin++;
			break;
		} 
		$coma = !empty($users_list) && $row['level'] != -1 ? ', ' : '';
		$users_list .= (!empty($row['login']) && $row['level'] != -1) ?  $coma . '<a href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" class="' . $status . '">' . $row['login'] . '</a>' : '';
	}
	$sql->close($result);

	$total_online = $total_admin + $total_modo + $total_member + $total_visit;
	$template->assign_vars(array(
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
	
	$template->pparse('forum_move');
}
elseif( !empty($id_post_msg) && !empty($post_topic) ) //Scindage du topic
{
	$msg =  $sql->query_array('forum_msg', 'idtopic', 'user_id', 'timestamp', 'contents', "WHERE id = '" . $id_post_msg . "'", __LINE__, __FILE__);
	$topic = $sql->query_array('forum_topics', 'idcat', 'title', 'last_user_id', 'last_msg_id', 'last_timestamp', "WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
	$to = !empty($_POST['to']) ? numeric($_POST['to']) : ''; //Catégorie cible.
	
	if( !$groups->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM) ) //Accès en édition
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	
	$id_first = $sql->query("SELECT MIN(id) FROM ".PREFIX."forum_msg WHERE idtopic = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
	//Scindage du premier message interdite.
	if( $id_first == $id_post_msg )
		$errorh->error_handler('e_unable_cut_forum', E_USER_REDIRECT); 
	
	$level = $sql->query("SELECT level FROM ".PREFIX."forum_cats WHERE id = '" . $to . "'", __LINE__, __FILE__);
	if( !empty($to) && $level > 0 )
	{
		$type = isset($_POST['type']) ? numeric($_POST['type']) : 0; 
		$contents = !empty($msg['contents']) ? parse($msg['contents']) : ''; 
		$title = !empty($_POST['title']) ? securit($_POST['title']) : ''; 
		$subtitle = !empty($_POST['desc']) ? securit($_POST['desc']) : ''; 
		
		//Requête de "scindage" du topic.
		if( !empty($to) && !empty($contents) && !empty($title) )
		{
			//Instanciation de la class du forum.
			include_once('../forum/forum.class.php');
			$forumfct = new Forum;

			$last_topic_id = $forumfct->cut_topic($id_post_msg, $msg['idtopic'], $topic['idcat'], $to, $title, $subtitle, $contents, $type, $msg['user_id'], $topic['last_user_id'], $topic['last_msg_id'], $topic['last_timestamp']); //Scindement du topic
			
			//Ajout d'un sondage en plus du topic.
			$question = isset($_POST['question']) ? securit($_POST['question']) : '';
			if( !empty($question) )
			{
				$poll_type = isset($_POST['poll_type']) ? numeric($_POST['poll_type']) : 0;
				$poll_type = ($poll_type == 0 || $poll_type == 1) ? $poll_type : 0;
				$answers = '';
				$votes = '';
				for($i = 0; $i < 20; $i++)
					if( !empty($_POST['a'.$i]) )
					{				
						$answers .= securit($_POST['a'.$i]) . '|';
						$votes .= '0|';
					}
					
				$forumfct->add_poll($last_topic_id, $question, $answers, 0, $votes, $poll_type); //Ajout du sondage.
			}
			
			redirect(HOST . DIR . '/forum/topic' . transid('.php?id=' . $last_topic_id, '-' . $last_topic_id . '.php', '&'));
		}
		else
			redirect(transid(HOST . SCRIPT . '?error=false_t&idm=' . $id_post_msg, '', '&') . '#errorh');
	}
	else
		$errorh->error_handler('e_incomplete', E_USER_REDIRECT); 
}
else
	$errorh->error_handler('unknow_error', E_USER_REDIRECT); 

include('../includes/footer.php');

?>