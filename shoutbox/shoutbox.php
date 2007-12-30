<?php
/*##################################################
 *                               shoutbox.php
 *                            -------------------
 *   begin                : July 29, 2005
 *   copyright          : (C) 2005 Viarre R�gis
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

require_once('../includes/begin.php'); 
require_once('../shoutbox/shoutbox_begin.php'); 
require_once('../includes/header.php');
	
$shout_id = !empty($_GET['id']) ? numeric($_GET['id']) : '';
if( !empty($_POST['shoutbox']) && empty($shout_id) ) //Insertion
{		
	//Membre en lecture seule?
	if( $session->data['user_readonly'] > time() ) 
	{
		$errorh->error_handler('e_readonly', E_USER_REDIRECT); 
		exit;
	}
	
	$shout_pseudo = !empty($_POST['shout_pseudo']) ? clean_user($_POST['shout_pseudo']) : $LANG['guest']; //Pseudo post�.
	$shout_contents = !empty($_POST['shout_contents']) ? trim($_POST['shout_contents']) : '';	
	if( !empty($shout_pseudo) && !empty($shout_contents) )
	{		
		//Acc�s pour poster.		
		if( $session->check_auth($session->data, $CONFIG_SHOUTBOX['shoutbox_auth']) )
		{
			//Mod anti-flood, autoris� aux membres qui b�nificie de l'autorisation de flooder.
			$check_time = ($session->data['user_id'] !== -1 && $CONFIG['anti_flood'] == 1) ? $sql->query("SELECT MAX(timestamp) as timestamp FROM ".PREFIX."shoutbox WHERE user_id = '" . $session->data['user_id'] . "'", __LINE__, __FILE__) : '';
			if( !empty($check_time) && !$groups->check_auth($groups->user_groups_auth, AUTH_FLOOD) )
			{
				if( $check_time >= (time() - $CONFIG['delay_flood']) )
				{
					header('location:' . HOST . DIR . '/shoutbox/shoutbox.php' . transid('?error=flood', '', '&') . '#errorh');
					exit;
				}
			}
			
			//V�rifie que le message ne contient pas du flood de lien.
			$shout_contents = parse($shout_contents, $CONFIG_SHOUTBOX['shoutbox_forbidden_tags']);		
			if( !check_nbr_links($shout_pseudo, 0) ) //Nombre de liens max dans le pseudo.
			{	
				header('location:' . HOST . SCRIPT . transid('?error=l_pseudo', '', '&') . '#errorh');
				exit;
			}			
			if( !check_nbr_links($shout_contents, $CONFIG_SHOUTBOX['shoutbox_max_link']) ) //Nombre de liens max dans le message.
			{	
				header('location:' . HOST . SCRIPT . transid('?error=l_flood', '', '&') . '#errorh');
				exit;
			}
			
			$sql->query_inject("INSERT INTO ".PREFIX."shoutbox (login,user_id,contents,timestamp) VALUES('" . $shout_pseudo . "', '" . $session->data['user_id'] . "','" . $shout_contents . "', '" . time() . "')", __LINE__, __FILE__);
				
			header('location:' . HOST . transid(SCRIPT . '?' . QUERY_STRING, '', '&'));
			exit;
		}
		else //utilisateur non autoris�!
		{
			header('location:' . transid(HOST . SCRIPT . '?error=auth', '', '&') . '#errorh');
			exit;
		}
	}
	else
	{
		//Champs incomplet!
		header('location:' . transid(HOST . SCRIPT . '?error=incomplete', '', '&') . '#errorh');
		exit;
	}
}
elseif( !empty($shout_id) ) //Edition + suppression!
{
	//Membre en lecture seule?
	if( $session->data['user_readonly'] > time() ) 
	{
		$errorh->error_handler('e_readonly', E_USER_REDIRECT); 
		exit;
	}
	
	$del = !empty($_GET['del']) ? true : false;
	$edit = !empty($_GET['edit']) ? true : false;
	$update = !empty($_GET['update']) ? true : false;
	
	$row = $sql->query_array('shoutbox', '*', "WHERE id = '" . $shout_id . "'", __LINE__, __LINE__);
	$row['user_id'] = (int)$row['user_id'];
	
	if( $session->check_auth($session->data, 1) || ($row['user_id'] === $session->data['user_id'] && $session->data['user_id'] !== -1) )
	{
		if( $del )
		{
			$sql->query_inject("DELETE FROM ".PREFIX."shoutbox WHERE id = '" . $shout_id . "'", __LINE__, __FILE__);
			
			header('location:' . HOST . SCRIPT . SID2);
			exit;
		}
		elseif( $edit )
		{
			$template->set_filenames(array(
				'shoutbox' => '../templates/' . $CONFIG['theme'] . '/shoutbox/shoutbox.tpl'
			));
			
			if( $row['user_id'] !== -1 )
				$template->assign_block_vars('hidden', array(
					'PSEUDO' => $row['login']
				));
			else
				$template->assign_block_vars('visible', array(
					'PSEUDO' => $row['login']
				));
			
			$forbidden_tags = implode(', ', $CONFIG_SHOUTBOX['shoutbox_forbidden_tags']);
			$template->assign_vars(array(
				'UPDATE' => transid('?update=1&amp;id=' . $row['id']),
				'SID' => '',
				'CONTENTS' => unparse($row['contents']),
				'DATE' => gmdate_format('date_format_short', $row['timestamp']),
				'THEME' => $CONFIG['theme'],
				'FORBIDDEN_TAGS' => !empty($forbidden_tags) ? $forbidden_tags : '',
				'DISPLAY_FORBIDDEN_TAGS' => !empty($forbidden_tags) ? '[' . str_replace(', ', '], [', $forbidden_tags) . ']' : '',
				'L_FORBIDDEN_TAGS' => !empty($forbidden_tags) ? $LANG['forbidden_tags'] : '',
				'L_ALERT_TEXT' => $LANG['require_text'],
				'L_UPDATE_MSG' => $LANG['update_msg'],
				'L_REQUIRE' => $LANG['require'],
				'L_MESSAGE' => $LANG['message'],
				'L_PSEUDO' => $LANG['pseudo'],
				'L_SUBMIT' => $LANG['update'],
				'L_PREVIEW' => $LANG['preview'],
				'L_RESET' => $LANG['reset']
			));
			
			$_field = 'shout_contents';
			include_once('../includes/bbcode.php');
			
			$template->pparse('shoutbox'); 
		}
		elseif( $update )
		{
			$shout_contents = !empty($_POST['shout_contents']) ? trim($_POST['shout_contents']) : '';			
			$shout_pseudo = !empty($_POST['shout_pseudo']) ? securit($_POST['shout_pseudo']) : '';			
			if( !empty($shout_contents) && !empty($shout_pseudo) )
			{
				//V�rifie que le message ne contient pas du flood de lien.
				$shout_contents = parse($shout_contents, $CONFIG_SHOUTBOX['shoutbox_forbidden_tags']);		
				if( !check_nbr_links($shout_contents, $CONFIG_SHOUTBOX['shoutbox_max_link']) ) //Nombre de liens max dans le message.
				{	
					header('location:' . HOST . SCRIPT . transid('?error=l_flood', '', '&') . '#errorh');
					exit;
				}
			
				$sql->query_inject("UPDATE ".PREFIX."shoutbox SET contents = '" . $shout_contents . "', login = '" . $shout_pseudo . "' WHERE id = '" . $shout_id . "'", __LINE__, __FILE__);
			
				header('location:' . HOST . SCRIPT. SID2);
				exit;
			}
			else
			{
				//Champs incomplet!
				header('location:' . transid(HOST . SCRIPT . '?error=incomplete', '', '&') . '#errorh');
				exit;
			}
		}
		else
		{
			header('location:' . HOST . SCRIPT . SID2);
			exit;
		}
	}
	else
	{
		header('location:' . HOST . SCRIPT . SID2);
		exit;
	}
}
else //Affichage.
{
	$template->set_filenames(array(
		'shoutbox' => '../templates/' . $CONFIG['theme'] . '/shoutbox/shoutbox.tpl'
	));
	
	//Pseudo du membre connect�.
	if( $session->data['user_id'] !== -1 )
	$template->assign_vars(array(
		'SHOUTBOX_PSEUDO' => $session->data['login'],
		'C_HIDDEN_SHOUT' => true
	));
else
	$template->assign_vars(array(
		'SHOUTBOX_PSEUDO' => $LANG['guest'],
		'C_VISIBLE_SHOUT' => true
	));
		  	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	switch($get_error)
	{
		case 'auth':
			$errstr = $LANG['e_unauthorized'];
			break;
		case 'flood':
			$errstr = $LANG['e_flood'];
			break;
		case 'l_flood':
			$errstr = sprintf($LANG['e_l_flood'], $CONFIG_SHOUTBOX['shoutbox_max_link']);
			break;
		case 'l_pseudo':
			$errstr = $LANG['e_link_pseudo'];
			break;
		case 'incomplete':
			$errstr = $LANG['e_incomplete'];
			break;
		default:
		$errstr = '';
	}	
	if( !empty($errstr) )
		$errorh->error_handler($errstr, E_USER_NOTICE);
	
	$forbidden_tags = implode(', ', $CONFIG_SHOUTBOX['shoutbox_forbidden_tags']);
	$template->assign_vars(array(
		'SID' => SID,
		'FORBIDDEN_TAGS' => !empty($forbidden_tags) ? $forbidden_tags : '',
		'DISPLAY_FORBIDDEN_TAGS' => !empty($forbidden_tags) ? '[' . str_replace(', ', '], [', $forbidden_tags) . ']' : '',
		'L_FORBIDDEN_TAGS' => !empty($forbidden_tags) ? $LANG['forbidden_tags'] : '',
		'L_ON' => $LANG['on'],
		'L_ALERT_TEXT' => $LANG['require_text'],
		'L_DELETE_MSG' => $LANG['alert_delete_msg'],
		'L_ADD_MSG' => $LANG['add_msg'],
		'L_REQUIRE' => $LANG['require'],
		'L_MESSAGE' => $LANG['message'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));
	
	$nbr_shout = $sql->count_table('shoutbox', __LINE__, __FILE__);
	
	//On cr�e une pagination si le nombre de messages est trop important.
	include_once('../includes/pagination.class.php'); 
	$pagination = new Pagination();
		
	$template->assign_vars(array(
		'PAGINATION' => $pagination->show_pagin('shoutbox' . transid('.php?p=%d'), $nbr_shout, 'p', 10, 3)
	));
	
	//Cr�ation du tableau des rangs.
	$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);

	//Gestion des rangs.	
	$cache->load_file('ranks');
	$result = $sql->query_while("SELECT s.id, s.login, s.user_id, s.timestamp, m.login as mlogin, m.level, m.user_mail, m.user_show_mail, m.timestamp AS registered, m.user_avatar, m.user_msg, m.user_local, m.user_web, m.user_sex, m.user_msn, m.user_yahoo, m.user_sign, m.user_warning, m.user_ban, m.user_groups, se.user_id AS connect, s.contents
	FROM ".PREFIX."shoutbox s
	LEFT JOIN ".PREFIX."member m ON m.user_id = s.user_id
	LEFT JOIN ".PREFIX."sessions se ON se.user_id = s.user_id AND se.session_time > '" . (time() - $CONFIG['site_session_invit']) . "'
	GROUP BY s.id
	ORDER BY s.timestamp DESC 
	" . $sql->sql_limit($pagination->first_msg(10, 'p'), 10), __LINE__, __FILE__);	
	while ($row = $sql->sql_fetch_assoc($result))
	{
		$row['user_id'] = (int)$row['user_id'];
			$edit = '';
			$del = '';
			
			$is_guest = ($row['user_id'] === -1);
			$is_modo = $session->check_auth($session->data, 1);
			$warning = '';
			$readonly = '';
			if( $is_modo && !$is_guest ) //Mod�ration.
			{
				$warning = '&nbsp;<a href="../member/moderation_panel' . transid('.php?action=warning&amp;id=' . $row['user_id']) . '" title="' . $LANG['warning_management'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/admin/important.png" alt="' . $LANG['warning_management'] .  '" class="valign_middle" /></a>'; 
				$readonly = '<a href="../member/moderation_panel' . transid('.php?action=punish&amp;id=' . $row['user_id']) . '" title="' . $LANG['punishment_management'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/readonly.png" alt="' . $LANG['punishment_management'] .  '" class="valign_middle" /></a>'; 
			}
			
			//Edition/suppression.
			if( $is_modo || ($row['user_id'] === $session->data['user_id'] && $session->data['user_id'] !== -1) )
			{
				$edit = '&nbsp;&nbsp;<a href="../shoutbox/shoutbox' . transid('.php?edit=1&id=' . $row['id']) . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="' . $LANG['edit'] . '" title="' . $LANG['edit'] . '" class="valign_middle" /></a>';
				$del = '&nbsp;&nbsp;<a href="../shoutbox/shoutbox' . transid('.php?del=1&id=' . $row['id']) . '" onClick="javascript:return Confirm_shout();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="' . $LANG['delete'] . '" title="' . $LANG['delete'] . '" class="valign_middle" /></a>';
			}
			
			//Pseudo.
			if( !$is_guest ) 
				$shout_pseudo = '<a class="msg_link_pseudo" href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" title="' . $row['mlogin'] . '"><span style="font-weight: bold;">' . wordwrap_html($row['mlogin'], 13) . '</span></a>';
			else
				$shout_pseudo = '<span style="font-style:italic;">' . (!empty($row['login']) ? wordwrap_html($row['login'], 13) : $LANG['guest']) . '</span>';
			
			//Rang de l'utilisateur.
			$user_rank = ($row['level'] === '0') ? $LANG['member'] : $LANG['guest'];
			$user_group = $user_rank;
			if( $row['level'] === '2' ) //Rang sp�cial (admins).  
			{
				$user_rank = $_array_rank[-2][0];
				$user_group = $user_rank;
				$user_rank_icon = $_array_rank[-2][1];
			}
			elseif( $row['level'] === '1' ) //Rang sp�cial (modos).  
			{
				$user_rank = $_array_rank[-1][0];
				$user_group = $user_rank;
				$user_rank_icon = $_array_rank[-1][1];
			}
			else
			{
				foreach($_array_rank as $msg => $ranks_info)
				{
					if( $msg >= 0 && $msg <= $row['user_msg'] )
					{ 
						$user_rank = $ranks_info[0];
						$user_rank_icon = $ranks_info[1];
						break;
					}
				}
			}
				
			//Image associ�e au rang.
			$user_assoc_img = isset($user_rank_icon) ? '<img src="../templates/' . $CONFIG['theme'] . '/images/ranks/' . $user_rank_icon . '" alt="" />' : '';
						
			//Affichage des groupes du membre.		
			if( !empty($row['user_groups']) && $_array_groups_auth ) 
			{	
				$user_groups = '';
				$array_user_groups = explode('|', $row['user_groups']);
				foreach($_array_groups_auth as $idgroup => $array_group_info)
				{
					if( is_numeric(array_search($idgroup, $array_user_groups)) )
						$user_groups .= !empty($array_group_info[1]) ? '<img src="../images/group/' . $array_group_info[1] . '" alt="' . $array_group_info[0] . '" title="' . $array_group_info[0] . '"/><br />' : $LANG['group'] . ': ' . $array_group_info[0];
				}
			}
			else
				$user_groups = $LANG['group'] . ': ' . $user_group;
			
			//Membre en ligne?
			$user_online = !empty($row['connect']) ? 'online' : 'offline';
			
			//Avatar			
			if( empty($row['user_avatar']) ) 
				$user_avatar = ($CONFIG_MEMBER['activ_avatar'] == '1' && !empty($CONFIG_MEMBER['avatar_url'])) ? '<img src="../templates/' . $CONFIG['theme'] . '/images/' .  $CONFIG_MEMBER['avatar_url'] . '" alt="" />' : '';
			else
				$user_avatar = '<img src="' . $row['user_avatar'] . '" alt=""	/>';
			
			//Affichage du sexe et du statut (connect�/d�connect�).	
			$user_sex = '';
			if( $row['user_sex'] == 1 )	
				$user_sex = $LANG['sex'] . ': <img src="../templates/' . $CONFIG['theme'] . '/images/man.png" alt="" /><br />';	
			elseif( $row['user_sex'] == 2 ) 
				$user_sex = $LANG['sex'] . ': <img src="../templates/' . $CONFIG['theme'] . '/images/woman.png" alt="" /><br />';
					
			//Nombre de message.
			$user_msg = ($row['user_msg'] > 1) ? $LANG['message_s'] . ': ' . $row['user_msg'] : $LANG['message'] . ': ' . $row['user_msg'];
			
			//Localisation.
			if( !empty($row['user_local']) ) 
			{
				$user_local = $LANG['place'] . ': ' . $row['user_local'];
				$user_local = $user_local > 15 ? htmlentities(substr(html_entity_decode($user_local), 0, 15)) . '...<br />' : $user_local . '<br />';			
			}
			else $user_local = '';
			
			$template->assign_block_vars('shoutbox',array(
				'ID' => $row['id'],
				'CONTENTS' => ucfirst(second_parse($row['contents'])),
				'DATE' => $LANG['on'] . ': ' . gmdate_format('date_format', $row['timestamp']),
				'USER_ONLINE' => '<img src="../templates/' . $CONFIG['theme'] . '/images/' . $user_online . '.png" alt="" class="valign_middle" />',
				'USER_PSEUDO' => $shout_pseudo,			
				'USER_RANK' => (($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? $user_rank : $LANG['banned']),
				'USER_IMG_ASSOC' => $user_assoc_img,
				'USER_AVATAR' => $user_avatar,			
				'USER_GROUP' => $user_groups,
				'USER_DATE' => !$is_guest ? $LANG['registered_on'] . ': ' . gmdate_format('date_format_short', $row['registered']) : '',
				'USER_SEX' => $user_sex,
				'USER_MSG' => !$is_guest ? $user_msg : '',
				'USER_LOCAL' => $user_local,
				'USER_MAIL' => (!empty($row['user_mail']) && ($row['user_show_mail'] == '1')) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/email.png" alt="' . $row['user_mail']  . '" title="' . $row['user_mail']  . '" /></a>' : '',			
				'USER_MSN' => !empty($row['user_msn']) ? '<a href="mailto:' . $row['user_msn'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/msn.png" alt="' . $row['user_msn']  . '" title="' . $row['user_msn']  . '" /></a>' : '',
				'USER_YAHOO' => !empty($row['user_yahoo']) ? '<a href="mailto:' . $row['user_yahoo'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/yahoo.png" alt="' . $row['user_yahoo']  . '" title="' . $row['user_yahoo']  . '" /></a>' : '',
				'USER_SIGN' => !empty($row['user_sign']) ? '____________________<br />' . $row['user_sign'] : '',
				'USER_WEB' => !empty($row['user_web']) ? '<a href="' . $row['user_web'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/user_web.png" alt="' . $row['user_web']  . '" title="' . $row['user_yahoo']  . '" /></a>' : '',
				'WARNING' => (!empty($row['user_warning']) ? $row['user_warning'] : '0') . '%' . $warning,
				'PUNISHMENT' => $readonly,			
				'DEL' => $del,
				'EDIT' => $edit,
				'U_ANCHOR' => 'shoutbox.php' . SID . '#m' . $row['id']
			));
	}
	$sql->close($result);
	
	$_field = 'shout_contents';
	include_once('../includes/bbcode.php');
	
	$template->pparse('shoutbox'); 
}

require_once('../includes/footer.php'); 

?>