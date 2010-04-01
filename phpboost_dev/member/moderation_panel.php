<?php
/*##################################################
 *                            moderation_panel.php
 *                            -------------------
 *   begin                : March 20, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                :  crowkait@phpboost.com
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

$Bread_crumb->add($LANG['member_area'], url('member.php?id=' . $User->get_attribute('user_id') . '&amp;view=1', 'member-' . $User->get_attribute('user_id') . '.php?view=1'));
$Bread_crumb->add($LANG['moderation_panel'], url('moderation_panel.php'));

$action = retrieve(GET, 'action', 'warning', TSTRING_UNCHANGE);
$id_get = retrieve(GET, 'id', 0);
switch ($action)
{
	case 'ban':
		$Bread_crumb->add($LANG['bans'], url('moderation_panel.php?action=ban'));
		break;
	case 'punish':
		$Bread_crumb->add($LANG['punishment'], url('moderation_panel.php?action=punish'));
		break;
	case 'warning':
	default:
		$Bread_crumb->add($LANG['warning'], url('moderation_panel.php?action=warning'));
}

define('TITLE', $LANG['moderation_panel']);
require_once('../kernel/header.php');

if (!$User->check_level(MODO_LEVEL)) //Si il n'est pas modérateur
	$Errorh->handler('e_auth', E_USER_REDIRECT);

$moderation_panel_template = new FileTemplate('member/moderation_panel.tpl');	

$moderation_panel_template->assign_vars(array(
	'SID' => SID,
	'LANG' => get_ulang(),
	'THEME' => get_utheme(),
	'L_MODERATION_PANEL' => $LANG['moderation_panel'],
	'L_PUNISHMENT' => $LANG['punishment'],
	'L_WARNING' => $LANG['warning'],
	'L_BAN' => $LANG['bans'],
	'L_USERS_PUNISHMENT' => $LANG['punishment_management'],
	'L_USERS_WARNING' => $LANG['warning_management'],
	'L_USERS_BAN' => $LANG['ban_management'],
	'U_WARNING' => url('.php?action=warning'),
	'U_PUNISH' => url('.php?action=punish'),
	'U_BAN' => url('.php?action=ban')
));

switch ($action)
{
	case 'punish': //Gestion des utilisateurs
		$readonly = retrieve(POST, 'new_info', 0);
		$readonly = $readonly > 0 ? (time() + $readonly) : 0;
		$readonly_contents = retrieve(POST, 'action_contents', '', TSTRING_UNCHANGE);
		if (!empty($id_get) && !empty($_POST['valid_user'])) //On met à  jour le niveau d'avertissement
		{
			$info_mbr = $Sql->query_array(DB_TABLE_MEMBER, 'user_id', 'level', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
			
			//Modérateur ne peux avertir l'admin (logique non?).
			if (!empty($info_mbr['user_id']) && ($info_mbr['level'] < 2 || $User->check_level(ADMIN_LEVEL)))
			{
				$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_readonly = '" . $readonly . "' WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);
				
				//Envoi d'un MP au membre pour lui signaler, si le membre en question n'est pas lui-même.
				if ($info_mbr['user_id'] != $User->get_attribute('user_id'))
				{
					if (!empty($readonly_contents) && !empty($readonly))
					{					
						
						
						//Envoi du message.
						PrivateMsg::start_conversation($info_mbr['user_id'], addslashes($LANG['read_only_title']), str_replace('%date', gmdate_format('date_format', $readonly), $readonly_contents), '-1', PrivateMsg::SYSTEM_PM);
					}
				}
			}
			
			AppContext::get_response()->redirect(HOST . DIR . url('/member/moderation_panel.php?action=punish', '', '&'));
		}
		
		$moderation_panel_template->assign_vars(array(
			'C_MODO_PANEL_USER' => true,
			'L_ACTION_INFO' => $LANG['punishment_management'],
			'L_LOGIN' => $LANG['pseudo'],
			'L_INFO_MANAGEMENT' => $LANG['punishment_management'],
			'U_XMLHTTPREQUEST' => 'punish_user',
			'U_ACTION' => '.php?action=punish&amp;token=' . $Session->get_token()
		));
		
		if (empty($id_get)) //On liste les membres qui ont déjà un avertissement
		{
			if (!empty($_POST['search_member']))
			{
				$login = retrieve(POST, 'login_mbr', '');
				$user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '%" . $login . "%'", __LINE__, __FILE__);
				if (!empty($user_id) && !empty($login))
					AppContext::get_response()->redirect(HOST . DIR . url('/member/moderation_panel.php?action=punish&id=' . $user_id, '', '&'));
				else
					AppContext::get_response()->redirect(HOST . DIR . url('/member/moderation_panel.php?action=punish', '', '&'));
			}	
					
			$moderation_panel_template->assign_vars(array(
				'C_MODO_PANEL_USER_LIST' => true,
				'L_PM' => $LANG['user_contact_pm'],
				'L_INFO' => $LANG['user_punish_until'],
				'L_ACTION_USER' => $LANG['punishment_management'],
				'L_PROFILE' => $LANG['profile'],
				'L_SEARCH_USER' => $LANG['search_member'],
				'L_SEARCH' => $LANG['search'],
				'L_REQUIRE_LOGIN' => $LANG['require_pseudo']
			));	
				
			$i = 0;
			$result = $Sql->query_while("SELECT user_id, login, user_readonly
			FROM " . PREFIX . "member
			WHERE user_readonly > " . time() . "
			ORDER BY user_readonly DESC", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$moderation_panel_template->assign_block_vars('member_list', array(
					'LOGIN' => $row['login'],
					'INFO' => gmdate_format('date_format', $row['user_readonly']),
					'U_PROFILE' => '../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
					'U_ACTION_USER' => '<a href="moderation_panel.php?action=punish&amp;id=' . $row['user_id'] . '"><img src="../templates/' . get_utheme() . '/images/readonly.png" alt="" /></a>',
					'U_PM' => url('.php?pm='. $row['user_id'], '-' . $row['user_id'] . '.php'),
				));
				
				$i++;
			}
			if ($i === 0)
			{
				$moderation_panel_template->assign_vars(array(
					'C_EMPTY_LIST' => true,
					'L_NO_USER' => $LANG['no_punish'],
				));
			}
		}
		else //On affiche les infos sur l'utilisateur
		{
			$member = $Sql->query_array(DB_TABLE_MEMBER, 'login', 'user_readonly', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
					
			//On crée le formulaire select
			$select = '';
			//Durée de la sanction.
			$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 326592000); 	
			$array_sanction = array($LANG['no'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '1 ' . $LANG['day'], '2 ' . $LANG['days'], '1 ' . $LANG['week'], '2 ' . $LANG['weeks'], '1 ' . $LANG['month'], '10 ' . strtolower($LANG['years'])); 
	
			$diff = ($member['user_readonly'] - time());	
			$key_sanction = 0;
			if ($diff > 0)
			{
				//Retourne la sanction la plus proche correspondant au temp de bannissement. 
				for ($i = 11; $i > 0; $i--)
				{					
					$avg = ceil(($array_time[$i] + $array_time[$i-1])/2);
					if (($diff - $array_time[$i]) > $avg) 
					{	
						$key_sanction = $i + 1;
						break;
					}
				}
			}
			//Affichge des sanctions
			foreach ($array_time as $key => $time)
			{
				$selected = ($key_sanction == $key) ? 'selected="selected"' : '' ;
				$select .= '<option value="' . $time . '" ' . $selected . '>' . strtolower($array_sanction[$key]) . '</option>';
			}	
			
			array_pop($array_sanction);
			$moderation_panel_template->assign_vars(array(
				'C_MODO_PANEL_USER_INFO' => true,
				'KERNEL_EDITOR' => display_editor('action_contents'),
				'ALTERNATIVE_PM' => ($key_sanction > 0) ? str_replace('%date%', $array_sanction[$key_sanction], $LANG['user_readonly_changed']) : str_replace('%date%', '1 ' . $LANG['minute'], $LANG['user_readonly_changed']),
				'LOGIN' => '<a href="../member/member' . url('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $member['login'] . '</a>',
				'INFO' => $array_sanction[$key_sanction],
				'SELECT' => $select,
				'REPLACE_VALUE' => 'replace_value = parseInt(replace_value);'. "\n" .
				'if (replace_value != \'326592000\'){'. "\n" .
				'array_time = new Array(' . (implode(', ', $array_time)) . ');' . "\n" .
				'array_sanction = new Array(\'' . implode('\', \'', array_map('addslashes', $array_sanction)) . '\');'. "\n" .
				'var i;
				for (i = 0; i <= 11; i++)
				{
					if (array_time[i] == replace_value)
					{
						replace_value = array_sanction[i];
						break;
					}
				}' . "\n" .
				'if (replace_value != \'' . addslashes($LANG['no']) . '\')' . "\n" .
				'{' . "\n" .
					'contents = contents.replace(regex, replace_value);' . "\n" .
					'document.getElementById(\'action_contents\').disabled = \'\'' . "\n" .
				'} else' . "\n" .
				'	document.getElementById(\'action_contents\').disabled = \'disabled\';' . "\n" .
				'document.getElementById(\'action_info\').innerHTML = replace_value;}',
				'REGEX'=> '/[0-9]+ [a-zA-Z]+/',
				'U_PM' => url('.php?pm='. $id_get, '-' . $id_get . '.php'),
				'U_ACTION_INFO' => '.php?action=punish&amp;id=' . $id_get . '&amp;token=' . $Session->get_token(),
				'L_ALTERNATIVE_PM' => $LANG['user_alternative_pm'],
				'L_INFO_EXPLAIN' => $LANG['user_readonly_explain'],
				'L_PM' => $LANG['user_contact_pm'],
				'L_LOGIN' => $LANG['pseudo'],
				'L_PM' => $LANG['user_contact_pm'],
				'L_CHANGE_INFO' => $LANG['submit']
			));		
		}
		break;
	case 'ban': //Gestion des utilisateurs
	default:
		$user_ban = retrieve(POST, 'user_ban', '', TSTRING_UNCHANGE);
		$user_ban = $user_ban > 0 ? (time() + $user_ban) : 0;
		if (!empty($_POST['valid_user']) && !empty($id_get)) //On banni le membre
		{
			$info_mbr = $Sql->query_array(DB_TABLE_MEMBER, 'user_id', 'level', 'user_warning', 'user_mail', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
			//Modérateur ne peux avertir l'admin (logique non?).
			if (!empty($info_mbr['user_id']) && ($info_mbr['level'] < 2 || $User->check_level(ADMIN_LEVEL)))
			{
				$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_ban = '" . $user_ban . "' WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);			
				
				//Si avertissement à 100% et débanni, on réduit l'avertissement à 90%.
				if ($user_ban == 0 && $info_mbr['user_warning'] == 100)
					$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_warning = '90' WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);
				
				if (!empty($user_ban)) //Envoi du mail
				{
					
					$Mail = new Mail();
					$Mail->send_from_properties($info_mbr['user_mail'], addslashes($LANG['ban_title_mail']), sprintf(addslashes($LANG['ban_mail']), HOST, addslashes($CONFIG['sign'])), $CONFIG['mail_exp']);
				}			
			}		
			AppContext::get_response()->redirect(HOST . DIR . url('/member/moderation_panel.php?action=ban', '', '&'));
		}
		
		$moderation_panel_template->assign_vars(array(
			'C_MODO_PANEL_USER' => true,
			'L_ACTION_INFO' => $LANG['ban_management'],
			'L_LOGIN' => $LANG['pseudo'],
			'L_INFO_MANAGEMENT' => $LANG['ban_management'],
			'U_XMLHTTPREQUEST' => 'ban_user',
			'U_ACTION' => '.php?action=ban&amp;token=' . $Session->get_token()
		));
		
		if (empty($id_get)) //On liste les membres qui ont déjà un avertissement
		{
			if (!empty($_POST['search_member']))
			{
				$login = retrieve(POST, 'login_mbr', '');
				$user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '%" . $login . "%'", __LINE__, __FILE__);
				if (!empty($user_id) && !empty($login))
					AppContext::get_response()->redirect(HOST . DIR . url('/member/moderation_panel.php?action=ban&id=' . $user_id, '', '&'));
				else
					AppContext::get_response()->redirect(HOST . DIR . url('/member/moderation_panel.php?action=ban', '', '&'));
			}	
			
			$moderation_panel_template->assign_vars(array(
				'C_MODO_PANEL_USER_LIST' => true,
				'L_PM' => $LANG['user_contact_pm'],
				'L_INFO' => $LANG['user_ban_until'],
				'L_ACTION_USER' => $LANG['ban_management'],
				'L_PROFILE' => $LANG['profile'],
				'L_SEARCH_USER' => $LANG['search_member'],
				'L_SEARCH' => $LANG['search'],
				'L_REQUIRE_LOGIN' => $LANG['require_pseudo']
			));	
				
			$i = 0;
			$result = $Sql->query_while("SELECT user_id, login, user_ban, user_warning
			FROM " . PREFIX . "member
			WHERE user_ban > " . time() . " OR user_warning = 100
			ORDER BY user_ban", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$moderation_panel_template->assign_block_vars('member_list', array(
					'LOGIN' => '<a href="moderation_panel.php?action=ban&amp;id=' . $row['user_id'] . '">' . $row['login'] . '</a>',
					'INFO' => ($row['user_warning'] != 100) ? gmdate_format('date_format', $row['user_ban']) : $LANG['illimited'],
					'U_PROFILE' => '../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
					'U_ACTION_USER' => '<a href="moderation_panel.php?action=ban&amp;id=' . $row['user_id'] . '"><img src="../templates/' . get_utheme() . '/images/admin/forbidden.png" alt="" /></a>',
					'U_PM' => url('.php?pm='. $row['user_id'], '-' . $row['user_id'] . '.php'),
				));
				
				$i++;
			}
			if ($i === 0)
			{
				$moderation_panel_template->assign_vars(array(
					'C_EMPTY_LIST' => true,
					'L_NO_USER' => $LANG['no_ban'],
				));
			}
		}
		else //On affiche les infos sur l'utilisateur
		{
			$mbr = $Sql->query_array(DB_TABLE_MEMBER, 'login', 'user_ban', 'user_warning', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
			$moderation_panel_template->assign_vars(array(
				'C_MODO_PANEL_USER_BAN' => true,
				'KERNEL_EDITOR' => display_editor('action_contents'),
				'LOGIN' => '<a href="../member/member' . url('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $mbr['login'] . '</a>',
				'U_PM' => url('.php?pm='. $id_get, '-' . $id_get . '.php'),
				'U_ACTION_INFO' => '.php?action=ban&amp;id=' . $id_get . '&amp;token=' . $Session->get_token(),
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
			if ($diff > 0)
			{
				//Retourne la sanction la plus proche correspondant au temp de bannissement. 
				for ($i = 11; $i >= 0; $i--)
				{					
					$avg = ceil(($array_time[$i] + $array_time[$i-1])/2);
					if (($diff - $array_time[$i]) > $avg)  
					{	
						$key_sanction = $i + 1;
						break;
					}
				}
			}
			if ($mbr['user_warning'] == 100)
				$key_sanction = 12;
				
			//Affichge des sanctions
			foreach ($array_time as $key => $time)
			{
				$selected = ($key_sanction == $key) ? 'selected="selected"' : '' ;
				$moderation_panel_template->assign_block_vars('select_ban', array(
					'TIME' => '<option value="' . $time . '" ' . $selected . '>' . $array_sanction[$key] . '</option>'
				));
			}	
		}
			case 'warning': //Gestion des utilisateurs
		$new_warning_level = retrieve(POST, 'new_info', 0);
		$warning_contents = retrieve(POST, 'action_contents', '', TSTRING_UNCHANGE);
		if ($new_warning_level >= 0 && $new_warning_level <= 100 && isset($_POST['new_info']) && !empty($id_get) && !empty($_POST['valid_user'])) //On met à  jour le niveau d'avertissement
		{
			$info_mbr = $Sql->query_array(DB_TABLE_MEMBER, 'user_id', 'level', 'user_mail', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
			
			//Modérateur ne peux avertir l'admin (logique non?).
			if (!empty($info_mbr['user_id']) && ($info_mbr['level'] < 2 || $User->check_level(ADMIN_LEVEL)))
			{
				if ($new_warning_level < 100) //Ne peux pas mettre des avertissements supérieurs à 100.
				{
					$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_warning = '" . $new_warning_level . "' WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);
					
					//Envoi d'un MP au membre pour lui signaler, si le membre en question n'est pas lui-même.
					if ($info_mbr['user_id'] != $User->get_attribute('user_id'))
					{					
						if (!empty($warning_contents))
						{					
							
							
							//Envoi du message.
							PrivateMsg::start_conversation($info_mbr['user_id'], addslashes($LANG['warning_title']), $warning_contents, '-1', PrivateMsg::SYSTEM_PM);
						}
					}
				}
				elseif ($new_warning_level == 100) //Ban => on supprime sa session et on le banni (pas besoin d'envoyer de pm :p).
				{
					$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_warning = 100 WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);
					$Sql->query_inject("DELETE FROM " . DB_TABLE_SESSIONS . " WHERE user_id = '" . $info_mbr['user_id'] . "'", __LINE__, __FILE__);
				
					//Envoi du mail
					
					$Mail = new Mail();
					$Mail->send_from_properties($info_mbr['user_mail'], addslashes($LANG['ban_title_mail']), sprintf(addslashes($LANG['ban_mail']), HOST, addslashes($CONFIG['sign'])), $CONFIG['mail_exp']);
				}	
			}
			
			AppContext::get_response()->redirect(HOST . DIR . url('/member/moderation_panel.php?action=warning', '', '&'));
		}
		
		$moderation_panel_template->assign_vars(array(
			'C_MODO_PANEL_USER' => true,
			'L_ACTION_INFO' => $LANG['warning_management'],
			'L_LOGIN' => $LANG['pseudo'],
			'L_INFO_MANAGEMENT' => $LANG['warning_management'],
			'U_XMLHTTPREQUEST' => 'warning_user',		
			'U_ACTION' => '.php?action=warning&amp;token=' . $Session->get_token()
		));
		
		if (empty($id_get)) //On liste les membres qui ont déjà un avertissement
		{
			if (!empty($_POST['search_member']))
			{
				$login = retrieve(POST, 'login_mbr', '');
				$user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '%" . $login . "%'", __LINE__, __FILE__);
				if (!empty($user_id) && !empty($login))
					AppContext::get_response()->redirect(HOST . DIR . url('/member/moderation_panel.php?action=warning&id=' . $user_id, '', '&'));
				else
					AppContext::get_response()->redirect(HOST . DIR . url('/member/moderation_panel.php?action=warning', '', '&'));
			}		
			
			$moderation_panel_template->assign_vars(array(
				'C_MODO_PANEL_USER_LIST' => true,
				'L_PM' => $LANG['user_contact_pm'],
				'L_INFO' => $LANG['user_warning_level'],
				'L_PM' => $LANG['user_contact_pm'],
				'L_ACTION_USER' => $LANG['warning_management'],
				'L_SEARCH_USER' => $LANG['search_member'],
				'L_SEARCH' => $LANG['search'],
				'L_REQUIRE_LOGIN' => $LANG['require_pseudo']
			));
			
			$i = 0;
			$result = $Sql->query_while("SELECT user_id, login, user_warning
			FROM " . PREFIX . "member
			WHERE user_warning > 0
			ORDER BY user_warning", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$moderation_panel_template->assign_block_vars('member_list', array(
					'LOGIN' => $row['login'],
					'INFO' => $row['user_warning'] . '%',
					'U_ACTION_USER' => '<a href="moderation_panel.php?action=warning&amp;id=' . $row['user_id'] . '"><img src="../templates/' . get_utheme() . '/images/admin/important.png" alt="" /></a>',
					'U_PROFILE' => '../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
					'U_PM' => url('.php?pm='. $row['user_id'], '-' . $row['user_id'] . '.php'),
				));
				
				$i++;
			}
			if ($i === 0)
			{
				$moderation_panel_template->assign_vars(array(
					'C_EMPTY_LIST' => true,
					'L_NO_USER' => $LANG['no_user_warning'],
				));
			}
		}
		else //On affiche les infos sur l'utilisateur
		{
			$member = $Sql->query_array(DB_TABLE_MEMBER, 'login', 'user_warning', "WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
						
			//On crée le formulaire select
			$select = '';
			$j = 0;
			for ($j = 0; $j <=10; $j++)
			{
				if (10 * $j == $member['user_warning']) 
					$select .= '<option value="' . 10 * $j . '" selected="selected">' . 10 * $j . '%</option>';
				else
					$select .= '<option value="' . 10 * $j . '">' . 10 * $j . '%</option>';
			}
			$moderation_panel_template->assign_vars(array(
				'C_MODO_PANEL_USER_INFO' => true,
				'KERNEL_EDITOR' => display_editor('action_contents'),
				'ALTERNATIVE_PM' => str_replace('%level%', $member['user_warning'], $LANG['user_warning_level_changed']),
				'LOGIN' => '<a href="../member/member' . url('.php?id=' . $id_get, '-' . $id_get . '.php') . '">' . $member['login'] . '</a>',
				'INFO' => $LANG['user_warning_level'] . ': ' . $member['user_warning'] . '%',
				'SELECT' => $select,
				'REPLACE_VALUE' => 'contents = contents.replace(regex, \' \' + replace_value + \'%\');' . "\n" . 'document.getElementById(\'action_info\').innerHTML = \'' . addslashes($LANG['user_warning_level']) . ': \' + replace_value + \'%\';',
				'REGEX'=> '/ [0-9]+%/',
				'U_ACTION_INFO' => '.php?action=warning&amp;id=' . $id_get . '&amp;token=' . $Session->get_token(),
				'U_PM' => url('.php?pm='. $id_get, '-' . $id_get . '.php'),
				'L_ALTERNATIVE_PM' => $LANG['user_alternative_pm'],
				'L_INFO_EXPLAIN' => $LANG['user_warning_explain'],
				'L_PM' => $LANG['user_contact_pm'],
				'L_INFO' => $LANG['user_warning_level'],
				'L_PM' => $LANG['user_contact_pm'],
				'L_CHANGE_INFO' => $LANG['change_user_warning']
			));			
		}
		break;	
}

$moderation_panel_template->display();

require_once('../kernel/footer.php');

?>