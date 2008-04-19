<?php
/*##################################################
 *                               moderation_panel.php
 *                            -------------------
 *   begin                : March 20, 2007
 *   copyright          : (C) 2007 Viarre Régis
 *   email                :   crowkait@phpboost.com
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
$Speed_bar->Add_link($LANG['moderation_panel'], transid('moderation_panel.php'));
define('TITLE', $LANG['moderation_panel']);
require_once('../includes/header.php');

if( !$Member->Check_level(MODO_LEVEL) ) //Si il n'est pas modérateur
	$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 

include('../includes/moderation_panel_begin.php');	
	
$Template->Set_filenames(array(
	'moderation_panel'=> 'moderation_panel.tpl'
));	

$Template->Assign_vars(array(
	'SID' => SID,
	'LANG' => $CONFIG['lang'],
	'THEME' => $CONFIG['theme'],
	'L_PUNISHMENT' => $LANG['punishment'],
	'L_WARNING' => $LANG['warning'],
	'L_BAN' => $LANG['bans'],
	'L_USERS_PUNISHMENT' => $LANG['punishment_management'],
	'L_USERS_WARNING' => $LANG['warning_management'],
	'L_USERS_BAN' => $LANG['ban_management'],
	'U_WARNING' => transid('.php?action=warning'),
	'U_PUNISH' => transid('.php?action=punish'),
	'U_BAN' => transid('.php?action=ban')
));
	
$action = !empty($_GET['action']) ? trim($_GET['action']) : '';
$id_get = !empty($_GET['id']) ? numeric($_GET['id']) : 0;
if( $action == 'punish' ) //Gestion des utilisateurs
{
	$readonly = isset($_POST['new_info']) ? numeric($_POST['new_info']) : 0;
	$readonly = $readonly > 0 ? (time() + $readonly) : 0;
	$readonly_contents = !empty($_POST['action_contents']) ? trim($_POST['action_contents']) : '';
	if( !empty($id_get) && !empty($_POST['valid_user']) ) //On met à  jour le niveau d'avertissement
	{
		$info_mbr = $Sql->Query_array('member', 'user_id', 'level', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
		
		//Modérateur ne peux avertir l'admin (logique non?).
		if( !empty($info_mbr['user_id']) && ($info_mbr['level'] < 2 || $Member->Check_level(ADMIN_LEVEL)) )
		{
			$Sql->Query_inject("UPDATE ".PREFIX."member SET user_readonly = '" . $readonly . "' WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);
			
			//Envoi d'un MP au membre pour lui signaler, si le membre en question n'est pas lui-même.
			if( $info_mbr['user_id'] != $Member->Get_attribute('user_id') )
			{
				if( !empty($readonly_contents) && !empty($readonly) )
				{					
					include_once('../includes/pm.class.php');
					$Privatemsg = new Privatemsg();
					
					//Envoi du message.
					$Privatemsg->Send_pm($info_mbr['user_id'], addslashes($LANG['read_only_title']), str_replace('%date', gmdate_format('date_format', $readonly), $readonly_contents), '-1', SYSTEM_PM);
				}
			}
		}
		
		redirect(HOST . DIR . transid('/member/moderation_panel.php?action=punish', '', '&'));
	}
	
	$Template->Assign_vars(array(
		'C_MODO_PANEL_MEMBER' => true,
		'L_ACTION_INFO' => $LANG['punishment_management'],
		'L_LOGIN' => $LANG['pseudo'],
		'L_INFO_MANAGEMENT' => $LANG['punishment_management'],
		'U_XMLHTTPREQUEST' => 'punish_user',
		'U_ACTION' => '.php?action=punish'
	));
	
	if( empty($id_get) ) //On liste les membres qui ont déjà un avertissement
	{
		if( !empty($_POST['search_member']) )
		{
			$login = !empty($_POST['login_mbr']) ? securit($_POST['login_mbr']) : '';
			$user_id = $Sql->Query("SELECT user_id FROM ".PREFIX."member WHERE login LIKE '%" . $login . "%'", __LINE__, __FILE__);
			if( !empty($user_id) && !empty($login) )
				redirect(HOST . DIR . transid('/member/moderation_panel.php?action=punish&id=' . $user_id, '', '&'));
			else
				redirect(HOST . DIR . transid('/member/moderation_panel.php?action=punish', '', '&'));
		}	
				
		$Template->Assign_vars(array(
			'C_MODO_PANEL_USER_LIST' => true,
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_punish_until'],
			'L_ACTION_USER' => $LANG['punishment_management'],
			'L_PROFILE' => $LANG['profil'],
			'L_SEARCH_MEMBER' => $LANG['search_member'],
			'L_SEARCH' => $LANG['search'],
			'L_REQUIRE_LOGIN' => $LANG['require_pseudo']
		));	
			
		$i = 0;
		$result = $Sql->Query_while("SELECT user_id, login, user_readonly
		FROM ".PREFIX."member
		WHERE user_readonly > " . time() . "
		ORDER BY user_readonly DESC", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$Template->Assign_block_vars('member_list', array(
				'LOGIN' => '<a href="moderation_panel.php?action=punish&amp;id=' . $row['user_id'] . '">' . $row['login'] . '</a>',
				'INFO' => gmdate_format('date_format', $row['user_readonly']),
				'U_PROFILE' => '../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
				'U_ACTION_USER' => '<a href="moderation_panel.php?action=punish&amp;id=' . $row['user_id'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/readonly.png" alt="" /></a>',
				'U_PM' => transid('.php?pm='. $row['user_id'], '-' . $row['user_id'] . '.php'),
			));
			
			$i++;
		}
		if( $i === 0 )
		{
			$Template->Assign_vars(array(
				'C_EMPTY_LIST' => true,
				'L_NO_USER' => $LANG['no_punish'],
			));
		}
	}
	else //On affiche les infos sur l'utilisateur
	{
		$member = $Sql->Query_array('member', 'login', 'user_readonly', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
				
		//On crée le formulaire select
		$select = '';
		//Durée de la sanction.
		$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 326592000); 	
		$array_sanction = array($LANG['no'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '1 ' . $LANG['day'], '2 ' . $LANG['days'], '1 ' . $LANG['week'], '2 ' . $LANG['weeks'], '1 ' . $LANG['month'], '10 ' . strtolower($LANG['years'])); 

		$diff = ($member['user_readonly'] - time());	
		$key_sanction = 0;
		if( $diff > 0 )
		{
			//Retourne la sanction la plus proche correspondant au temp de bannissement. 
			for($i = 11; $i > 0; $i--)
			{					
				$avg = ceil(($array_time[$i] + $array_time[$i-1])/2);
				if( ($diff - $array_time[$i]) > $avg ) 
				{	
					$key_sanction = $i + 1;
					break;
				}
			}
		}
		//Affichge des sanctions
		foreach( $array_time as $key => $time)
		{
			$selected = ($key_sanction == $key) ? 'selected="selected"' : '' ;
			$select .= '<option value="' . $time . '" ' . $selected . '>' . strtolower($array_sanction[$key]) . '</option>';
		}	
		
		$Template->Assign_vars(array(
			'C_MODO_PANEL_USER_INFO' => true,
			'ALTERNATIVE_PM' => ($key_sanction > 0) ? str_replace('%date%', $array_sanction[$key_sanction], $LANG['user_readonly_changed']) : str_replace('%date%', '1 ' . $LANG['minute'], $LANG['user_readonly_changed']),
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
			'REGEX'=> '[0-9]+ [a-zA-Z]+/',
			'U_PM' => transid('.php?pm='. $id_get, '-' . $id_get . '.php'),
			'U_ACTION_INFO' => '.php?action=punish&amp;id=' . $id_get,
			'L_ALTERNATIVE_PM' => $LANG['user_alternative_pm'],
			'L_INFO_EXPLAIN' => $LANG['user_readonly_explain'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_LOGIN' => $LANG['pseudo'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_CHANGE_INFO' => $LANG['submit']
		));		

		$_field = 'action_contents';
		include_once('../includes/bbcode.php');		
	}	
}
elseif( $action == 'warning' ) //Gestion des utilisateurs
{
	$new_warning_level = isset($_POST['new_info']) ? numeric($_POST['new_info']) : 0;
	$warning_contents = !empty($_POST['action_contents']) ? trim($_POST['action_contents']) : '';
	if( $new_warning_level >= 0 && $new_warning_level <= 100 && isset($_POST['new_info']) && !empty($id_get) && !empty($_POST['valid_user']) ) //On met à  jour le niveau d'avertissement
	{
		$info_mbr = $Sql->Query_array('member', 'user_id', 'level', 'user_mail', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
		
		//Modérateur ne peux avertir l'admin (logique non?).
		if( !empty($info_mbr['user_id']) && ($info_mbr['level'] < 2 || $Member->Check_level(ADMIN_LEVEL)) )
		{
			if( $new_warning_level < 100 ) //Ne peux pas mettre des avertissements supérieurs à 100.
			{
				$Sql->Query_inject("UPDATE ".PREFIX."member SET user_warning = '" . $new_warning_level . "' WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);
				
				//Envoi d'un MP au membre pour lui signaler, si le membre en question n'est pas lui-même.
				if( $info_mbr['user_id'] != $Member->Get_attribute('user_id') )
				{					
					if( !empty($warning_contents) )
					{					
						include_once('../includes/pm.class.php');
						$Privatemsg = new Privatemsg();
						
						//Envoi du message.
						$Privatemsg->Send_pm($info_mbr['user_id'], addslashes($LANG['warning_title']), $warning_contents, '-1', SYSTEM_PM);
					}
				}
			}
			elseif( $new_warning_level == 100 ) //Ban => on supprime sa session et on le banni (pas besoin d'envoyer de pm :p).
			{
				$Sql->Query_inject("UPDATE ".PREFIX."member SET user_warning = 100 WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);
				$Sql->Query_inject("DELETE FROM ".PREFIX."sessions WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);
			
				//Envoi du mail
				include_once('../includes/mail.class.php');
				$Mail = new Mail();
				$Mail->Send_mail($info_mbr['user_mail'], addslashes($LANG['ban_title_mail']), sprintf(addslashes($LANG['ban_mail']), HOST, addslashes($CONFIG['sign'])), $CONFIG['mail']);
			}	
		}
		
		redirect(HOST . DIR . transid('/member/moderation_panel.php?action=warning', '', '&'));
	}
	
	$Template->Assign_vars(array(
		'C_MODO_PANEL_MEMBER' => true,
		'L_ACTION_INFO' => $LANG['warning_management'],
		'L_LOGIN' => $LANG['pseudo'],
		'L_INFO_MANAGEMENT' => $LANG['warning_management'],
		'U_XMLHTTPREQUEST' => 'warning_user',		
		'U_ACTION' => '.php?action=warning'
	));
	
	if( empty($id_get) ) //On liste les membres qui ont déjà un avertissement
	{
		if( !empty($_POST['search_member']) )
		{
			$login = !empty($_POST['login_mbr']) ? securit($_POST['login_mbr']) : '';
			$user_id = $Sql->Query("SELECT user_id FROM ".PREFIX."member WHERE login LIKE '%" . $login . "%'", __LINE__, __FILE__);
			if( !empty($user_id) && !empty($login) )
				redirect(HOST . DIR . transid('/member/moderation_panel.php?action=warning&id=' . $user_id, '', '&'));
			else
				redirect(HOST . DIR . transid('/member/moderation_panel.php?action=warning', '', '&'));
		}		
		
		$Template->Assign_vars(array(
			'C_MODO_PANEL_USER_LIST' => true,
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_warning_level'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_ACTION_USER' => $LANG['warning_management'],
			'L_SEARCH_MEMBER' => $LANG['search_member'],
			'L_SEARCH' => $LANG['search'],
			'L_REQUIRE_LOGIN' => $LANG['require_pseudo']
		));
		
		$i = 0;
		$result = $Sql->Query_while("SELECT user_id, login, user_warning
		FROM ".PREFIX."member
		WHERE user_warning > 0
		ORDER BY user_warning", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$Template->Assign_block_vars('member_list', array(
				'LOGIN' => $row['login'],
				'INFO' => $row['user_warning'] . '%',
				'U_ACTION_USER' => '<a href="moderation_panel.php?action=warning&amp;id=' . $row['user_id'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/admin/important.png" alt="" /></a>',
				'U_PROFILE' => '../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
				'U_PM' => transid('.php?pm='. $row['user_id'], '-' . $row['user_id'] . '.php'),
			));
			
			$i++;
		}
		if( $i === 0 )
		{
			$Template->Assign_vars(array(
				'C_EMPTY_LIST' => true,
				'L_NO_USER' => $LANG['no_user_warning'],
			));
		}
	}
	else //On affiche les infos sur l'utilisateur
	{
		$member = $Sql->Query_array('member', 'login', 'user_warning', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
					
		//On crée le formulaire select
		$select = '';
		$j = 0;
		for($j = 0; $j <=10; $j++)
		{
			if( 10 * $j == $member['user_warning'] ) 
				$select .= '<option value="' . 10 * $j . '" selected="selected">' . 10 * $j . '%</option>';
			else
				$select .= '<option value="' . 10 * $j . '">' . 10 * $j . '%</option>';
		}
		$Template->Assign_vars(array(
			'C_MODO_PANEL_USER_INFO' => true,
			'ALTERNATIVE_PM' => str_replace('%level%', $member['user_warning'], $LANG['user_warning_level_changed']),
			'LOGIN' => '<a href="../member/member' . transid('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $member['login'] . '</a>',
			'INFO' => $LANG['user_warning_level'] . ': ' . $member['user_warning'] . '%',
			'SELECT' => $select,
			'REPLACE_VALUE' => 'contents = contents.replace(regex, \' \' + replace_value + \'%\');' . "\n" . 'document.getElementById(\'action_info\').innerHTML = \'' . addslashes($LANG['user_warning_level']) . ': \' + replace_value + \'%\';',
			'REGEX'=> ' [0-9]+%/',
			'U_ACTION_INFO' => '.php?action=warning&amp;id=' . $id_get,
			'U_PM' => transid('.php?pm='. $id_get, '-' . $id_get . '.php'),
			'L_ALTERNATIVE_PM' => $LANG['user_alternative_pm'],
			'L_INFO_EXPLAIN' => $LANG['user_warning_explain'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_warning_level'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_CHANGE_INFO' => $LANG['change_user_warning']
		));			

		$_field = 'action_contents';
		include_once('../includes/bbcode.php');
	}	
}
elseif( $action == 'ban' ) //Gestion des utilisateurs
{
	$user_ban = !empty($_POST['user_ban']) ? trim($_POST['user_ban']) : '';
	$user_ban = $user_ban > 0 ? (time() + $user_ban) : 0;
	if( !empty($_POST['valid_user']) && !empty($id_get) ) //On banni le membre
	{
		$info_mbr = $Sql->Query_array('member', 'user_id', 'level', 'user_warning', 'user_mail', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
		//Modérateur ne peux avertir l'admin (logique non?).
		if( !empty($info_mbr['user_id']) && ($info_mbr['level'] < 2 || $Member->Check_level(ADMIN_LEVEL)) )
		{
			$Sql->Query_inject("UPDATE ".PREFIX."member SET user_ban = '" . $user_ban . "' WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);			
			
			//Si avertissement à 100% et débanni, on réduit l'avertissement à 90%.
			if( $user_ban == 0 && $info_mbr['user_warning'] == 100 )
				$Sql->Query_inject("UPDATE ".PREFIX."member SET user_warning = '90' WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);
			
			if( !empty($user_ban) ) //Envoi du mail
			{
				include_once('../includes/mail.class.php');
				$Mail = new Mail();
				$Mail->Send_mail($info_mbr['user_mail'], addslashes($LANG['ban_title_mail']), sprintf(addslashes($LANG['ban_mail']), HOST, addslashes($CONFIG['sign'])), $CONFIG['mail']);
			}			
		}		
		redirect(HOST . DIR . transid('/member/moderation_panel.php?action=ban', '', '&'));
	}
	
	$Template->Assign_vars(array(
		'C_MODO_PANEL_MEMBER' => true,
		'L_ACTION_INFO' => $LANG['ban_management'],
		'L_LOGIN' => $LANG['pseudo'],
		'L_INFO_MANAGEMENT' => $LANG['ban_management'],
		'U_XMLHTTPREQUEST' => 'ban_user',
		'U_ACTION' => '.php?action=ban'
	));
	
	if( empty($id_get) ) //On liste les membres qui ont déjà un avertissement
	{
		if( !empty($_POST['search_member']) )
		{
			$login = !empty($_POST['login_mbr']) ? securit($_POST['login_mbr']) : '';
			$user_id = $Sql->Query("SELECT user_id FROM ".PREFIX."member WHERE login LIKE '%" . $login . "%'", __LINE__, __FILE__);
			if( !empty($user_id) && !empty($login) )
				redirect(HOST . DIR . transid('/member/moderation_panel.php?action=ban&id=' . $user_id, '', '&'));
			else
				redirect(HOST . DIR . transid('/member/moderation_panel.php?action=ban', '', '&'));
		}	
		
		$Template->Assign_vars(array(
			'C_MODO_PANEL_USER_LIST' => true,
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_ban_until'],
			'L_ACTION_USER' => $LANG['ban_management'],
			'L_PROFILE' => $LANG['profil'],
			'L_SEARCH_MEMBER' => $LANG['search_member'],
			'L_SEARCH' => $LANG['search'],
			'L_REQUIRE_LOGIN' => $LANG['require_pseudo']
		));	
			
		$i = 0;
		$result = $Sql->Query_while("SELECT user_id, login, user_ban, user_warning
		FROM ".PREFIX."member
		WHERE user_ban > " . time() . " OR user_warning = 100
		ORDER BY user_ban", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$Template->Assign_block_vars('member_list', array(
				'LOGIN' => '<a href="moderation_panel.php?action=ban&amp;id=' . $row['user_id'] . '">' . $row['login'] . '</a>',
				'INFO' => ($row['user_warning'] != 100) ? gmdate_format('date_format', $row['user_ban']) : $LANG['illimited'],
				'U_PROFILE' => '../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
				'U_ACTION_USER' => '<a href="moderation_panel.php?action=ban&amp;id=' . $row['user_id'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/admin/forbidden.png" alt="" /></a>',
				'U_PM' => transid('.php?pm='. $row['user_id'], '-' . $row['user_id'] . '.php'),
			));
			
			$i++;
		}
		if( $i === 0 )
		{
			$Template->Assign_vars(array(
				'C_EMPTY_LIST' => true,
				'L_NO_USER' => $LANG['no_ban'],
			));
		}
	}
	else //On affiche les infos sur l'utilisateur
	{
		$mbr = $Sql->Query_array('member', 'login', 'user_ban', 'user_warning', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
		$Template->Assign_vars(array(
			'C_MODO_PANEL_USER_BAN' => true,
			'LOGIN' => '<a href="../member/member' . transid('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $mbr['login'] . '</a>',
			'U_PM' => transid('.php?pm='. $id_get, '-' . $id_get . '.php'),
			'U_ACTION_INFO' => '.php?action=ban&amp;id=' . $id_get,
			'L_PM' => $LANG['user_contact_pm'],
			'L_LOGIN' => $LANG['pseudo'],
			'L_BAN' => $LANG['ban_user'],
			'L_DELAY_BAN' => $LANG['user_ban_delay'],
		));	
		
		//Temps de bannissement.
		$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 326592000);
		$array_sanction = array($LANG['no'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '1 ' . $LANG['day'], '2 ' . $LANG['days'], '1 ' . $LANG['week'], '2 ' . $LANG['weeks'], '1 ' . $LANG['month'], $LANG['illimited']); 
		
		$diff = ($mbr['user_ban'] - time());	
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
		if( $mbr['user_warning'] == 100 )
			$key_sanction = 12;
			
		//Affichge des sanctions
		foreach( $array_time as $key => $time)
		{
			$selected = ($key_sanction == $key) ? 'selected="selected"' : '' ;
			$Template->Assign_block_vars('select_ban', array(
				'TIME' => '<option value="' . $time . '" ' . $selected . '>' . $array_sanction[$key] . '</option>'
			));
		}	
		$_field = 'action_contents';
		include_once('../includes/bbcode.php');
	}
}

$Template->Pparse('moderation_panel');

require_once('../includes/footer.php');

?>