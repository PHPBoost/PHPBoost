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

include_once('../includes/begin.php'); 
include_once('../forum/lang/' . $CONFIG['lang'] . '/forum_' . $CONFIG['lang'] . '.php'); //Chargement de la langue du module.
include_once('../forum/forum_auth.php');

define('TITLE', $LANG['title_forum']);
define('ALTERNATIVE_CSS', 'forum');

include_once('../includes/header.php'); 

//Variables $_GET.
$id_get = !empty($_GET['id']) ? numeric($_GET['id']) : ''; //Id du topic à déplacer.
$id_post = !empty($_POST['id']) ? numeric($_POST['id']) : ''; //Id du topic à déplacer.
$id_get_msg = !empty($_GET['idm']) ? numeric($_GET['idm']) : ''; //Id du message à partir duquel il faut scinder le topic.
$id_post_msg = !empty($_POST['idm']) ? numeric($_POST['idm']) : ''; //Id du message à partir duquel il faut scinder le topic.
$error_get = !empty($_GET['error']) ? securit($_GET['error']) : ''; //Gestion des erreurs.
$post_topic = !empty($_POST['post_topic']) ? trim($_POST['post_topic']) : ''; //Création du topic scindé.
$preview_topic = !empty($_POST['prw_t']) ? trim($_POST['prw_t']) : ''; //Prévisualisation du topic scindé.

if( !$groups->check_auth($SECURE_MODULE['forum'], ACCESS_MODULE) )
{
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

if( !empty($id_get) )
{
	$template->set_filenames(array(
		'forum_move' => '../templates/' . $CONFIG['theme'] . '/forum/forum_move.tpl'
	));

	$topic = $sql->query_array('forum_topics', 'idcat', 'title', "WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
	if( !$groups->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM) ) //Accès en édition
	{
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
		exit;
	}
	
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
	
	$template->pparse('forum_move');
}
elseif( !empty($id_post) ) //Déplacement du topic
{
	$idcat = $sql->query("SELECT idcat FROM ".PREFIX."forum_topics WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
	if( $groups->check_auth($CAT_FORUM[$idcat]['auth'], EDIT_CAT_FORUM) ) //Accès en édition
	{		
		$to = !empty($_POST['to']) ? numeric($_POST['to']) : $idcat; //Catégorie cible.
		$level = $sql->query("SELECT level FROM ".PREFIX."forum_cats WHERE id = '" . $to . "'", __LINE__, __FILE__);
		if( !empty($to) && $level > 0 )
		{
			//On va chercher le nombre de messages dans la table topics
			$nbr_msg = $sql->query("SELECT nbr_msg FROM ".PREFIX."forum_topics WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
			$nbr_msg = !empty($nbr_msg) ? numeric($nbr_msg)  : 0;
			
			//On déplace le topic dans la nouvelle catégorie
			$sql->query_inject("UPDATE ".PREFIX."forum_topics SET idcat = '" . $to . "' WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
			
			//On met à jour l'ancienne table
			$last_topic_id = $sql->query("SELECT id 
			FROM ".PREFIX."forum_topics WHERE idcat = '" . $idcat . "' 
			ORDER BY last_timestamp DESC 
			" . $sql->sql_limit(0, 1), __LINE__, __FILE__);
			
			$sql->query_inject("UPDATE ".PREFIX."forum_cats SET nbr_msg = nbr_msg - '" . $nbr_msg . "', nbr_topic = nbr_topic - 1, last_topic_id = '" . $last_topic_id . "' WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
			
			//On met à jour la nouvelle table
			$last_topic_id = $sql->query("SELECT id FROM ".PREFIX."forum_topics WHERE idcat = '" . $to . "' ORDER BY last_timestamp DESC " . $sql->sql_limit(0, 1), __LINE__, __FILE__);
			$sql->query_inject("UPDATE ".PREFIX."forum_cats SET nbr_msg = nbr_msg + " . $nbr_msg . ", nbr_topic = nbr_topic + 1, last_topic_id = '" . $last_topic_id . "' WHERE id = '" . $to . "'", __LINE__, __FILE__);
			
			//Insertion de l'action dans l'historique.
			$sql->query_inject("INSERT INTO ".PREFIX."forum_history (action,user_id,timestamp) VALUES(4, '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
			
			header('Location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $id_post, '-' .$id_post  . '.php', '&'));
			exit;
		}
		else
		{
			$errorh->error_handler('e_incomplete', E_USER_REDIRECT); 
		exit;
		}
	}
	else
	{
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
		exit;
	}	
}
elseif( (!empty($id_get_msg) || !empty($id_post_msg)) && empty($post_topic) ) //Choix de la nouvelle catégorie, titre, sous-titre du topic à scinder.
{
	$template->set_filenames(array(
		'forum_move' => '../templates/' . $CONFIG['theme'] . '/forum/forum_post.tpl'
	));

	$idm = !empty($id_get_msg) ? $id_get_msg : $id_post_msg;
	$msg =  $sql->query_array('forum_msg', 'idtopic', 'contents', "WHERE id = '" . $idm . "'", __LINE__, __FILE__);
	$topic = $sql->query_array('forum_topics', 'idcat', 'title', "WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
	
	if( !$groups->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM) ) //Accès en édition
	{
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
		exit;
	}

	$id_first = $sql->query("SELECT MIN(id) as id FROM ".PREFIX."forum_msg WHERE idtopic = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
	//Scindage du premier message interdite.
	if( $id_first == $idm )
	{
		$errorh->error_handler('e_unable_cut_forum', E_USER_REDIRECT); 
		exit;
	}
			
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
		$disabled = ($row['id'] == $topic['idcat']) ? ' disabled="disabled"' : '';
		$cat_forum .= ($row['level'] > 0) ? '<option value="' . $row['id'] . '"' . $disabled . '>' . str_repeat('--------', $row['level']) . ' ' . $row['name'] . '</option>' : '<option value="' . $row['id'] . '" disabled="disabled">-- ' . $row['name'] . '</option>';
	}
	$sql->close($result);
	
	$template->assign_block_vars('cut_cat', array(
		'CATEGORIES' => $cat_forum
	));
	
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'LANG' => $CONFIG['lang'],
		'FORUM_NAME' => $CONFIG_FORUM['forum_name'],
		'SID' => SID,			
		'U_ACTION' => 'move.php',
		'U_TITLE_T' => '<a href="topic' . transid('.php?id=' . $msg['idtopic'], '-' . $msg['idtopic'] . '.php') . '">' . ucfirst($topic['title']) . '</a>',	
		'U_FORUM_CAT' => '<a href="forum' . transid('.php?id=' . $cat['id'], '-' . $cat['id'] . '.php') . '">' . $cat['name'] . '</a>',
		'L_ACTION' => $LANG['forum_cut_subject'] . ' : ' . $topic['title'],
		'L_REQUIRE' => $LANG['require'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
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
		$title = !empty($_POST['title']) ? $_POST['title'] : '';
		$subtitle = !empty($_POST['desc']) ? $_POST['desc'] : '';
		$contents = !empty($_POST['contents']) ? $_POST['contents'] : '';
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
			'DATE' => $LANG['on'] . ' ' . date('d/m/Y ' . $LANG['at'] . ' H\hi', time()),
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
	$template->assign_var_from_handle('BBCODE', 'bbcode');
	
	$template->pparse('forum_move');
}
elseif( !empty($id_post_msg) && !empty($post_topic) ) //Scindage du topic
{
	$msg =  $sql->query_array('forum_msg', 'idtopic', 'user_id', 'timestamp', 'contents', "WHERE id = '" . $id_post_msg . "'", __LINE__, __FILE__);
	$topic = $sql->query_array('forum_topics', 'idcat', 'title', 'last_user_id', 'last_msg_id', 'last_timestamp', "WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
	$to = !empty($_POST['to']) ? numeric($_POST['to']) : ''; //Catégorie cible.
	
	if( !$groups->check_auth($CAT_FORUM[$topic['idcat']]['auth'], EDIT_CAT_FORUM) ) //Accès en édition
	{
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
		exit;
	}
	
	$id_first = $sql->query("SELECT MIN(id) as id FROM ".PREFIX."forum_msg WHERE idtopic = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
	//Scindage du premier message interdite.
	if( $id_first == $id_post_msg )
	{
		$errorh->error_handler('e_unable_cut_forum', E_USER_REDIRECT); 
		exit;
	}
	
	$level = $sql->query("SELECT level FROM ".PREFIX."forum_cats WHERE id = '" . $to . "'", __LINE__, __FILE__);
	if( !empty($to) && $level > 0 )
	{
		$type = isset($_POST['type']) ? numeric($_POST['type']) : 0; 
		$contents = !empty($msg['contents']) ? parse($msg['contents']) : ''; 
		$title = !empty($_POST['title']) ? securit($_POST['title']) : ''; 
		$subtitle = !empty($_POST['desc']) ? securit($_POST['desc']) : ''; 
		
		//Requête de "scindage" du topic.
		if( !empty($to) && !empty($contents) && !empty($title) && isset($type) )
		{
			//Calcul du nombre de messages déplacés.
			$nbr_msg = $sql->query("SELECT COUNT(*) as compt FROM ".PREFIX."forum_msg WHERE idtopic = '" . $msg['idtopic'] . "' AND id >= '" . $id_post_msg . "'", __LINE__, __FILE__);
			$nbr_msg = !empty($nbr_msg) ? numeric($nbr_msg)  : 0;
			
			//Insertion nouveau topic.
			$sql->query_inject("INSERT INTO ".PREFIX."forum_topics (idcat,title,subtitle,user_id,nbr_msg,nbr_views,last_user_id,last_msg_id,last_timestamp,first_msg_id,type,status,aprob) VALUES ('" . $to . "', '" . $title . "', '" . $subtitle . "', '" . $msg['user_id'] . "', '" . $nbr_msg . "', 0, '" . $topic['last_user_id'] . "', '" . $topic['last_msg_id'] . "', '" . $topic['last_timestamp'] . "', '" . $id_post_msg . "', '" . $type . "', 1, 0)", __LINE__, __FILE__);
			$last_topic_id = $sql->sql_insert_id("SELECT MAX(id) FROM ".PREFIX."forum_topics");	//Dernier topic inseré
		
			//Ajout d'un sondage en plus du topic.
			$question = isset($_POST['question']) ? securit($_POST['question']) : '';
			if( !empty($question) )
			{
				$poll_type = (isset($_POST['poll_type']) && ($_POST['poll_type'] == 0 || $_POST['poll_type'] == 1)) ? numeric($_POST['poll_type']) : '0';
				$answers = '';
				$votes = '';
				for($i = 0; $i < 20; $i++)
					if( !empty($_POST['a'.$i]) )
					{				
						$answers .= securit($_POST['a'.$i]) . '|';
						$votes .= '0|';
					}
					
				$sql->query_inject("INSERT INTO ".PREFIX."forum_poll (idtopic,question,answers,voter_id,votes,type) VALUES ('" . $last_topic_id . "', '" . $question . "', '" . substr($answers, 0, strlen($answers) - 1) . "', 0, '" . substr($votes, 0, strlen($votes) - 1) . "', '" . $poll_type . "')", __LINE__, __FILE__);
			}

			//Déplacement des messages.
			$sql->query_inject("UPDATE ".PREFIX."forum_msg SET idtopic = '" . $last_topic_id . "' WHERE idtopic = '" . $msg['idtopic'] . "' AND id >= '" . $id_post_msg . "'", __LINE__, __FILE__);
			
			//Mise à jour de l'ancien topic
			$previous_topic = $sql->query_array('forum_msg', 'id', 'user_id', 'timestamp', "WHERE id < '" . $id_post_msg . "' AND idtopic = '" . $msg['idtopic'] . "' ORDER BY timestamp DESC " . $sql->sql_limit(0, 1), __LINE__, __FILE__);
			$sql->query_inject("UPDATE ".PREFIX."forum_topics SET last_user_id = '" . $previous_topic['user_id'] . "', last_msg_id = '" . $previous_topic['id'] . "', nbr_msg = nbr_msg - " . $nbr_msg . ", last_timestamp = '" . $previous_topic['timestamp'] . "'  WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
			
			//Mise à jour de l'ancienne catégorie, si elle est différente.
			if( $topic['idcat'] != $to )
			{
				//Mise à jour du nombre de messages de la nouvelle catégorie, ainsi que du last_topic_id.
				$cat_last_topic_id = $sql->query("SELECT id FROM ".PREFIX."forum_topics WHERE idcat = '" . $to . "' ORDER BY last_timestamp DESC " . $sql->sql_limit(0, 1), __LINE__, __FILE__);
				$sql->query_inject("UPDATE ".PREFIX."forum_cats SET nbr_topic = nbr_topic + 1, nbr_msg = nbr_msg + " . $nbr_msg . ", last_topic_id = '" . $cat_last_topic_id . "' WHERE id = '" . $to . "'", __LINE__, __FILE__);
				
				//Mise à jour du nombre de messages de l'ancienne catégorie, ainsi que du last_topic_id.
				$previous_cat_last_topic_id = $sql->query("SELECT id FROM ".PREFIX."forum_topics WHERE idcat = '" . $topic['idcat'] . "' ORDER BY last_timestamp DESC " . $sql->sql_limit(0, 1), __LINE__, __FILE__);
				$sql->query_inject("UPDATE ".PREFIX."forum_cats SET nbr_msg = nbr_msg - " . $nbr_msg . ", last_topic_id = '" . $previous_cat_last_topic_id . "' WHERE id = '" . $topic['idcat'] . "'", __LINE__, __FILE__);		
				
				//On marque comme lu le message avant le message scindé qui est le dernier message de l'ancienne catégorie pour tous les utilisateurs.
				$sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."forum_view SET last_view_id = '" . $previous_topic['id'] . "', timestamp = '" . time() . "' WHERE idtopic = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
			}
			else
			{	
				//Mise à jour du nombre de messages de la catégorie, ainsi que du last_topic_id.
				$cat_last_topic_id = $sql->query("SELECT id FROM ".PREFIX."forum_topics WHERE idcat = '" . $topic['idcat'] . "' ORDER BY last_timestamp DESC " . $sql->sql_limit(0, 1), __LINE__, __FILE__);
				$sql->query_inject("UPDATE ".PREFIX."forum_cats SET nbr_topic = nbr_topic + 1, last_topic_id = '" . $cat_last_topic_id . "' WHERE id = '" . $topic['idcat'] . "'", __LINE__, __FILE__);
				
				//On marque comme lu le message avant le message scindé qui est le dernier message de l'ancienne catégorie pour tous les utilisateurs.
				$sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."forum_view SET last_view_id = '" . $previous_topic['id'] . "', timestamp = '" . time() . "' WHERE idtopic = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
			}
			
			//Insertion de l'action dans l'historique.
			$sql->query_inject("INSERT INTO ".PREFIX."forum_history (action,user_id,timestamp) VALUES(5, '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
			
			header('location:' . HOST . DIR . '/forum/topic' . transid('.php?id=' . $last_topic_id, '-' . $last_topic_id . '.php', '&'));
			exit;
		}
		else
		{
			header('location:' . transid(HOST . SCRIPT . '?error=false_t&idm=' . $id_post_msg, '', '&') . '#errorh');
			exit;
		}
	}
	else
	{
		$errorh->error_handler('e_incomplete', E_USER_REDIRECT); 
		exit;
	}	
}
else
{
	$errorh->error_handler('unknow_error', E_USER_REDIRECT); 
	exit;
}

include('../includes/footer.php');

?>