<?php
/*##################################################
 *                               moderation_forum.php
 *                            -------------------
 *   begin                : August 8, 2006
 *   copyright          : (C) 2006 Sautel Benoît / Viarre Régis
 *   email                :  ben.popeye@phpboost.com / crowkait@phpboost.com
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

$action = !empty($_GET['action']) ? trim($_GET['action']) : '';
$id_get = !empty($_GET['id']) ? numeric($_GET['id']) : 0;
$new_status = isset($_GET['new_status']) ? numeric($_GET['new_status']) : '';
$get_del = !empty($_GET['del']) ? numeric($_GET['del']) : '';

$speed_bar = array(	
	$LANG['moderation_panel'] => '../member/moderation_panel.php' . SID,
	$LANG['title_forum'] => 'moderation_forum.php' . SID
);
if( $action == 'alert' )
	$speed_bar[$LANG['alert_management']] = 'moderation_forum.php?action=alert';
elseif( $action == 'users' )
	$speed_bar[$LANG['warning_management']] = 'moderation_forum.php?action=warning';
define('TITLE', $LANG['title_forum'] . ' - ' . $LANG['moderation_panel']);
define('ALTERNATIVE_CSS', 'forum');

include_once('../includes/header.php');

if( !$groups->check_auth($SECURE_MODULE['forum'], ACCESS_MODULE) )
{
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

//Au moins modérateur sur une catégorie du forum, ou modérateur global.
$check_auth_by_group = false;
if( is_array($CAT_FORUM) )
{
	foreach($CAT_FORUM as $idcat => $value)
	{
		if( $groups->check_auth($CAT_FORUM[$idcat]['auth'], EDIT_CAT_FORUM) )
		{
			$check_auth_by_group = true;
			break;
		}
	}
}

if( !$session->check_auth($session->data, 1) && $check_auth_by_group !== true ) //Si il n'est pas modérateur (total ou partiel)
{
	$errorh->error_handler('e_auth', E_USER_REDIRECT);
	exit;
}

$template->set_filenames(array(
	'forum_moderation_panel' => '../templates/' . $CONFIG['theme'] . '/forum/forum_moderation_panel.tpl'
));
$template->assign_vars(array(
	'SID' => SID,
	'LANG' => $CONFIG['lang'],
	'THEME' => $CONFIG['theme'],
	'L_USERS_PUNISHMENT' => $LANG['punishment_management'],
	'L_USERS_WARNING' => $LANG['warning_management'],
	'L_ALERT_MANAGEMENT' => $LANG['alert_management'],
));

//Redirection changement de catégorie.
$id_topic_get = !empty($_POST['change_cat']) ? numeric($_POST['change_cat']) : 0;
if( !empty($id_topic_get) )
{	
	//On va chercher les infos sur le topic	
	$topic = !empty($id_topic_get) ? $sql->query_array('forum_topics', 'idcat', 'title', "WHERE id = '" . $id_topic_get . "'", __LINE__, __FILE__) : '';

	//Informations sur la catégorie du topic, en cache $CAT_FORUM variable globale.
	$CAT_FORUM[$topic['idcat']]['secure'] = '2';
	$cache->load_file('forum');

	//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
	$rewrited_cat_title = ($CONFIG['rewrite'] == 1) ? '+' . url_encode_rewrite($CAT_FORUM[$topic['idcat']]['name']) : '';
	//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
	$rewrited_title = ($CONFIG['rewrite'] == 1) ? '+' . url_encode_rewrite($topic['title']) : '';

	header('location:' . HOST . DIR . '/forum/forum' . transid('.php?id=' . $id_topic_get, '-' . $id_topic_get . $rewrited_cat_title . '.php', '&'));
	exit;
}

if( $action == 'alert' ) //Gestion des alertes
{
	//Changement de statut ou suppression
	if( (!empty($id_get) && ($new_status == '0' || $new_status == '1')) || !empty($get_del) )
	{
		if( !empty($get_del) )
		{
			$hist = false;
			foreach( $_POST as $id_alert => $checked )
			{
				if( $checked = 'on' && is_numeric($id_alert) )
				{	
					$sql->query_inject("DELETE FROM ".PREFIX."forum_alerts WHERE id = '" . $id_alert . "'", __LINE__, __FILE__);
					if( !$hist ) //Insertion de l'action dans l'historique.
					{
						$hist = true;												
						$sql->query_inject("INSERT INTO ".PREFIX."forum_history (action,user_id,timestamp) VALUES('14', '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
					}				
				}
			}
		}
		else
		{
			if( $new_status == '0' ) //On le passe en non lu
			{	
				$sql->query_inject("UPDATE ".PREFIX."forum_alerts SET status = 0, idmodo = 0 WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
				
				//Insertion de l'action dans l'historique.
				$sql->query_inject("INSERT INTO ".PREFIX."forum_history (action,user_id,timestamp) VALUES(13, '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
			}
			elseif( $new_status == '1' ) //On le passe en résolu
			{
				$sql->query_inject("UPDATE ".PREFIX."forum_alerts SET status = 1, idmodo = '" . $session->data['user_id'] . "' WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
			
				//Insertion de l'action dans l'historique.
				$sql->query_inject("INSERT INTO ".PREFIX."forum_history (action,user_id,timestamp) VALUES(12, '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
			}
		}
		
		## Mise à jour du fichier de mise en cache ##
		$cache->generate_module_file('forum');
		
		if( !empty($get_del) ) 
			$get_id = '';
		else 
			$get_id = '&id=' . $id_get;
			
		header('location:' . HOST . DIR . '/forum/moderation_forum' . transid('.php?action=alert' . $get_id, '', '&'));
		exit;
	}
	
	$template->assign_vars(array(
		'L_MODERATION_PANEL' => $LANG['moderation_panel'],
		'L_MODERATION_FORUM' => $LANG['moderation_forum'],
		'L_FORUM' => $LANG['forum'],
		'L_LOGIN' => $LANG['pseudo'],
		'L_ALERT' => $LANG['alert_management'],
		'U_MODERATION_FORUM_ACTION' => '&raquo; <a href="moderation_forum.php'. transid('?action=alert') . '">' . $LANG['alert_management'] . '</a>',
		'U_ACTION_ALERT' => transid('.php?action=alert&amp;del=1')
	));

	if( empty($id_get) ) //On liste les alertes
	{
		$template->assign_block_vars('alert', array(			
		));
		
		$template->assign_vars(array(
			'L_TITLE' => $LANG['alert_title'],
			'L_TOPIC' => $LANG['alert_concerned_topic'],
			'L_LOGIN' => $LANG['alert_login'],
			'L_TIME' => $LANG['date'],
			'L_STATUS' => $LANG['status'],
			'L_DELETE' => $LANG['delete'],
			'L_DELETE_MESSAGE' => $LANG['delete_several_alerts']
		));
		
		//Vérification des autorisations.
		$auth_cats = '';
		foreach($CAT_FORUM as $idcat => $key)
		{
			if( !$groups->check_auth($CAT_FORUM[$idcat]['auth'], EDIT_CAT_FORUM) )
				$auth_cats .= $idcat . ',';
		}
		$auth_cats = !empty($auth_cats) ? " WHERE c.id NOT IN (" . trim($auth_cats, ',') . ")" : '';
			
		$i = 0;		
		$result = $sql->query_while("SELECT ta.id, ta.title, ta.timestamp, ta.status, ta.user_id, ta.idtopic, ta.idmodo, m2.login AS login_modo, m.login, t.title AS topic_title, c.id AS cid
		FROM ".PREFIX."forum_alerts AS ta
		LEFT JOIN ".PREFIX."forum_topics AS t ON t.id = ta.idtopic
		LEFT JOIN ".PREFIX."member AS m ON m.user_id = ta.user_id
		LEFT JOIN ".PREFIX."member AS m2 ON m2.user_id = ta.idmodo
		LEFT JOIN ".PREFIX."forum_cats AS c ON c.id = t.idcat
		" . $auth_cats . "
		ORDER BY ta.status ASC, ta.timestamp DESC", __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) )
		{
			if( $row['status'] == 0 )
				$status = $LANG['alert_not_solved'];
			else
				$status = $LANG['alert_solved'] . '<a href="../member/member' . transid('.php?id=' . $row['idmodo'], '-' . $row['idmodo'] . '.php') . '">' . $row['login_modo'] . '</a>';
			
			$template->assign_block_vars('alert.list', array(
				'TITLE' => '<a href="moderation_forum' . transid('.php?action=alert&amp;id=' . $row['id']) . '">' . $row['title'] . '</a>',
				'TOPIC' => '<a href="topic' . transid('.php?id=' . $row['idtopic'], '-' . $row['idtopic'] . '+' . url_encode_rewrite($row['topic_title']) . '.php') . '">' . $row['topic_title'] . '</a>',
				'STATUS' => $status,
				'LOGIN' => '<a href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . $row['login'] . '</a>',
				'TIME' => date($LANG['date_format'] . ' ' . $LANG['at'] . ' H\hi', $row['timestamp']),
				'BACKGROUND_COLOR' => $row['status'] == 1 ? 'background-color:#82c2a7;' : 'background-color:#e59f09;',
				'ID' => $row['id']
			));
				
			$i++;
		}
		
		if( $i === 0 )
		{
			$template->assign_block_vars('alert.empty', array(
				'NO_ALERT' => $LANG['no_alert'],
			));
		}
	}
	else //On affiche les informations sur une alerte
	{
		//Vérification des autorisations.
		$auth_cats = '';
		foreach($CAT_FORUM as $idcat => $key)
		{
			if( !$groups->check_auth($CAT_FORUM[$idcat]['auth'], EDIT_CAT_FORUM) )
				$auth_cats .= $idcat . ',';
		}
		$auth_cats = !empty($auth_cats) ? " AND c.id NOT IN (" . trim($auth_cats, ',') . ")" : '';
		
		$result = $sql->query_while("
		SELECT ta.id, ta.title, ta.timestamp, ta.status, ta.user_id, ta.idtopic, ta.idmodo, m2.login AS login_modo, m.login, t.title AS topic_title, t.idcat, c.id AS cid, ta.contents
		FROM ".PREFIX."forum_alerts AS ta
		LEFT JOIN ".PREFIX."forum_topics AS t ON t.id = ta.idtopic
		LEFT JOIN ".PREFIX."member AS m ON m.user_id = ta.user_id
		LEFT JOIN ".PREFIX."member AS m2 ON m2.user_id = ta.idmodo
		LEFT JOIN ".PREFIX."forum_cats AS c ON c.id = t.idcat
		WHERE ta.id = '" . $id_get . "'" . $auth_cats, __LINE__, __FILE__);			
		$row = $sql->sql_fetch_assoc($result);
		
		if( !empty($row) )
		{
			if( $row['status'] == 0 )
				$status = $LANG['alert_not_solved'];
			else
				$status = $LANG['alert_solved'] . '<a href="../member/member' . transid('.php?id=' . $row['idmodo'], '-' . $row['idmodo'] . '.php') . '">' . $row['login_modo'] . '</a>';
			
			$template->assign_block_vars('alert_id', array(
				'L_TITLE' => $LANG['alert_title'],
				'L_TOPIC' => $LANG['alert_concerned_topic'],
				'L_CONTENTS' => $LANG['alert_msg'],
				'L_LOGIN' => $LANG['alert_login'],
				'L_TIME' => $LANG['date'],
				'L_STATUS' => $LANG['status'],
				'CHANGE_STATUS' => $LANG['change_status'] . ($row['status'] == '0' ? '<a href="moderation_forum.php' . transid('?action=alert&amp;id=' . $id_get . '&amp;new_status=1') . '">' . $LANG['change_status_to_1'] . '</a>' : '<a href="moderation_forum.php' . transid('?action=alert&amp;id=' . $id_get . '&amp;new_status=0') . '">' . $LANG['change_status_to_0'] . '</a>'),
				'L_STATUS_1' => $LANG['change_status_to_1'],
				'L_CAT' => $LANG['alert_concerned_cat'],
				'ID' => $id_get,
				'TITLE' => $row['title'],
				'TOPIC' => '<a href="topic' . transid('.php?id=' . $row['idtopic'], '-' . $row['idtopic'] . '+' . url_encode_rewrite($row['topic_title']) . '.php') . '">' . $row['topic_title'] . '</a>',
				'CONTENTS' => stripslashes($row['contents']),
				'STATUS' => $status,
				'LOGIN' => '<a href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . $row['login'] . '</a>',
				'TIME' => date($LANG['date_format'] . ' ' . $LANG['at'] . ' H\hi', $row['timestamp']),
				'CAT' => '<a href="forum' . transid('.php?id=' . $row['idcat'], '-' . $row['idcat'] . '+' . url_encode_rewrite($CAT_FORUM[$row['idcat']]['name']) . '.php') . '">' . $CAT_FORUM[$row['idcat']]['name'] . '</a>'
			));
		}
		else //Groupe, modérateur partiel qui n'a pas accès à cette alerte car elle ne concerne pas son forum
		{
			$template->assign_block_vars('alert_id_not_auth', array(
				'NO_ALERT' => $LANG['alert_not_auth']
			));
		}	
	}		
}
elseif( $action == 'punish' ) //Gestion des utilisateurs
{
	$readonly = isset($_POST['new_info']) ? numeric($_POST['new_info']) : 0;
	$readonly = $readonly > 0 ? (time() + $readonly) : 0;
	$readonly_contents = !empty($_POST['action_contents']) ? trim($_POST['action_contents']) : '';
	if( !empty($id_get) && !empty($_POST['valid_user']) ) //On met à  jour le niveau d'avertissement
	{
		$info_mbr = $sql->query_array('member', 'user_id', 'level', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
		
		//Modérateur ne peux avertir l'admin (logique non?).
		if( !empty($info_mbr['user_id']) && ($info_mbr['level'] < 2 || $session->data['level'] === 2) )
		{
			$sql->query_inject("UPDATE ".PREFIX."member SET user_readonly = '" . $readonly . "' WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);
			
			//Envoi d'un MP au membre pour lui signaler, si le membre en question n'est pas lui-même.
			if( $info_mbr['user_id'] != $session->data['user_id'] )
			{
				if( !empty($readonly_contents) && !empty($readonly) )
				{					
					include_once('../includes/pm.class.php');
					$privatemsg = new Privatemsg();
					
					//Envoi du message.
					$privatemsg->send_pm($info_mbr['user_id'], addslashes($LANG['read_only_title']), str_replace('%date', date($LANG['date_format'] . ' ' . $LANG['at'] . ' H\Hi', $readonly), $readonly_contents), '-1', CHECK_PM_BOX, SYSTEM_PM);
				}
			}
			
			//Insertion de l'action dans l'historique.
			$sql->query_inject("INSERT INTO ".PREFIX."forum_history (action,user_id,timestamp) VALUES(15, '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
		}
		
		header('location:' . HOST . DIR . '/forum/moderation_forum' . transid('.php?action=punish', '', '&'));
		exit;
	}
	
	$template->assign_vars(array(
		'L_FORUM' => $LANG['forum'],
		'L_LOGIN' => $LANG['pseudo'],
		'L_MODERATION_PANEL' => $LANG['moderation_panel'],
		'L_MODERATION_FORUM' => $LANG['moderation_forum'],
		'L_INFO_MANAGEMENT' => $LANG['punishment_management'],
		'U_XMLHTTPREQUEST' => 'punish_moderation_panel',
		'U_MODERATION_FORUM_ACTION' => '&raquo; <a href="moderation_forum.php' . transid('?action=punish') . '">' .$LANG['punishment_management'] . '</a>',
		'U_ACTION' => transid('.php?action=punish')
	));
	
	if( empty($id_get) ) //On liste les membres qui ont déjà un avertissement
	{
		if( !empty($_POST['search_member']) )
		{
			$login = !empty($_POST['login_mbr']) ? securit($_POST['login_mbr']) : '';
			$user_id = $sql->query("SELECT user_id FROM ".PREFIX."member WHERE login LIKE '%" . $login . "%'", __LINE__, __FILE__);
			if( !empty($user_id) && !empty($login) )
			{
				header('location:' . HOST . DIR . '/forum/moderation_forum' . transid('.php?action=punish&id=' . $user_id, '', '&'));
				exit;
			}	
			else
			{
				header('location:' . HOST . DIR . '/forum/moderation_forum' . transid('.php?action=punish', '', '&'));
				exit;
			}	
		}	
		
		$template->assign_block_vars('user_list', array(
		));
		
		$template->assign_vars(array(
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_punish_until'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_ACTION_USER' => $LANG['punishment_management'],
			'L_PROFILE' => $LANG['profil'],
			'L_SEARCH_MEMBER' => $LANG['search_member'],
			'L_SEARCH' => $LANG['search'],
			'L_REQUIRE_LOGIN' => $LANG['require_pseudo']
		));	
			
		$i = 0;
		$result = $sql->query_while("SELECT user_id, login, user_readonly
		FROM ".PREFIX."member
		WHERE user_readonly > " . time() . "
		ORDER BY user_readonly", __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) )
		{
			$template->assign_block_vars('user_list.list', array(
				'LOGIN' => '<a href="moderation_forum.php' . transid('?action=punish&amp;id=' . $row['user_id']) . '">' . $row['login'] . '</a>',
				'INFO' => date($LANG['date_format'] . ' ' . $LANG['at'] . ' H\Hi', $row['user_readonly']),
				'U_PROFILE' => '../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
				'U_ACTION_USER' => '<a href="moderation_forum.php' . transid('?action=punish&amp;id=' . $row['user_id']) . '"><img src="../templates/' . $CONFIG['theme'] . '/images/readonly.png" alt="" /></a>',
				'U_PM' => transid('.php?pm='. $row['user_id'], '-' . $row['user_id'] . '.php'),
			));
			
			$i++;
		}
		
		if( $i === 0 )
		{
			$template->assign_block_vars('user_list.empty', array(
				'NO_USER' => $LANG['no_punish'],
			));
		}
	}
	else //On affiche les infos sur l'utilisateur
	{
		$member = $sql->query_array('member', 'login', 'user_readonly', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);

		//Durée de la sanction.
		$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 326592000); 	
		$array_sanction = array($LANG['no'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '1 ' . $LANG['day'], '2 ' . $LANG['days'], '1 ' . $LANG['week'], '2 ' . $LANG['weeks'], '1 ' . $LANG['month'], $LANG['life']); 
		
		$diff = ($member['user_readonly'] - time());	
		$key_sanction = 0;
		if( $diff > 0 )
		{
			//Retourne la sanction la plus proche correspondant au temp de bannissement. 
			for($i = 11; $i >= 0; $i--)
			{					
				$avg = ceil(($array_time[$i] + $array_time[$i-1])/2);
				if( ($diff - $array_time[$i]) > $avg ) 
				{	
					$key_sanction = $i + 1;
					break;
				}
			}
		}	

		//On crée le formulaire select
		$select = '';
		foreach( $array_time as $key => $time)
		{
			$selected = ( $key_sanction == $key ) ? 'selected="selected"' : '' ;
			$select .= '<option value="' . $time . '" ' . $selected . '>' . strtolower($array_sanction[$key]) . '</option>';
		}	
		
		$template->assign_vars(array(
			'ALTERNATIVE_PM' => ($key_sanction > 0) ? str_replace('%date%', $array_sanction[$key_sanction], $LANG['user_readonly_changed']) : str_replace('%date%', '1 ' . $LANG['minute'], $LANG['user_readonly_changed']),
			'L_ALTERNATIVE_PM' => $LANG['user_alternative_pm'],
			'L_INFO_EXPLAIN' => $LANG['user_readonly_explain'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_LOGIN' => $LANG['pseudo'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_CHANGE_INFO' => $LANG['submit']
		));	
		
		$template->assign_block_vars('user_info', array(			
			'LOGIN' => '<a href="../member/member' . transid('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $member['login'] . '</a>',
			'INFO' => $array_sanction[$key_sanction],
			'SELECT' => $select,
			'REPLACE_VALUE' => 'replace_value = parseInt(replace_value);'. "\n" . 
			'array_time = new Array(' . (implode(', ', $array_time)) . ');' . "\n" .  
			'array_sanction = new Array(\'' . implode('\', \'', array_map('addslashes', $array_sanction)) . '\');'. "\n" . 
			'var i; 		
			for(i = 0; i <= 12; i++)
			{ 
				if( array_time[i] == replace_value )
				{
					replace_value = array_sanction[i];	
					break;
				}
			}' . "\n" . 
			'if( replace_value != \'' . addslashes($LANG['no']) . '\' )' . "\n" .
			'{' . "\n" .
				'contents = contents.replace(regex, replace_value);' . "\n" .
				'document.getElementById(\'action_contents\').disabled = \'\'' . "\n" .
			'} else' . "\n" .
			'	document.getElementById(\'action_contents\').disabled = \'disabled\';' . "\n" .
			'document.getElementById(\'action_info\').innerHTML = replace_value;',
			'REGEX' => '/[0-9]+ [a-zA-Z]+/',
			'U_PM' => transid('.php?pm='. $id_get, '-' . $id_get . '.php'),
			'U_ACTION_INFO' => transid('.php?action=punish&amp;id=' . $id_get)
		));		

		$_field = 'action_contents';
		include_once('../includes/bbcode.php');
		$template->assign_var_from_handle('BBCODE', 'bbcode');		
	}	
}
elseif( $action == 'warning' ) //Gestion des utilisateurs
{
	$new_warning_level = isset($_POST['new_info']) ? numeric($_POST['new_info']) : 0;
	$warning_contents = !empty($_POST['action_contents']) ? trim($_POST['action_contents']) : '';
	if( $new_warning_level >= 0 && $new_warning_level <= 100 && isset($_POST['new_info']) && !empty($id_get) && !empty($_POST['valid_user']) ) //On met à  jour le niveau d'avertissement
	{
		$info_mbr = $sql->query_array('member', 'user_id', 'level', 'user_mail', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
		
		//Modérateur ne peux avertir l'admin (logique non?).
		if( !empty($info_mbr['user_id']) && ($info_mbr['level'] < 2 || $session->data['level'] === 2) )
		{
			if( $new_warning_level < 100 ) //Ne peux pas mettre des avertissements supérieurs à 100.
			{
				$sql->query_inject("UPDATE ".PREFIX."member SET user_warning = '" . $new_warning_level . "' WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);
				
				//Envoi d'un MP au membre pour lui signaler, si le membre en question n'est pas lui-même.
				if( $info_mbr['user_id'] != $session->data['user_id'] )
				{					
					if( !empty($warning_contents) )
					{					
						include_once('../includes/pm.class.php');
						$privatemsg = new Privatemsg();
						
						//Envoi du message.
						$privatemsg->send_pm($info_mbr['user_id'], addslashes($LANG['warning_title']), $warning_contents, '-1', CHECK_PM_BOX, SYSTEM_PM);
					}
				}
				
				//Insertion de l'action dans l'historique.
				$sql->query_inject("INSERT INTO ".PREFIX."forum_history (action,user_id,timestamp) VALUES(8, '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
			}
			elseif( $new_warning_level == 100 ) //Ban => on supprime sa session et on le banni (pas besoin d'envoyer de pm :p).
			{
				$sql->query_inject("UPDATE ".PREFIX."member SET user_warning = 100 WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);
				$sql->query_inject("DELETE FROM ".PREFIX."sessions WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);
				
				//Insertion de l'action dans l'historique.
				$sql->query_inject("INSERT INTO ".PREFIX."forum_history (action,user_id,timestamp) VALUES( 9, '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
				
				//Envoi du mail
				include_once('../includes/mail.class.php');
				$mail = new Mail();
				$mail->send_mail($info_mbr['user_mail'], addslashes($LANG['ban_title_mail']), sprintf(addslashes($LANG['ban_mail']), HOST, addslashes($CONFIG['sign'])), $CONFIG['mail']);
			}	
		}
		
		header('Location:' . HOST . DIR . '/forum/moderation_forum' . transid('.php?action=warning', '', '&'));
		exit;
	}
	
	$template->assign_vars(array(
		'L_FORUM' => $LANG['forum'],
		'L_LOGIN' => $LANG['pseudo'],
		'L_MODERATION_PANEL' => $LANG['moderation_panel'],
		'L_MODERATION_FORUM' => $LANG['moderation_forum'],
		'L_INFO_MANAGEMENT' => $LANG['warning_management'],
		'U_XMLHTTPREQUEST' => 'warning_moderation_panel',
		'U_MODERATION_FORUM_ACTION' => '&raquo; <a href="moderation_forum.php' . transid('?action=warning') . '">' . $LANG['warning_management'] . '</a>',
		'U_ACTION' => transid('.php?action=warning')
	));
	
	if( empty($id_get) ) //On liste les membres qui ont déjà un avertissement
	{
		if( !empty($_POST['search_member']) )
		{
			$login = !empty($_POST['login_mbr']) ? securit($_POST['login_mbr']) : '';
			$user_id = $sql->query("SELECT user_id FROM ".PREFIX."member WHERE login LIKE '%" . $login . "%'", __LINE__, __FILE__);
			if( !empty($user_id) && !empty($login) )
			{
				header('location:' . HOST . DIR . '/forum/moderation_forum' . transid('.php?action=warning&id=' . $user_id, '', '&'));
				exit;
			}	
			else
			{
				header('location:' . HOST . DIR . '/forum/moderation_forum' . transid('.php?action=warning', '', '&'));
				exit;
			}	
		}		
		
		$template->assign_vars(array(
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_warning_level'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_ACTION_USER' => $LANG['change_user_warning'],
			'L_SEARCH_MEMBER' => $LANG['search_member'],
			'L_SEARCH' => $LANG['search'],
			'L_REQUIRE_LOGIN' => $LANG['require_pseudo']
		));
		
		$template->assign_block_vars('user_list', array(
		));
		
		$i = 0;
		$result = $sql->query_while("SELECT user_id, login, user_warning
		FROM ".PREFIX."member
		WHERE user_warning > 0
		ORDER BY user_warning", __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) )
		{
			$template->assign_block_vars('user_list.list', array(
				'LOGIN' => $row['login'],
				'INFO' => $row['user_warning'] . '%',
				'U_ACTION_USER' => '<a href="moderation_forum.php' . transid('?action=warning&amp;id=' . $row['user_id']) . '"><img src="../templates/' . $CONFIG['theme'] . '/images/admin/important.png" alt="" /></a>',
				'U_PROFILE' => '../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
				'U_PM' => transid('.php?pm='. $row['user_id'], '-' . $row['user_id'] . '.php'),
			));
			
			$i++;
		}
		
		if( $i === 0 )
		{
			$template->assign_block_vars('user_list.empty', array(
				'NO_USER' => $LANG['no_user_warning'],
			));
		}
	}
	else //On affiche les infos sur l'utilisateur
	{
		$member = $sql->query_array('member', 'login', 'user_warning', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);

		
		$template->assign_vars(array(
			'ALTERNATIVE_PM' => str_replace('%level%', $member['user_warning'], $LANG['user_warning_level_changed']),
			'L_ALTERNATIVE_PM' => $LANG['user_alternative_pm'],
			'L_INFO_EXPLAIN' => $LANG['user_warning_explain'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_warning_level'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_CHANGE_INFO' => $LANG['change_user_warning']
		));			
			
		//On crée le formulaire select
		$select = '';
		$j = 0;
		for($j = 0; $j <= 10; $j++)
		{
			if( (10 * $j) == $member['user_warning'] ) 
				$select .= '<option value="' . 10 * $j . '" selected="selected">' . 10 * $j . '%</option>';
			else
				$select .= '<option value="' . 10 * $j . '">' . 10 * $j . '%</option>';
		}
		
		$template->assign_block_vars('user_info', array(			
			'LOGIN' => '<a href="../member/member' . transid('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $member['login'] . '</a>',
			'INFO' => $LANG['user_warning_level'] . ': ' . $member['user_warning'] . '%',
			'SELECT' => $select,
			'REPLACE_VALUE' => 'contents = contents.replace(regex, \' \' + replace_value + \'%\');' . "\n" . 'document.getElementById(\'action_info\').innerHTML = \'' . addslashes($LANG['user_warning_level']) . ': \' + replace_value + \'%\';',
			'REGEX' => '/ [0-9]+%/',
			'U_ACTION_INFO' => transid('.php?action=warning&amp;id=' . $id_get),
			'U_PM' => transid('.php?pm='. $id_get, '-' . $id_get . '.php'),
		));	

		$_field = 'action_contents';
		include_once('../includes/bbcode.php');
		$template->assign_var_from_handle('BBCODE', 'bbcode');
	}	
}
elseif( !empty($_GET['del_h']) && $session->data['level'] === 2 ) //Suppression de l'historique.
{
	$sql->query_inject("DELETE FROM ".PREFIX."forum_history");
	
	header('location:' . HOST . DIR . '/forum/moderation_forum' . transid('.php', '', '&'));
	exit;
}
else //Panneau de modération
{
	$get_more = !empty($_GET['more']) ? numeric($_GET['more']) : 0;
	
	$template->assign_block_vars('main', array(
		'U_ACTION_HISTORY' => transid('.php?del_h=1'),
		'U_MORE_ACTION' => !empty($get_more) ? transid('.php?more=' . ($get_more + 100)) : transid('.php?more=100')
	));
	
	//Bouton de suppression de l'historique, visible uniquement pour l'admin.
	if( $session->data['level'] === 2 )
	{
		$template->assign_block_vars('main.admin', array(
		));	
	}
	
	$template->assign_vars(array(
		'SID' => SID,
		'L_DEL_HISTORY' => $LANG['alert_history'],
		'L_MODERATION_PANEL' => $LANG['moderation_panel'],
		'L_MODERATION_FORUM' => $LANG['moderation_forum'],
		'L_FORUM' => $LANG['forum'],
		'L_USERS_PUNISHMENT' => $LANG['punishment_management'],
		'L_ALERT_MANAGEMENT' => $LANG['alert_management'],
		'L_USERS_MANAGEMENT' => $LANG['warning_management'],
		'L_HISTORY' => $LANG['history'],
		'L_LOGIN' => $LANG['pseudo'],
		'L_ACTION' => $LANG['action'],
		'L_DATE' => $LANG['date'],
		'L_DELETE' => $LANG['delete'],
		'L_MORE_ACTION' => $LANG['more_action']
	));

	//Tableau des actions.
	$action = array(
		0 => $LANG['delete_msg'], //Suppression d'un message.
		1 => $LANG['delete_topic'], //Suppression d'un sujet.
		2 => $LANG['lock_topic'], //Verrouillage d'un sujet.
		3 => $LANG['unlock_topic'], //Déverrouillage d'un sujet.
		4 => $LANG['move_topic'], //Déplacement d'un sujet.
		5 => $LANG['cut_topic'], //Scindement d'un sujet.
		6 => $LANG['warning_on_user'], //+10% à un membre.
		7 => $LANG['warning_off_user'], //-10% à un membre.
		8 => $LANG['set_warning_user'], //Modification pourcentage avertissement.		
		9 => $LANG['ban_user'], //Modification pourcentage avertissement.		
		10 => $LANG['edit_msg'], //Edition message d'un membre.
		11 => $LANG['edit_topic'], //Edition sujet d'un membre.
		12 => $LANG['solve_alert'], //Résolution d'une alerte.
		13 => $LANG['wait_alert'], //Mise en attente d'une alerte.
		14 => $LANG['del_alert'], //Suppression d'une alerte.
		15 => $LANG['readonly_user'] //Modification lecture seule d'un membre.
	);
	
	$end = !empty($get_more) ? $get_more : 15; //Limit.
	$i = 0;
	
	$result = $sql->query_while("SELECT h.action, h.user_id, h.timestamp, m.login 
	FROM ".PREFIX."forum_history AS h 
	LEFT JOIN ".PREFIX."member AS m ON m.user_id = h.user_id 
	ORDER BY h.timestamp DESC
	" . $sql->sql_limit(0, $end), __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		$template->assign_block_vars('main.list', array(
			'LOGIN' => !empty($row['login']) ? $row['login'] : $LANG['guest'],
			'ACTION' => $action[$row['action']],
			'DATE' => date($LANG['date_format'] . ' ' . $LANG['at'] . ' H\hi', $row['timestamp']),
			'U_MEMBER_ID' => transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php')
		));
		
		$i++;
	}
	$sql->close($result);
	
	if( $i == 0 )
	{
		$template->assign_block_vars('main.no_action', array(
			'L_NO_ACTION' => $LANG['no_action']
		));
	}
}

//Gestion des utilisateurs connectés.
$total_admin = 0;
$total_modo = 0;
$total_member = 0;
$user_pseudo = '';

$result = $sql->query_while("SELECT s.user_id, s.level, m.login 
FROM ".PREFIX."sessions AS s 
LEFT JOIN ".PREFIX."member AS m ON m.user_id = s.user_id 
WHERE s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' AND s.session_script = '" . DIR . "/forum/moderation_forum.php'
ORDER BY s.session_time DESC", __LINE__, __FILE__);
while( $row = $sql->sql_fetch_assoc($result) )
{
	switch ($row['level']) //Coloration du membre suivant son level d'autorisation. 
	{ 		
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
	
	$coma = !empty($user_pseudo) && $status != 'visiteur' ? ', ' : '';

	$user_pseudo .= ( !empty($row['login']) && $status != 'visiteur' ) ?  $coma . '<a href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" class="' . $status . '">' . $row['login'] . '</a>' : '';
}
$sql->close($result);

$template->assign_block_vars('online', array(
	'ONLINE' =>  $user_pseudo
));	

$l_admin = ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'];
$l_modo = ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'];
$l_member = ($total_member > 1) ? $LANG['member_s'] : $LANG['member'];

$total_online = $total_admin + $total_modo + $total_member;
$l_online = ($total_online > 1) ? $LANG['user_s'] : $LANG['user'];

$template->assign_vars(array(
	'TOTAL_ONLINE' => $total_online,
	'ADMIN' => $total_admin,
	'MODO' => $total_modo,
	'MEMBER' => $total_member,
	'SELECT_CAT' => forum_list_cat($session->data), //Retourne la liste des catégories, avec les vérifications d'accès qui s'imposent.
	'L_USER' => $l_online,
	'L_ADMIN' => $l_admin,
	'L_MODO' => $l_modo ,
	'L_MEMBER' => $l_member,
	'L_ONLINE' => strtolower($LANG['online']),
	'L_FORUM_INDEX' => $LANG['forum_index'],		
	'U_SEARCH' => '<a class="small_link" href="search.php' . SID . '" title="' . $LANG['search'] . '">' . $LANG['search'] . '</a> &bull;',
	'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a> &bull;',
	'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' . SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . '</a>',
	'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . SID . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a> &bull;',
	'U_ONCHANGE' => "'forum" . transid(".php?id=' + this.options[this.selectedIndex].value + '", "-' + this.options[this.selectedIndex].value + '.php") . "'"
));

if( $total_online == 0  )
{
	$template->assign_block_vars('online', array(
		'ONLINE' =>  '<em>' . $LANG['no_member_online'] . '</em>'
	));
}

$template->pparse('forum_moderation_panel');

include('../includes/footer.php');

?>