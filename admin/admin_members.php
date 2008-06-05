<?php
/*##################################################
 *                               admin_members.php
 *                            -------------------
 *   begin                : August 01, 2005
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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once(PATH_TO_ROOT . '/kernel/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once(PATH_TO_ROOT . '/kernel/admin_header.php');

$id = !empty($_GET['id']) ? numeric($_GET['id']) : '' ;
$id_post = !empty($_POST['id']) ? numeric($_POST['id']) : '' ;
$delete = !empty($_GET['delete']) ? true : false ;
$add = !empty($_GET['add']) ? true : false;
$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
$get_l_error = !empty($_GET['erroru']) ? trim($_GET['erroru']) : '';

//Si c'est confirmé on execute
if( !empty($_POST['valid']) && !empty($id_post) )
{
	if( !empty($_POST['delete']) ) //Suppression du membre.
	{
		$Sql->Query_inject("DELETE FROM ".PREFIX."member WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);	
	
		//On régénère le cache
		$Cache->Generate_file('stats');
			
		redirect(HOST . SCRIPT);
	}

	$login = !empty($_POST['name']) ?  strprotect(substr($_POST['name'], 0, 25)) : '';
	$user_mail = strtolower($_POST['mail']);
	if( preg_match('`^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-zA-Z]{2,4}$`', $user_mail) )
	{	
		//Vérirication de l'unicité du membre et du mail
		$check_user = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."member WHERE login = '" . $login . "' AND user_id <> '" . $id_post . "'", __LINE__, __FILE__);
		$check_mail = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."member WHERE user_id <> '" . $id_post . "' AND user_mail = '" . $user_mail . "'", __LINE__, __FILE__);
		if( $check_user >= 1 ) 
			redirect(HOST . DIR . '/admin/admin_members' . transid('.php?id=' .  $id_post . '&error=pseudo_auth') . '#errorh');
		elseif( $check_mail >= 1 ) 
			redirect(HOST . DIR . '/admin/admin_members' . transid('.php?id=' .  $id_post . '&error=auth_mail') . '#errorh');
		else
		{
			//Vérification des password.
			$password = !empty($_POST['pass']) ? trim($_POST['pass']) : '';
			$password_md5 = !empty($password) ? md5($password) : '';   
			$password_bis = !empty($_POST['confirm_pass']) ? trim($_POST['confirm_pass']) : '';
			$password_bis_md5 = !empty($password_bis) ? md5($password_bis) : '';
	
			if( !empty($password_md5) && !empty($password_bis_md5) )
			{
				if( $password_md5 === $password_bis_md5 )
				{
					if( strlen($password) >= 6 && strlen($password_bis) >= 6 )
						$Sql->Query_inject("UPDATE ".PREFIX."member SET password = '" . $password_md5 . "' WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__); 
					else //Longueur minimale du password
						redirect(HOST . DIR . '/admin/admin_members' . transid('.php?id=' .  $id_post . '&error=pass_mini') . '#errorh');
				}
				else
					redirect(HOST . DIR . '/admin/admin_members' . transid('.php?id=' .  $id_post . '&error=pass_same') . '#errorh');
			}
			
			$user_level = isset($_POST['level']) ? numeric($_POST['level']) : '-1';  
			$user_mail = !empty($user_mail) ? strprotect($user_mail) : '';	
			$user_aprob = !empty($_POST['user_aprob']) ? numeric($_POST['user_aprob']) : '0';  
			
			//Informations.
			$user_show_mail = !empty($_POST['user_show_mail']) ? '0' : '1';
			$user_lang = !empty($_POST['user_lang']) ? strprotect($_POST['user_lang']) : '';
			$user_theme = !empty($_POST['user_theme']) ? strprotect($_POST['user_theme']) : '';
			$user_editor = !empty($_POST['user_editor']) ? strprotect($_POST['user_editor']) : '';
			$user_timezone = !empty($_POST['user_timezone']) ? numeric($_POST['user_timezone']) : '';
			
			$user_local = !empty($_POST['user_local']) ? strprotect($_POST['user_local']) : '';
			//Validité de l'adresse du site.
			$user_web = !empty($_POST['user_web']) ? strprotect($_POST['user_web']) : '';
			$user_web = (!empty($user_web) && preg_match('`^http(s)?://[a-z0-9._/-]+\.[-[:alnum:]]+\.[a-zA-Z]{2,4}(.*)$`', trim($_POST['user_web']))) ? $user_web : '';
					
			$user_occupation = !empty($_POST['user_occupation']) ? strprotect($_POST['user_occupation']) : '';
			$user_hobbies = !empty($_POST['user_hobbies']) ? strprotect($_POST['user_hobbies']) : '';
			$user_desc = !empty($_POST['user_desc']) ? strparse($_POST['user_desc']) : '';
			$user_sex = !empty($_POST['user_sex']) ? numeric($_POST['user_sex']) : '0';
			$user_sign = !empty($_POST['user_sign']) ? strparse($_POST['user_sign']) : '';			
			$user_msn = !empty($_POST['user_msn']) ? strprotect($_POST['user_msn']) : '';
			$user_yahoo= !empty($_POST['user_yahoo']) ? strprotect($_POST['user_yahoo']) : '';
			
			$user_warning = isset($_POST['user_warning']) ? numeric($_POST['user_warning']) : 0;
			$user_readonly = isset($_POST['user_readonly']) ? numeric($_POST['user_readonly']) : 0;
			$user_readonly = ($user_readonly > 0) ? (time() + $user_readonly) : 0; //Lecture seule!
			$user_ban = isset($_POST['user_ban']) ? numeric($_POST['user_ban']) : 0;
			$user_ban = ($user_ban > 0) ? (time() + $user_ban) : 0; //Bannissement!
			
			//Gestion des groupes.				
			$array_user_groups = isset($_POST['user_groups']) ? $_POST['user_groups'] : array();
			$Group->Edit_member($id_post, $array_user_groups); //Change les groupes du membre, calcul la différence entre les groupes précédent et nouveaux.
			
			//Gestion de la date de naissance.
			$user_born = strtodate($_POST['user_born'], $LANG['date_birth_parse']);
			
			//Gestion de la suppression de l'avatar.
			if( !empty($_POST['delete_avatar']) )
			{
				$user_avatar_path = $Sql->Query("SELECT user_avatar FROM ".PREFIX."member WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
				
				if( !empty($user_avatar_path) )
				{
					$user_avatar_path = str_replace('../images/avatars/', '', $user_avatar_path);
					$user_avatar_path = str_replace('/', '', $user_avatar_path);
					@unlink('../images/avatars/' . $user_avatar_path);
				}
				
				$Sql->Query_inject("UPDATE ".PREFIX."member SET user_avatar = '' WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
			}

			//Gestion upload d'avatar.					
			$user_avatar = '';
			$dir = '../images/avatars/';
			
			include_once(PATH_TO_ROOT . '/kernel/framework/files/upload.class.php');
			$Upload = new Upload($dir);
			
			if( is_writable($dir) )
			{
				if( $_FILES['avatars']['size'] > 0 )
				{
					$Upload->Upload_file('avatars', '`([a-z0-9()_-])+\.(jpg|gif|png|bmp)+`i$', UNIQ_NAME, $CONFIG_MEMBER['weight_max']*1024);
					if( !empty($Upload->error) ) //Erreur, on arrête ici
						redirect(HOST . DIR . '/admin/admin_members' . transid('.php?id=' .  $id_post . '&erroru=' . $Upload->error) . '#errorh');
					else
					{
						$path = $dir . $Upload->filename['avatars'];
						$error = $Upload->Validate_img($path, $CONFIG_MEMBER['width_max'], $CONFIG_MEMBER['height_max'], DELETE_ON_ERROR);
						if( !empty($error) ) //Erreur, on arrête ici
							redirect(HOST . DIR . '/admin/admin_members' . transid('.php?id=' .  $id_post . '&erroru=' . $error) . '#errorh');
						else
						{
							//Suppression de l'ancien avatar (sur le serveur) si il existe!
							$user_avatar_path = $Sql->Query("SELECT user_avatar FROM ".PREFIX."member WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
							if( !empty($user_avatar_path) && preg_match('`\.\./images/avatars/(([a-z0-9()_-])+\.([a-z]){3,4})`i', $user_avatar_path, $match) )
							{
								if( is_file($user_avatar_path) && isset($match[1]) )
									@unlink('../images/avatars/' . $match[1]);
							}						
							$user_avatar = $path; //Avatar uploadé et validé.
						}
					}
				}
			}
			
			if( !empty($_POST['avatar']) )
			{
				$path = strprotect($_POST['avatar']);
				$error = $Upload->Validate_img($path, $CONFIG_MEMBER['width_max'], $CONFIG_MEMBER['height_max'], DELETE_ON_ERROR);
				if( !empty($error) ) //Erreur, on arrête ici
					redirect(HOST . DIR . '/admin/admin_members' . transid('.php?id=' .  $id_post . '&erroru=' . $error) . '#errorh');
				else
					$user_avatar = $path; //Avatar posté et validé.
			}

			$user_avatar = !empty($user_avatar) ? "user_avatar = '" . $user_avatar . "', " : '';
			if( !empty($login) && !empty($user_mail) )
			{	
				//Suppression des images des stats concernant les membres, si l'info à été modifiée.
				$info_mbr = $Sql->Query_array("member", "user_theme", "user_sex", "WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
				if( $info_mbr['user_sex'] != $user_sex )
					@unlink('../cache/sex.png');
				if( $info_mbr['user_theme'] != $user_theme )
					@unlink('../cache/theme.png');
					
				$Sql->Query_inject("UPDATE ".PREFIX."member SET login = '" . $login . "', level = '" . $user_level . "', user_lang = '" . $user_lang . "', user_theme = '" . $user_theme . "', user_mail = '" . $user_mail . "', user_show_mail = '" . $user_show_mail . "', user_editor = '" . $user_editor . "', user_timezone = '" . $user_timezone . "', user_local = '" . $user_local . "', " . $user_avatar . "user_msn = '" . $user_msn . "', user_yahoo = '" . $user_yahoo . "', user_web = '" . $user_web . "', user_occupation = '" . $user_occupation . "', user_hobbies = '" . $user_hobbies . "', user_desc = '" . $user_desc . "', user_sex = '" . $user_sex . "', user_born = '" . $user_born . "', user_sign = '" . $user_sign . "', user_warning = '" . $user_warning . "', user_readonly = '" . $user_readonly . "', user_ban = '" . $user_ban . "', user_aprob = '" . $user_aprob . "' WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
					
				$Sql->Query_inject("UPDATE ".PREFIX."sessions SET level = '" . $user_level . "' WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
				if( $user_ban > 0 )	//Suppression de la session si le membre se fait bannir.
				{	
					$Sql->Query_inject("DELETE FROM ".PREFIX."sessions WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
					include_once(PATH_TO_ROOT . '/kernel/framework/mail.class.php');
					$Mail = new Mail();
					$Mail->Send_mail($user_mail, addslashes($LANG['ban_title_mail']), sprintf(addslashes($LANG['ban_mail']), HOST), $CONFIG['mail']);
				}
				
				//Champs supplémentaires.
				$extend_field_exist = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."member_extend_cat WHERE display = 1", __LINE__, __FILE__);
				if( $extend_field_exist > 0 )
				{
					$req_update = '';
					$req_field = '';
					$req_insert = '';
					$result = $Sql->Query_while("SELECT field_name, field, possible_values
					FROM ".PREFIX."member_extend_cat
					WHERE display = 1", __LINE__, __FILE__);
					while( $row = $Sql->Sql_fetch_assoc($result) )
					{
						$field = isset($_POST[$row['field_name']]) ? trim($_POST[$row['field_name']]) : '';
						if( $row['field'] == 2 )
							$field = strparse($field);
						elseif( $row['field'] == 4 )
						{
							$array_field = is_array($field) ? $field : array();
							$field = '';
							foreach($array_field as $value)
								$field .= addslashes($value) . '|';
						}
						elseif( $row['field'] == 6 )
						{
							$field = '';
							$i = 0;
							$array_possible_values = explode('|', $row['possible_values']);
							foreach($array_possible_values as $value)
							{
								$field .= !empty($_POST[$row['field_name'] . '_' . $i]) ? addslashes($value) . '|' : '';
								$i++;
							}
						}
						else
							$field = strprotect($field);
							
						if( !empty($field) )
						{
							$req_update .= $row['field_name'] . ' = \'' . trim($field, '|') . '\', ';
							$req_field .= $row['field_name'] . ', ';
							$req_insert .= '\'' . trim($field, '|') . '\', ';
						}
					}
					$Sql->Close($result);	
										
					$check_member = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."member_extend WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
					if( $check_member )
					{	
						if( !empty($req_update) )
							$Sql->Query_inject("UPDATE ".PREFIX."member_extend SET " . trim($req_update, ', ') . " WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__); 
					}
					else
					{	
						if( !empty($req_insert) )
							$Sql->Query_inject("INSERT INTO ".PREFIX."member_extend (user_id, " . trim($req_field, ', ') . ") VALUES ('" . $id_post . "', " . trim($req_insert, ', ') . ")", __LINE__, __FILE__);
					}
				}	
				
				redirect(HOST . SCRIPT);	
			}
			else
				redirect(HOST . DIR . '/admin/admin_members' . transid('.php?id=' .  $id_post . '&error=incomplete') . '#errorh');
		}
	}
	else
		redirect(HOST . DIR . '/admin/admin_members' . transid('.php?id=' .  $id_post . '&error=incomplete') . '#errorh');
}
elseif( $add && !empty($_POST['add']) ) //Ajout du membre.
{
	$login = !empty($_POST['login2']) ? strprotect(substr($_POST['login2'], 0, 25)) : '';
	$password = !empty($_POST['password2']) ? trim($_POST['password2']) : '';
	$password_bis = !empty($_POST['password2_bis']) ? trim($_POST['password2_bis']) : '';
	$password_md5 = !empty($password) ? md5($password) : '';
	$level = isset($_POST['level2']) ? numeric($_POST['level2']) : '-1';
	$mail = !empty($_POST['mail2']) ? strprotect($_POST['mail2']) : '';
	
	if( preg_match("!^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$!", strtolower($mail)) )
	{	
		//Vérirication de l'unicité du membre et du mail
		$check_user = $Sql->Query("SELECT COUNT(*) as compt FROM ".PREFIX."member WHERE login = '" . $login . "'", __LINE__, __FILE__);
		$check_mail = $Sql->Query("SELECT COUNT(*) as compt FROM ".PREFIX."member WHERE user_mail = '" . $mail . "'", __LINE__, __FILE__);
		if( $check_user >= 1 ) 
			redirect(HOST . DIR . '/admin/admin_members' . transid('.php?error=pseudo_auth&add=1') . '#errorh');
		elseif( $check_mail >= 1 ) 
			redirect(HOST . DIR . '/admin/admin_members' . transid('.php?error=auth_mail&add=1') . '#errorh');
		else
		{
			if( strlen($password) >= 6 && strlen($password_bis) >= 6 )
			{
				if( !empty($login) )
				{	
					//On insere le nouveau membre.
					$Sql->Query_inject("INSERT INTO ".PREFIX."member (login,password,level,user_groups,user_lang,user_theme,user_mail,user_show_mail,timestamp,user_avatar,user_msg,user_local,user_msn,user_yahoo,user_web,user_occupation,user_hobbies,user_desc,user_sex,user_born,user_sign,user_pm,user_warning,user_readonly,last_connect,test_connect,activ_pass,new_pass,user_ban,user_aprob) 
					VALUES('" . $login . "', '" . $password_md5 . "', '" . $level . "', '0', '" . $CONFIG['lang'] . "', '', '" . $mail . "', '1', '" . time() . "', '', '0', '', '', '', '', '', '', '', '', '', '', '0', '0', '0', '0', '0', '', '', '0', '1')", __LINE__, __FILE__);
					
					//On régénère le cache
					$Cache->Generate_file('stats');
						
					redirect(HOST . SCRIPT); 	
				}
				else
					redirect(HOST . DIR . '/member/member' . transid('.php?error=incomplete&add=1') . '#errorh');
			}
			else //Longueur minimale du password
				redirect(HOST . DIR . '/admin/admin_members' . transid('.php?id=' .  $id_get . '&error=pass_mini&add=1') . '#errorh');
		}
	}
	else
		redirect(HOST . DIR . '/admin/admin_members' . transid('.php?error=invalid_mail&add=1') . '#errorh');
}
elseif( !empty($id) && $delete ) //Suppression du membre.
{
	//On supprime dans la bdd.
	$Sql->Query_inject("DELETE FROM ".PREFIX."member WHERE user_id = '" . $id . "'", __LINE__, __FILE__);	
	
	//On régénère le cache
	$Cache->Generate_file('stats');
		
	redirect(HOST . SCRIPT);
}
elseif( $add )
{
	$Template->Set_filenames(array(
		'admin_members_management2'=> 'admin/admin_members_management2.tpl'
	));

	//Gestion des erreurs.
	switch($get_error)
	{
		case 'pass_mini':
		$errstr = $LANG['e_pass_mini'];
		break;
		case 'incomplete':
		$errstr = $LANG['e_incomplete'];
		break;
		case 'invalid_mail':
		$errstr = $LANG['e_mail_invalid'];
		break;		
		case 'pseudo_auth':
		$errstr = $LANG['e_pseudo_auth'];
		break;
		case 'auth_mail':
		$errstr = $LANG['e_mail_auth'];
		break;
		default:
		$errstr = '';
	}
	if( !empty($errstr) )
		$Errorh->Error_handler($errstr, E_USER_NOTICE);  
		
	$Template->Assign_vars(array(
		'C_MEMBERS_ADD' => true,
		'L_MEMBERS_MANAGEMENT' => $LANG['members_management'],
		'L_MEMBERS_ADD' => $LANG['members_add'],
		'L_MEMBERS_CONFIG' => $LANG['members_config'],
		'L_MEMBERS_PUNISHMENT' => $LANG['punishment_management'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_PASSWORD' => $LANG['password'],
		'L_PASSWORD_CONFIRM' => $LANG['confirm_pass'],
		'L_MAIL' => $LANG['mail'],
		'L_RANK' => $LANG['rank'],
		'L_MEMBER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_RESET' => $LANG['reset'],
		'L_ADD' => $LANG['add']
	));
	
	$Template->Pparse('admin_members_management2'); 	
}
elseif( !empty($id) )	
{		
	$Template->Set_filenames(array(
		'admin_members_management2'=> 'admin/admin_members_management2.tpl'
	));
	
	$mbr = $Sql->Query_array('member', '*', "WHERE user_id = '" . $id . "'", __LINE__, __FILE__);

	$user_born = '';
	$array_user_born = explode('-', $mbr['user_born']);
	$date_birth = explode('/', $LANG['date_birth_parse']);
	for($i = 0; $i < 3; $i++)
	{
		if( $date_birth[$i] == 'DD' )
		{	
			$user_born .= $array_user_born[2 - $i];
			$born_day = $array_user_born[2 - $i];
		}
		elseif( $date_birth[$i] == 'MM' )
		{	
			$user_born .= $array_user_born[2 - $i];
			$born_month = $array_user_born[2 - $i];
		}
		elseif( $date_birth[$i] == 'YYYY' )	
		{
			$user_born .= $array_user_born[2 - $i];				
			$born_year = $array_user_born[2 - $i];
		}
		$user_born .= ($i != 2) ? '/' : '';	
	}
	
	//Gestion des erreurs.
	switch($get_error)
	{
		case 'pass_mini':
		$errstr = $LANG['e_pass_mini'];
		break;
		case 'pass_same':
		$errstr = $LANG['e_pass_same'];
		break;
		case 'incomplete':
		$errstr = $LANG['e_incomplete'];
		break;
		case 'invalid_mail':
		$errstr = $LANG['e_mail_invalid'];
		break;		
		case 'pseudo_auth':
		$errstr = $LANG['e_pseudo_auth'];
		break;
		case 'auth_mail':
		$errstr = $LANG['e_mail_auth'];
		break;
		default:
		$errstr = '';
	}
	if( !empty($errstr) )
		$Errorh->Error_handler($errstr, E_USER_NOTICE);  

	if( isset($LANG[$get_l_error]) )
		$Errorh->Error_handler($errstr, E_USER_WARNING);   

	$user_sex = '';
	if( !empty($mbr['user_sex']) )
		$user_sex = ($mbr['user_sex'] == 1) ? '/images/man.png' : '/images/woman.png';
	
	//Rang d'autorisation.
	$array_ranks = array(0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	$ranks_options = '';
	for( $i = 0 ; $i <= 2 ; $i++ )
	{
		$selected = ($mbr['level'] == $i) ? 'selected="selected"' : '' ;
		$ranks_options .= '<option value="' . $i . '" ' . $selected . '>' . $array_ranks[$i] . '</option>';
	}
	
	//Groupes.	
	$i = 0;
	$groups_options = '';
	$result = $Sql->Query_while("SELECT id, name
	FROM ".PREFIX."group", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{		
		$selected = '';		
		$search_group = array_search($row['id'], explode('|', $mbr['user_groups']));		
		if( is_numeric($search_group) )
			$selected = 'selected="selected"';	
			
		$groups_options .= '<option value="' . $row['id'] . '" id="g' . $i . '" ' . $selected . '>' . $row['name'] . '</option>';
		$i++;
	}
	$Sql->Close($result);

	//Temps de bannissement.
	$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 326592000);
	$array_sanction = array($LANG['no'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '1 ' . $LANG['day'], '2 ' . $LANG['days'], '1 ' . $LANG['week'], '2 ' . $LANG['weeks'], '1 ' . $LANG['month'], $LANG['life']); 
	$diff = ($mbr['user_ban'] - time());	
	$key_sanction = 0;
	if( $diff > 0 )
	{
		//Retourne la sanction la plus proche correspondant au temp de bannissement. 
		for($i = 12; $i > 0; $i--)
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
	$ban_options = '';
	foreach( $array_time as $key => $time)
	{
		$selected = ( $key_sanction == $key ) ? 'selected="selected"' : '' ;		
		$ban_options .= '<option value="' . $time . '" ' . $selected . '>' . $array_sanction[$key] . '</option>';
	}

	//Durée de la sanction.
	$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 326592000); 	
	$array_sanction = array($LANG['no'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '1 ' . $LANG['day'], '2 ' . $LANG['days'], '1 ' . $LANG['week'], '2 ' . $LANG['weeks'], '1 ' . $LANG['month'], $LANG['life']); 
	$diff = ($mbr['user_readonly'] - time());	
	$key_sanction = 0;
	if( $diff > 0 )
	{
		//Retourne la sanction la plus proche correspondant au temp de bannissement. 
		for($i = 12; $i > 0; $i--)
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
	$readonly_options = '';
	foreach($array_time as $key => $time)
	{
		$selected = ($key_sanction == $key) ? ' selected="selected"' : '' ;
		$readonly_options .= '<option value="' . $time . '"' . $selected . '>' . $array_sanction[$key] . '</option>';
	}
		
	//On crée le formulaire select
	$warning_options = '';
	$j = 0;
	for($j = 0; $j <=10; $j++)
	{
		$selected = ((10 * $j) == $mbr['user_warning']) ? ' selected="selected"' : '';
		$warning_options .= '<option value="' . 10 * $j . '"' . $selected . '>' . 10 * $j . '%</option>';
	}
	
	//Gestion LANG par défaut.
	$lang_options = '';
	$array_identifier = '';
	$lang_identifier = '../images/stats/other.png';
	$result = $Sql->Query_while("SELECT lang 
	FROM ".PREFIX."lang", __LINE__, __FILE__);
	while( $row2 = $Sql->Sql_fetch_assoc($result) )
	{	
		$lang_info = load_ini_file('../lang/', $row2['lang']);
		if( $lang_info )
		{
			$lang_name = !empty($lang_info['name']) ? $lang_info['name'] : $row2['lang'];
			$array_identifier .= 'array_identifier[\'' . $row2['lang'] . '\'] = \'' . $lang_info['identifier'] . '\';' . "\n";
			$selected = '';
			if( $row2['lang'] == $mbr['user_lang'] )
			{
				$selected = 'selected="selected"';
				$lang_identifier = '../images/stats/countries/' . $lang_info['identifier'] . '.png';
			}			
			$lang_options .= '<option value="' . $row2['lang'] . '" ' . $selected . '>' . $lang_name . '</option>';
		}
	}
	$Sql->Close($result);
	
	//Gestion thème par défaut.
	$theme_options = '';
	$result = $Sql->Query_while("SELECT theme 
	FROM ".PREFIX."themes", __LINE__, __FILE__);
	while( $row2 = $Sql->Sql_fetch_assoc($result) )
	{	
		$theme_info = load_ini_file('../templates/' . $row2['theme'] . '/config/', $CONFIG['lang']);
		if( $theme_info )
		{
			$theme_name = !empty($theme_info['name']) ? $theme_info['name'] : $row2['theme'];
			$selected = ($row2['theme'] == $mbr['user_theme']) ? 'selected="selected"' : '';
			$theme_options .= '<option value="' . $row2['theme'] . '" ' . $selected . '>' . $theme_name . '</option>';
		}
	}
	$Sql->Close($result);
	
	//Editeur texte par défaut.
	$editors = array('bbcode' => 'BBCode', 'tinymce' => 'Tinymce');
	$editor_options = '';
	foreach($editors as $code => $name)
	{
		$selected = ($code == $mbr['user_editor']) ? 'selected="selected"' : '';
		$editor_options .= '<option value="' . $code . '" ' . $selected . '>' . $name . '</option>';
	}
	
	//Gestion fuseau horaire par défaut.
	$timezone_options = '';
	for($i = -12; $i <= 14; $i++)
	{
		$selected = ($i == $mbr['user_timezone']) ? 'selected="selected"' : '';
		$name = (!empty($i) ? ($i > 0 ? ' + ' . $i : ' - ' . -$i) : '');
		$timezone_options .= '<option value="' . $i . '" ' . $selected . '> [GMT' . $name . ']</option>';
	}
		
	//Sex par défaut
	$i = 0;
	$array_sex = array('--', $LANG['male'], $LANG['female'], );
	$sex_options = '';
	foreach($array_sex as $value_sex)
	{		
		$selected = ($i == $mbr['user_sex']) ? 'selected="selected"' : '';
		$sex_options .= '<option value="' . $i . '" ' . $selected . '>' . $value_sex . '</option>';
		$i++;
	}
	
	//On assigne les variables pour le POST en précisant l'user_id.
	$Template->Assign_vars(array(
		'C_MEMBERS_MANAGEMENT' => true,
		'JS_LANG_IDENTIFIER' => $array_identifier,
		'IMG_LANG_IDENTIFIER' => $lang_identifier,
		'IDMBR' => $mbr['user_id'],
		'NAME' => $mbr['login'],
		'MAIL' => $mbr['user_mail'],
		'USER_THEME' => $mbr['user_theme'],
		'SELECT_UNAPROB' => ($mbr['user_aprob'] == 0) ? 'selected="selected"' : '',
		'SELECT_APROB' => ($mbr['user_aprob'] == 1) ? 'selected="selected"' : '',
		'RANKS_OPTIONS' => $ranks_options,
		'GROUPS_OPTIONS' => $groups_options,
		'LANG_OPTIONS' => $lang_options,
		'THEME_OPTIONS' => $theme_options,
		'EDITOR_OPTIONS' => $editor_options,
		'TIMEZONE_OPTIONS' => $timezone_options,
		'BAN_OPTIONS' => $ban_options,
		'READONLY_OPTIONS' => $readonly_options,
		'WARNING_OPTIONS' => $warning_options,
		'SEX_OPTIONS' => $sex_options,
		'MSN' => $mbr['user_msn'],
		'YAHOO' => $mbr['user_yahoo'],
		'LOCAL' => $mbr['user_local'],
		'WEB' => $mbr['user_web'],
		'IMG_SEX' => !empty($user_sex) ? '<img src="' . $user_sex . '" alt="" />' : '',
		'BORN' => $user_born,
		'BORN_DAY' => $born_day,
		'BORN_MONTH' => $born_month,
		'BORN_YEAR' => $born_year,
		'OCCUPATION' => $mbr['user_occupation'],
		'HOBBIES' => $mbr['user_hobbies'],
		'SIGN' => unparse($mbr['user_sign'], NO_EDITOR_UNPARSE),
		'BIOGRAPHY' => unparse($mbr['user_desc'], NO_EDITOR_UNPARSE),
		'USER_AVATAR' => !empty($mbr['user_avatar']) ? '<img src="' . $mbr['user_avatar'] . '" alt="" />' : '<em>' . $LANG['no_avatar'] . '</em>',
		'AVATAR_LINK' => $mbr['user_avatar'],
		'SHOW_MAIL_CHECKED' => ($mbr['user_show_mail'] == 0) ? 'checked="checked"' : '',
		'THEME' => $CONFIG['theme'],
		'WEIGHT_MAX' => $CONFIG_MEMBER['weight_max'],
		'HEIGHT_MAX' => $CONFIG_MEMBER['height_max'],
		'WIDTH_MAX' => $CONFIG_MEMBER['width_max'],
		'L_REQUIRE_MAIL' => $LANG['require_mail'],
		'L_REQUIRE_RANK' => $LANG['require_rank'],
		'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
		'L_REQUIRE' => $LANG['require'],
		'L_CONFIRM_DEL_MEMBER' => $LANG['confirm_del_member'],
		'L_MEMBERS_MANAGEMENT' => $LANG['members_management'],
		'L_MEMBERS_ADD' => $LANG['members_add'],
		'L_MEMBERS_CONFIG' => $LANG['members_config'],
		'L_MEMBERS_PUNISHMENT' => $LANG['punishment_management'],
		'L_UPDATE' => $LANG['update'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_PASSWORD' => $LANG['password'],
		'L_CONFIRM_PASSWORD' => $LANG['confirm_password'],
		'L_CONFIRM_PASSWORD_EXPLAIN' => $LANG['confirm_password_explain'],
		'L_MAIL' => $LANG['mail'],
		'L_HIDE_MAIL' => $LANG['hide_mail'],
		'L_HIDE_MAIL_EXPLAIN' => $LANG['hide_mail_explain'],
		'L_APROB' => $LANG['aprob'],
		'L_RANK' => $LANG['rank'],
		'L_NO' => $LANG['no'],
		'L_YES' => $LANG['yes'],
		'L_GROUP' => $LANG['group'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],		
		'L_SANCTION' => $LANG['sanction'],		
		'L_BAN' => $LANG['ban'],
		'L_READONLY' => $LANG['readonly_user'],
		'L_WARNING' => $LANG['warning_user'],
		'L_LANG_CHOOSE'  => $LANG['choose_lang'],
		'L_OPTIONS' => $LANG['options'],
		'L_THEME_CHOOSE' => $LANG['choose_theme'],
		'L_EDITOR_CHOOSE' => $LANG['choose_editor'],
		'L_TIMEZONE_CHOOSE' => $LANG['timezone_choose'],
		'L_TIMEZONE_CHOOSE_EXPLAIN' => $LANG['timezone_choose_explain'],
		'L_INFO' => $LANG['info'],
		'L_WEBSITE' => $LANG['website'],
		'L_WEBSITE_EXPLAIN' => $LANG['website_explain'],
		'L_LOCALISATION' => $LANG['localisation'],
		'L_JOB' => $LANG['job'],
		'L_HOBBIES' => $LANG['hobbies'],
		'L_MEMBER_SIGN' => $LANG['member_sign'],
		'L_MEMBER_SIGN_EXPLAIN' => $LANG['member_sign_explain'],
		'L_MEMBER_BIOGRAPHY' => $LANG['biography'],
		'L_SEX' => $LANG['sex'],
		'L_DATE_BIRTH' => $LANG['date_of_birth'],
		'L_VALID' => $LANG['valid'],
		'L_CONTACT' => $LANG['contact'],
		'L_AVATAR_GESTION' => $LANG['avatar_management'],
		'L_CURRENT_AVATAR' => $LANG['current_avatar'],
		'L_WEIGHT_MAX' => $LANG['weight_max'],
		'L_HEIGHT_MAX' => $LANG['height_max'],
		'L_WIDTH_MAX' => $LANG['width_max'],
		'L_UPLOAD_AVATAR' => $LANG['upload_avatar'],
		'L_UPLOAD_AVATAR_WHERE' => $LANG['upload_avatar_where'],
		'L_AVATAR_LINK' => $LANG['avatar_link'],
		'L_AVATAR_LINK_WHERE' => $LANG['avatar_link_where'],
		'L_AVATAR_DEL' => $LANG['avatar_del'],		
		'L_MEMBER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_WEBSITE' => $LANG['website'],
		'L_REGISTERED' => $LANG['registered'],
		'L_DELETE' => $LANG['delete'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));

	//Champs supplémentaires.
	$extend_field_exist = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."member_extend_cat WHERE display = 1", __LINE__, __FILE__);
	if( $extend_field_exist > 0 )
	{
		$Template->Assign_vars(array(			
			'C_MISCELLANEOUS' => true,
			'L_MISCELLANEOUS' => $LANG['miscellaneous']
		));

		$result = $Sql->Query_while("SELECT exc.name, exc.contents, exc.field, exc.require, exc.field_name, exc.possible_values, exc.default_values, ex.*
		FROM ".PREFIX."member_extend_cat exc
		LEFT JOIN ".PREFIX."member_extend ex ON ex.user_id = '" . $id . "'
		WHERE exc.display = 1
		ORDER BY exc.class", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{	
			// field: 0 => base de données, 1 => text, 2 => textarea, 3 => select, 4 => select multiple, 5=> radio, 6 => checkbox
			$field = '';
			$row[$row['field_name']] = !empty($row[$row['field_name']]) ? $row[$row['field_name']] : $row['default_values'];
			switch($row['field'])
			{
				case 1:
				$field = '<label><input type="text" size="30" name="' . $row['field_name'] . '" id="' . $row['field_name'] . '" class="text" value="' . $row[$row['field_name']] . '" /></label>';
				break;
				case 2:
				$field = '<label><textarea class="post" rows="4" cols="27" name="' . $row['field_name'] . '" id="' . $row['field_name'] . '">' . unparse($row[$row['field_name']]) . '</textarea></label>';
				break;
				case 3:
				$field = '<label><select name="' . $row['field_name'] . '" id="' . $row['field_name'] . '">';
				$array_values = explode('|', $row['possible_values']);
				$i = 0;
				foreach($array_values as $values)
				{
					$selected = ($values == $row[$row['field_name']]) ? 'selected="selected"' : '';
					$field .= '<option name="' . $row['field_name'] . '_' . $i . '" value="' . $values . '" ' . $selected . '/> ' . ucfirst($values) . '</option>';
					$i++;
				}
				$field .= '</select></label>';
				break;
				case 4:
				$field = '<label><select name="' . $row['field_name'] . '[]" multiple="multiple" id="' . $row['field_name'] . '">';
				$array_values = explode('|', $row['possible_values']);
				$array_default_values = explode('|', $row[$row['field_name']]);
				$i = 0;
				foreach($array_values as $values)
				{
					$selected = in_array($values, $array_default_values) ? 'selected="selected"' : '';
					$field .= '<option name="' . $row['field_name'] . '_' . $i . '" value="' . $values . '" ' . $selected . '/> ' . ucfirst($values) . '</option>';
					$i++;
				}
				$field .= '</select></label>';
				break;
				case 5:
				$array_values = explode('|', $row['possible_values']);
				foreach($array_values as $values)
				{
					$checked = ($values == $row[$row['field_name']]) ? 'checked="checked"' : '';
					$field .= '<label><input type="radio" name="' . $row['field_name'] . '" id="' . $row['field_name'] . '" value="' . $values . '" ' . $checked . '/> ' . ucfirst($values) . '</label><br />';
				}
				break;
				case 6:
				$array_values = explode('|', $row['possible_values']);
				$array_default_values = explode('|', $row[$row['field_name']]);
				$i = 0;
				foreach($array_values as $values)
				{
					$checked = in_array($values, $array_default_values) ? 'checked="checked"' : '';
					$field .= '<label><input type="checkbox" name="' . $row['field_name'] . '_' . $i . '" value="' . $values . '" ' . $checked . '/> ' . ucfirst($values) . '</label><br />';
					$i++;
				}
				break;
			}				
			
			$Template->Assign_block_vars('list', array(
				'NAME' => $row['require'] ? '* ' . ucfirst($row['name']) : ucfirst($row['name']),
				'ID' => $row['field_name'],
				'DESC' => !empty($row['contents']) ? ucfirst($row['contents']) : '',
				'FIELD' => $field
			));
		}
		$Sql->Close($result);	
	}
	
	$Template->Pparse('admin_members_management2');
}
else
{			
	$Template->Set_filenames(array(
		'admin_members_management'=> 'admin/admin_members_management.tpl'
	));
	 
	$search = ( !empty($_POST['login_mbr'])) ? strprotect($_POST['login_mbr']) : '' ; 
	if( !empty($search) ) //Moteur de recherche des members
	{
		$search = str_replace('*', '%', $search);
		$req = "SELECT user_id, login FROM ".PREFIX."member WHERE login LIKE '".$search."%'";
		$nbr_result = $Sql->Query("SELECT COUNT(*) as compt FROM ".PREFIX."member WHERE login LIKE '%".$search."%'", __LINE__, __FILE__);

		if( !empty($nbr_result) )
		{			
			$i = 0;
			$result = $Sql->Query_while($req, __LINE__, __FILE__);
			while ($row = $Sql->Sql_fetch_assoc($result)) //On execute la requête dans une boucle pour afficher tout les résultats.
			{ 
				$coma = ($i != 0) ? ', ' : '';
				$i++;
				$Template->Assign_block_vars('search', array(
					'RESULT' => $coma . '<a href="../admin/admin_members.php?id=' . $row['user_id'] . '">' . $row['login'] . '</a>'
				));
			}
			$Sql->Close($result);
		}
		else
		{
			$Template->Assign_block_vars('search', array(
				'RESULT' => $LANG['no_result']
			));
		}		
	}

	$nbr_membre = $Sql->Count_table("member", __LINE__, __FILE__);
	//On crée une pagination si le nombre de membre est trop important.
	include_once(PATH_TO_ROOT . '/kernel/framework/pagination.class.php'); 
	$Pagination = new Pagination();
	 
	$get_sort = !empty($_GET['sort']) ? trim($_GET['sort']) : '';	
	switch($get_sort)
	{
		case 'time' : 
		$sort = 'timestamp';
		break;		
		break;		
		case 'alph' : 
		$sort = 'login';
		break;	
		case 'rank' : 
		$sort = 'level';
		break;	
		case 'aprob' : 
		$sort = 'user_aprob';
		break;	
		default :
		$sort = 'timestamp';
	}
	
	$get_mode = !empty($_GET['mode']) ? trim($_GET['mode']) : '';	
	$mode = ($get_mode == 'asc' || $get_mode == 'desc') ? strtoupper(trim($_GET['mode'])) : '';	
	$unget = (!empty($get_sort) && !empty($mode)) ? '&amp;sort=' . $get_sort . '&amp;mode=' . $get_mode : '';

	$Template->Assign_vars(array(
		'PAGINATION' => $Pagination->Display_pagination('admin_members.php?p=%d' . $unget, $nbr_membre, 'p', 25, 3),	
		'THEME' => $CONFIG['theme'],
		'LANG' => $CONFIG['lang'],
		'L_REQUIRE_MAIL' => $LANG['require_mail'],
		'L_REQUIRE_PASS' => $LANG['require_pass'],
		'L_REQUIRE_RANK' => $LANG['require_rank'],
		'L_REQUIRE_LOGIN' => $LANG['require_pseudo'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_CONFIRM_DEL_MEMBER' => $LANG['confirm_del_member'],
		'L_CONTENTS' => $LANG['contents'],
		'L_SUBMIT' => $LANG['submit'],
		'L_UPDATE' => $LANG['update'],
		'L_MEMBERS_MANAGEMENT' => $LANG['members_management'],
		'L_MEMBERS_ADD' => $LANG['members_add'],
		'L_MEMBERS_CONFIG' => $LANG['members_config'],
		'L_MEMBERS_PUNISHMENT' => $LANG['members_punishment'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_PASSWORD' => $LANG['password'],
		'L_MAIL' => $LANG['mail'],
		'L_RANK' => $LANG['rank'],
		'L_APROB' => $LANG['aprob'],
		'L_MEMBER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_SEARCH_MEMBER' => $LANG['search_member'],
		'L_JOKER' => $LANG['joker'],
		'L_SEARCH' => $LANG['search'],
		'L_WEBSITE' => $LANG['website'],
		'L_REGISTERED' => $LANG['registered'],
		'L_DELETE' => $LANG['delete']
	));
		
	$result = $Sql->Query_while("SELECT login, user_id, user_mail, timestamp, user_web, level, user_aprob
	FROM ".PREFIX."member 
	ORDER BY " . $sort . " " . $mode . 
	$Sql->Sql_limit($Pagination->First_msg(25, 'p'), 25), __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		switch ($row['level']) 
		{	
			case 0:
				$rank = $LANG['member'];
			break;
			
			case 1: 
				$rank = $LANG['modo'];
			break;
	
			case 2:
				$rank = $LANG['admin'];
			break;	
			
			default: 0;
		} 
		
		$user_web = !empty($row['user_web']) ? '<a href="' . $row['user_web'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/user_web.png" alt="' . $row['user_web'] . '" title="' . $row['user_web'] . '" /></a>' : '';
		
		$Template->Assign_block_vars('member', array(
			'IDMBR' => $row['user_id'],
			'NAME' => $row['login'],
			'RANK' => $rank,
			'MAIL' => $row['user_mail'],
			'WEB' => $user_web,
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'APROB' => ($row['user_aprob'] == 0) ? $LANG['no'] : $LANG['yes']		
		));
	}
	$Sql->Close($result);
	
	include_once(PATH_TO_ROOT . '/kernel/framework/content/bbcode.php');
	
	$Template->Pparse('admin_members_management'); 
}
require_once(PATH_TO_ROOT . '/kernel/admin_footer.php');

?>