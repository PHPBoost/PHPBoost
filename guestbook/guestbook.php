<?php
/*##################################################
 *                              guestbook.php
 *                            -------------------
 *   begin                : July 11, 2005
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

require_once('../kernel/begin.php'); 
require_once('../guestbook/guestbook_begin.php'); 
require_once('../kernel/header.php'); 

$id_get = retrieve(GET, 'id', 0);
$guestbook = retrieve(POST, 'guestbook', false);
//Chargement du cache
$Cache->Load_file('guestbook');
		
if( $guestbook && empty($id_get) ) //Enregistrement
{
	$guestbook_contents = retrieve(POST, 'guestbook_contents', '', TSTRING_UNSECURE);
	$guestbook_pseudo = retrieve(POST, 'guestbook_pseudo', $LANG['guest']);

	//Membre en lecture seule?
	if( $Member->Get_attribute('user_readonly') > time() ) 
		$Errorh->Error_handler('e_readonly', E_USER_REDIRECT); 
	
	if( !empty($guestbook_contents) && !empty($guestbook_pseudo) )
	{	
		//Acc�s pour poster.			
		if( $Member->Check_level($CONFIG_GUESTBOOK['guestbook_auth']) )
		{
			//Mod anti-flood
			$check_time = ($Member->Get_attribute('user_id') !== -1 && $CONFIG['anti_flood'] == 1) ? $Sql->Query("SELECT MAX(timestamp) as timestamp FROM ".PREFIX."guestbook WHERE user_id = '" . $Member->Get_attribute('user_id') . "'", __LINE__, __FILE__) : '';
			if( !empty($check_time) )
			{			
				if( $check_time >= (time() - $CONFIG['delay_flood']) ) //On calcul la fin du delai.	
					redirect(HOST . SCRIPT . transid('?error=flood', '', '&') . '#errorh');
			}

			$guestbook_contents = parse($guestbook_contents, $CONFIG_GUESTBOOK['guestbook_forbidden_tags']); 			
			if( !check_nbr_links($guestbook_pseudo, 0) ) //Nombre de liens max dans le pseudo.
				redirect(HOST . SCRIPT . transid('?error=l_pseudo', '', '&') . '#errorh');
			if( !check_nbr_links($guestbook_contents, $CONFIG_GUESTBOOK['guestbook_max_link']) ) //Nombre de liens max dans le message.
				redirect(HOST . SCRIPT . transid('?error=l_flood', '', '&') . '#errorh');
			
			$Sql->Query_inject("INSERT INTO ".PREFIX."guestbook (contents,login,user_id,timestamp) VALUES('" . $guestbook_contents . "', '" . $guestbook_pseudo . "', '" . $Member->Get_attribute('user_id') . "', '" . time() . "')", __LINE__, __FILE__);
			$last_msg_id = $Sql->Sql_insert_id("SELECT MAX(id) FROM ".PREFIX."guestbook"); //Dernier message ins�r�.
			
			redirect(HOST . SCRIPT . SID2 . '#m' . $last_msg_id);
		}
		else //utilisateur non autoris�!
			redirect(HOST . SCRIPT . transid('?error=auth', '', '&') . '#errorh');
	}
	else
		redirect(HOST . SCRIPT . transid('?error=incomplete', '', '&') . '#errorh');
}
elseif( retrieve(POST, 'previs', false) ) //Pr�visualisation.
{
	$Template->Set_filenames(array(
		'guestbook'=> 'guestbook/guestbook.tpl'
	));

	$user_id = $Sql->Query("SELECT user_id FROM ".PREFIX."guestbook WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
	$user_id = (int)$user_id;
	
	$guestbook_contents = retrieve(POST, 'guestbook_contents', '');
	$guestbook_pseudo = retrieve(POST, 'guestbook_pseudo', $LANG['guest']);

	//Pseudo du membre connect�.
	if( $user_id !== -1)
		$Template->Assign_block_vars('hidden_guestbook', array(
			'PSEUDO' => $guestbook_pseudo
		));
	else
		$Template->Assign_block_vars('visible_guestbook', array(
			'PSEUDO' => stripslashes($guestbook_pseudo)
		));

	$forbidden_tags = implode(', ', $CONFIG_GUESTBOOK['guestbook_forbidden_tags']);
	$Template->Assign_block_vars('guestbook', array(
		'CONTENTS' => second_parse(stripslashes(parse($guestbook_contents, $CONFIG_GUESTBOOK['guestbook_forbidden_tags']))),
		'PSEUDO' => stripslashes($guestbook_pseudo),
		'DATE' => gmdate_format('date_format_short'),
		'THEME' => $CONFIG['theme'],
		'FORBIDDEN_TAGS' => !empty($forbidden_tags) ? $forbidden_tags : '',
		'DISPLAY_FORBIDDEN_TAGS' => !empty($forbidden_tags) ? '[' . str_replace(', ', '], [', $forbidden_tags) . ']' : '',
		'L_FORBIDDEN_TAGS' => !empty($forbidden_tags) ? $LANG['forbidden_tags'] : '',
	));

	//On met � jour en cas d'�dition apr�s pr�visualisation du message
	$update = retrieve(GET, 'update', false);
	$update = $update && !empty($id_get) ? '?update=1&amp;id=' . $id_get : '';
	
	$Template->Assign_vars(array(
		'CONTENTS' => stripslashes($guestbook_contents),
		'PSEUDO' => stripslashes($guestbook_pseudo),
		'DATE' => gmdate_format('date_format_short'),
		'UPDATE' => transid($update),
		'ERROR' => '',
		'L_ALERT_TEXT' => $LANG['require_text'],
		'L_UPDATE_MSG' => $LANG['update_msg'],
		'L_REQUIRE' => $LANG['require'],
		'L_MESSAGE' => $LANG['message'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'], 
		'L_RESET' => $LANG['reset'],
		'L_ON' => $LANG['on']
	));	
	
	$_field = 'guestbook_contents';
	include_once('../kernel/framework/content/bbcode.php');
	
	$Template->Pparse('guestbook'); 
}
elseif( !empty($id_get) ) //Edition + suppression!
{
	$del = retrieve(GET, 'del', false);
	$edit = retrieve(GET, 'edit', false);
	$update = retrieve(GET, 'update', false);
	
	$row = $Sql->Query_array('guestbook', '*', 'WHERE id="' . $id_get . '"', __LINE__, __FILE__);
	$row['user_id'] = (int)$row['user_id'];
	
	if( $Member->Check_level(MODO_LEVEL) || ($row['user_id'] === $Member->Get_attribute('user_id') && $Member->Get_attribute('user_id') !== -1) )
	{
		if( $del )
		{
			$Sql->Query_inject("DELETE FROM ".PREFIX."guestbook WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
			$previous_id = $Sql->Query("SELECT MAX(id) FROM ".PREFIX."guestbook", __LINE__, __FILE__);
			
			redirect(HOST . SCRIPT . SID2 . '#m' . $previous_id);
		}
		elseif( $edit )
		{
			$Template->Set_filenames(array(
				'guestbook'=> 'guestbook/guestbook.tpl'
			));

			if( $row['user_id'] !== -1 )
				$Template->Assign_vars(array(
					'C_HIDDEN_GUESTBOOK' => true,
					'PSEUDO' => $row['login']
				));
			else
				$Template->Assign_vars(array(
					'C_VISIBLE_GUESTBOOK' => true,
					'PSEUDO' => $row['login']
				));		
			
			$forbidden_tags = implode(', ', $CONFIG_GUESTBOOK['guestbook_forbidden_tags']);
			$Template->Assign_vars(array(
				'UPDATE' => transid('?update=1&amp;id=' . $id_get),
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
			
			$_field = 'guestbook_contents';
			include_once('../kernel/framework/content/bbcode.php');
			
			$Template->Pparse('guestbook'); 
		}
		elseif( $update )
		{
			$guestbook_contents = retrieve(POST, 'guestbook_contents', '', TSTRING_UNSECURE);
			$guestbook_pseudo = retrieve(POST, 'guestbook_pseudo', $LANG['guest']);
			if( !empty($guestbook_contents) && !empty($guestbook_pseudo) )
			{
				$guestbook_contents = parse($guestbook_contents, $CONFIG_GUESTBOOK['guestbook_forbidden_tags']); 			
				if( !check_nbr_links($guestbook_contents, $CONFIG_GUESTBOOK['guestbook_max_link']) ) //Nombre de liens max dans le message.
					redirect(HOST . SCRIPT . transid('?error=l_flood', '', '&') . '#errorh');
			
				$Sql->Query_inject("UPDATE ".PREFIX."guestbook SET contents = '" . $guestbook_contents . "', login = '" . $guestbook_pseudo . "' WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
			
				redirect(HOST . SCRIPT. SID2 . '#m' . $id_get);
			}
			else
				$Errorh->Error_handler('e_incomplete', E_USER_REDIRECT);
		}
		else
			redirect(HOST . SCRIPT . SID2);
	}
	else
		redirect(HOST . SCRIPT . SID2);
}
else //Affichage.
{
	$Template->Set_filenames(array(
		'guestbook'=> 'guestbook/guestbook.tpl'
	));
		
	//Pseudo du membre connect�.
	if( $Member->Get_attribute('user_id') !== -1 )
		$Template->Assign_vars(array(
			'C_HIDDEN_GUESTBOOK' => true,
			'PSEUDO' => $Member->Get_attribute('login')
		));
	else
		$Template->Assign_vars(array(
			'C_VISIBLE_GUESTBOOK' => true,
			'PSEUDO' => $LANG['guest']
		));
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	switch($get_error)
	{
		case 'auth':
		$errstr = $LANG['e_unauthorized'];
		break;
		case 'flood':
		$errstr = $LANG['e_flood'];
		break;
		case 'l_flood':
		$errstr = sprintf($LANG['e_l_flood'], $CONFIG_GUESTBOOK['guestbook_max_link']);
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
		$Errorh->Error_handler($errstr, E_USER_NOTICE);
	
	$nbr_guestbook = $Sql->Count_table('guestbook', __LINE__, __FILE__);
	//On cr�e une pagination si le nombre de msg est trop important.
	include_once('../kernel/framework/pagination.class.php'); 
	$Pagination = new Pagination();
		
	$forbidden_tags = implode(', ', $CONFIG_GUESTBOOK['guestbook_forbidden_tags']);
	$Template->Assign_vars(array(
		'UPDATE' => transid(''),
		'PAGINATION' => $Pagination->Display_pagination('guestbook' . transid('.php?p=%d'), $nbr_guestbook, 'p', 10, 3),
		'FORBIDDEN_TAGS' => !empty($forbidden_tags) ? $forbidden_tags : '',
		'DISPLAY_FORBIDDEN_TAGS' => !empty($forbidden_tags) ? '[' . str_replace(', ', '], [', $forbidden_tags) . ']' : '',
		'L_FORBIDDEN_TAGS' => !empty($forbidden_tags) ? $LANG['forbidden_tags'] : '',
		'L_ALERT_TEXT' => $LANG['require_text'],
		'L_DELETE_MSG' => $LANG['alert_delete_msg'],
		'L_ADD_MSG' => $LANG['add_msg'],
		'L_REQUIRE' => $LANG['require'],
		'L_MESSAGE' => $LANG['message'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'], 
		'L_RESET' => $LANG['reset'],
		'L_ON' => $LANG['on']
	));
		
	//Cr�ation du tableau des rangs.
	$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	
	//Gestion des rangs.	
	$Cache->Load_file('ranks');
	$j = 0;
	$result = $Sql->Query_while("SELECT g.id, g.login, g.user_id, g.timestamp, m.login as mlogin, m.level, m.user_mail, m.user_show_mail, m.timestamp AS registered, m.user_avatar, m.user_msg, m.user_local, m.user_web, m.user_sex, m.user_msn, m.user_yahoo, m.user_sign, m.user_warning, m.user_ban, m.user_groups, s.user_id AS connect, g.contents
	FROM ".PREFIX."guestbook g
	LEFT JOIN ".PREFIX."member m ON m.user_id = g.user_id
	LEFT JOIN ".PREFIX."sessions s ON s.user_id = g.user_id AND s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "'
	GROUP BY g.id
	ORDER BY g.timestamp DESC 
	" . $Sql->Sql_limit($Pagination->First_msg(10, 'p'), 10), __LINE__, __FILE__);	
	while ($row = $Sql->Sql_fetch_assoc($result))
	{
		$row['user_id'] = (int)$row['user_id'];
		$edit = '';
		$del = '';
		
		$is_guest = ($row['user_id'] === -1);
		$is_modo = $Member->Check_level(MODO_LEVEL);
		$warning = '';
		$readonly = '';
		if( $is_modo && !$is_guest ) //Mod�ration.
		{
			$warning = '&nbsp;<a href="../member/moderation_panel' . transid('.php?action=warning&amp;id=' . $row['user_id']) . '" title="' . $LANG['warning_management'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/admin/important.png" alt="' . $LANG['warning_management'] .  '" class="valign_middle" /></a>'; 
			$readonly = '<a href="../member/moderation_panel' . transid('.php?action=punish&amp;id=' . $row['user_id']) . '" title="' . $LANG['punishment_management'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/readonly.png" alt="' . $LANG['punishment_management'] .  '" class="valign_middle" /></a>'; 
		}
		
		//Edition/suppression.
		if( $is_modo || ($row['user_id'] === $Member->Get_attribute('user_id') && $Member->Get_attribute('user_id') !== -1) )
		{
			$edit = '&nbsp;&nbsp;<a href="../guestbook/guestbook' . transid('.php?edit=1&id=' . $row['id']) . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="' . $LANG['edit'] . '" title="' . $LANG['edit'] . '" class="valign_middle" /></a>';
			$del = '&nbsp;&nbsp;<a href="../guestbook/guestbook' . transid('.php?del=1&id=' . $row['id']) . '" onClick="javascript:return Confirm();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="' . $LANG['delete'] . '" title="' . $LANG['delete'] . '" class="valign_middle" /></a>';
		}
		
		//Pseudo.
		if( !$is_guest ) 
			$guestbook_pseudo = '<a class="msg_link_pseudo" href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" title="' . $row['mlogin'] . '"><span style="font-weight: bold;">' . wordwrap_html($row['mlogin'], 13) . '</span></a>';
		else
			$guestbook_pseudo = '<span style="font-style:italic;">' . (!empty($row['login']) ? wordwrap_html($row['login'], 13) : $LANG['guest']) . '</span>';
		
		//Rang de l'utilisateur.
		$user_rank = ($row['level'] === '0') ? $LANG['member'] : $LANG['guest'];
		$user_group = $user_rank;
		$user_rank_icon = '';
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
		$user_assoc_img = !empty($user_rank_icon) ? '<img src="../templates/' . $CONFIG['theme'] . '/images/ranks/' . $user_rank_icon . '" alt="" />' : '';
					
		//Affichage des groupes du membre.		
		if( !empty($row['user_groups']) && $_array_groups_auth ) 
		{	
			$user_groups = '';
			$array_user_groups = explode('|', $row['user_groups']);
			foreach($_array_groups_auth as $idgroup => $array_group_info)
			{
				if( is_numeric(array_search($idgroup, $array_user_groups)) )
					$user_groups .= !empty($array_group_info['img']) ? '<img src="../images/group/' . $array_group_info['img'] . '" alt="' . $array_group_info['name'] . '" title="' . $array_group_info['name'] . '"/><br />' : $LANG['group'] . ': ' . $array_group_info['name'];
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
		
		$Template->Assign_block_vars('guestbook',array(
			'ID' => $row['id'],
			'CONTENTS' => ucfirst(second_parse($row['contents'])),
			'DATE' => $LANG['on'] . ': ' . gmdate_format('date_format', $row['timestamp']),
			'CLASS_COLOR' => ($j%2 == 0) ? '' : 2,
			'USER_ONLINE' => '<img src="../templates/' . $CONFIG['theme'] . '/images/' . $user_online . '.png" alt="" class="valign_middle" />',
			'USER_PSEUDO' => $guestbook_pseudo,			
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
			'U_MEMBER_PM' => !$is_guest ? '<a href="../member/pm' . transid('.php?pm=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/pm.png" alt="" /></a>' : '',
			'U_ANCHOR' => 'guestbook.php' . SID . '#m' . $row['id']
		));
		$j++;
	}
	$Sql->Close($result);
		
	$_field = 'guestbook_contents';
	include_once('../kernel/framework/content/bbcode.php');
		
	$Template->Pparse('guestbook'); 
}

require_once('../kernel/footer.php'); 

?>