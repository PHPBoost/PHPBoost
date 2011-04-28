<?php
/*##################################################
 *                             admin_members.php
 *                            -------------------
 *   begin                : August 01, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$id = retrieve(GET, 'id', 0);
$id_post = retrieve(POST, 'id', 0);
$delete = !empty($_GET['delete']) ? true : false ;
$add = !empty($_GET['add']) ? true : false;
$get_error = retrieve(GET, 'error', '');
$get_l_error = retrieve(GET, 'erroru', '');

//Si c'est confirmé on execute
if (!empty($_POST['valid']) && !empty($id_post))
{
	if (!empty($_POST['delete'])) //Suppression du membre.
	{
		$Sql->query_inject("DELETE FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);	
		
		
		Uploads::Empty_folder_member($id_post); //Suppression de tout les fichiers et dossiers du membre.
			
		//On régénère le cache
		StatsCache::invalidate();
			
		AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
	}

	$login = !empty($_POST['name']) ?  TextHelper::strprotect(substr($_POST['name'], 0, 25)) : '';
	$user_mail = strtolower($_POST['mail']);
	if (check_mail($user_mail))
	{	
		//Vérirication de l'unicité du membre et du mail
		$check_user = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER . " WHERE login = '" . $login . "' AND user_id <> '" . $id_post . "'", __LINE__, __FILE__);
		$check_mail = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER . " WHERE user_id <> '" . $id_post . "' AND user_mail = '" . $user_mail . "'", __LINE__, __FILE__);
		if ($check_user >= 1) 
			AppContext::get_response()->redirect('/admin/admin_members' . url('.php?id=' .  $id_post . '&error=pseudo_auth') . '#message_helper');
		elseif ($check_mail >= 1) 
			AppContext::get_response()->redirect('/admin/admin_members' . url('.php?id=' .  $id_post . '&error=auth_mail') . '#message_helper');
		else
		{
			//Vérification des password.
			$password = retrieve(POST, 'pass', '', TSTRING_UNCHANGE);
			$password_hash = !empty($password) ? strhash($password) : '';
			$password_bis = retrieve(POST, 'confirm_pass', '', TSTRING_UNCHANGE);
			$password_bis_hash = !empty($password_bis) ? strhash($password_bis) : '';
            
			if (!empty($password_hash) && !empty($password_bis_hash))
			{
				if ($password_hash === $password_bis_hash)
				{
					if (strlen($password) >= 6)
                    {
						$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET password = '" . $password_hash . "' WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
                    }
					else //Longueur minimale du password
						AppContext::get_response()->redirect('/admin/admin_members' . url('.php?id=' .  $id_post . '&error=pass_mini') . '#message_helper');
				}
				else
					AppContext::get_response()->redirect('/admin/admin_members' . url('.php?id=' .  $id_post . '&error=pass_same') . '#message_helper');
			}
			
			$MEMBER_LEVEL = retrieve(POST, 'level', -1);  
			$user_aprob = retrieve(POST, 'user_aprob', 0);  
			
			//Informations.
			$user_show_mail = !empty($_POST['user_show_mail']) ? 0 : 1;
			$user_lang = retrieve(POST, 'user_lang', '');
			$user_theme = retrieve(POST, 'user_theme', '');
			$user_editor = retrieve(POST, 'user_editor', '');
			$user_timezone = retrieve(POST, 'user_timezone', 0);
			
			$user_local = retrieve(POST, 'user_local', '');
			$user_occupation = retrieve(POST, 'user_occupation', '');
			$user_hobbies = retrieve(POST, 'user_hobbies', '');
			$user_desc = retrieve(POST, 'user_desc', '', TSTRING_PARSE);
			$user_sex = retrieve(POST, 'user_sex', 0);
			$user_sign = retrieve(POST, 'user_sign', '', TSTRING_PARSE);			
			$user_msn = retrieve(POST, 'user_msn', '');
			$user_yahoo= retrieve(POST, 'user_yahoo', '');
			
			$user_warning = retrieve(POST, 'user_warning', 0);
			$user_readonly = retrieve(POST, 'user_readonly', 0);
			$user_readonly = ($user_readonly > 0) ? (time() + $user_readonly) : 0; //Lecture seule!
			$user_ban = retrieve(POST, 'user_ban', 0);
			$user_ban = ($user_ban > 0) ? (time() + $user_ban) : 0; //Bannissement!
			
			$user_web = retrieve(POST, 'user_web', '');
			if (!empty($user_web) && substr($user_web, 0, 7) != 'http://' && substr($user_web, 0, 6) != 'ftp://' && substr($user_web, 0, 8) != 'https://')
				$user_web = 'http://' . $user_web;
			
			//Gestion des groupes.				
			$array_user_groups = isset($_POST['user_groups']) ? $_POST['user_groups'] : array();
			GroupsService::edit_member($id_post, $array_user_groups); //Change les groupes du membre, calcul la différence entre les groupes précédent et nouveaux.
			
			//Gestion de la date de naissance.
			$user_born = strtodate($_POST['user_born'], $LANG['date_birth_parse']);
			
			//Gestion de la suppression de l'avatar.
			if (!empty($_POST['delete_avatar']))
			{
				$user_avatar_path = $Sql->query("SELECT user_avatar FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
				
				if (!empty($user_avatar_path))
				{
					$user_avatar_path = str_replace(PATH_TO_ROOT .'/images/avatars/', '', $user_avatar_path);
					$user_avatar_path = str_replace('/', '', $user_avatar_path);
					@unlink(PATH_TO_ROOT .'/images/avatars/' . $user_avatar_path);
				}
				
				$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_avatar = '' WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
			}
			
			$user_accounts_config = UserAccountsConfig::load();

			//Gestion upload d'avatar.					
			$user_avatar = '';
			$dir = PATH_TO_ROOT .'/images/avatars/';
			
			$Upload = new Upload($dir);
			$Upload->disableContentCheck();
			$Upload->file('avatars', '`([a-z0-9()_-])+\.(jpg|gif|png|bmp)+$`i', Upload::UNIQ_NAME, $user_accounts_config->get_max_avatar_weight() * 1024);
			if ($Upload->get_size() > 0)
			{
				if ($Upload->get_error() != '') //Erreur, on arrête ici
					AppContext::get_response()->redirect('/admin/admin_members' . url('.php?id=' .  $id_post . '&erroru=' . $Upload->get_error()) . '#message_helper');
				else
				{
					$path = $dir . $Upload->get_filename();
					$error = $Upload->check_img($user_accounts_config->get_max_avatar_width(), $user_accounts_config->get_max_avatar_height(), Upload::DELETE_ON_ERROR);
					if (!empty($error)) //Erreur, on arrête ici
						AppContext::get_response()->redirect('/admin/admin_members' . url('.php?id=' .  $id_post . '&erroru=' . $error) . '#message_helper');
					else
					{
						//Suppression de l'ancien avatar (sur le serveur) si il existe!
						$user_avatar_path = $Sql->query("SELECT user_avatar FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
						if (!empty($user_avatar_path) && preg_match('`\.\./images/avatars/(([a-z0-9()_-])+\.([a-z]){3,4})`i', $user_avatar_path, $match))
						{
							if (is_file($user_avatar_path) && isset($match[1]))
								@unlink(PATH_TO_ROOT .'/images/avatars/' . $match[1]);
						}						
						$user_avatar = $path; //Avatar uploadé et validé.
					}
				}
			}
			
			if (!empty($_POST['avatar']))
			{
				$path = TextHelper::strprotect($_POST['avatar']);
				$error = $Upload->check_img($user_accounts_config->get_max_avatar_width(), $user_accounts_config->get_max_avatar_height(), Upload::DELETE_ON_ERROR);
				if (!empty($error)) //Erreur, on arrête ici
					AppContext::get_response()->redirect('/admin/admin_members' . url('.php?id=' .  $id_post . '&erroru=' . $error) . '#message_helper');
				else
					$user_avatar = $path; //Avatar posté et validé.
			}

			$user_avatar = !empty($user_avatar) ? "user_avatar = '" . $user_avatar . "', " : '';
			if (!empty($login) && !empty($user_mail))
			{	
				//Suppression des images des stats concernant les membres, si l'info à été modifiée.
				$info_mbr = $Sql->query_array(DB_TABLE_MEMBER, "user_theme", "user_sex", "WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
				if ($info_mbr['user_sex'] != $user_sex)
					@unlink(PATH_TO_ROOT .'/cache/sex.png');
				if ($info_mbr['user_theme'] != $user_theme)
					@unlink(PATH_TO_ROOT .'/cache/theme.png');
				
                //Si le membre n'était pas approuvé et qu'on l'approuve et qu'il existe une alerte, on la règle automatiquement
                $member_infos = $Sql->query_array(DB_TABLE_MEMBER, "user_aprob", "level", "WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
				if ($member_infos['user_aprob'] != $user_aprob && $member_infos['user_aprob'] == 0)
				{
					//On recherche l'alerte
					
					
					//Recherche de l'alerte correspondante
					$matching_alerts = AdministratorAlertService::find_by_criteria($id_post, 'member_account_to_approbate');
					
					//L'alerte a été trouvée
					if (count($matching_alerts) == 1)
					{
						$alert = $matching_alerts[0];
						$alert->set_status(AdministratorAlert::ADMIN_ALERT_STATUS_PROCESSED);
						AdministratorAlertService::save_alert($alert);
					}

					//Régénération du cache des stats.
					StatsCache::invalidate();
				}
				
                $Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET login = '" . $login . "', level = '" . $MEMBER_LEVEL . "', user_lang = '" . $user_lang . "', user_theme = '" . $user_theme . "', user_mail = '" . $user_mail . "', user_show_mail = " . $user_show_mail . ", user_editor = '" . $user_editor . "', user_timezone = '" . $user_timezone . "', user_local = '" . $user_local . "', " . $user_avatar . "user_msn = '" . $user_msn . "', user_yahoo = '" . $user_yahoo . "', user_web = '" . $user_web . "', user_occupation = '" . $user_occupation . "', user_hobbies = '" . $user_hobbies . "', user_desc = '" . $user_desc . "', user_sex = '" . $user_sex . "', user_born = '" . $user_born . "', user_sign = '" . $user_sign . "', user_warning = '" . $user_warning . "', user_readonly = '" . $user_readonly . "', user_ban = '" . $user_ban . "', user_aprob = '" . $user_aprob . "' WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
				
                //Mise à jour de la session si l'utilisateur change de niveau pour lui donner immédiatement les droits
                if ($member_infos['level'] != $MEMBER_LEVEL)
					$Sql->query_inject("UPDATE " . DB_TABLE_SESSIONS . " SET level = '" . $MEMBER_LEVEL . "' WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
				
				if ($user_ban > 0)	//Suppression de la session si le membre se fait bannir.
				{	
					$Sql->query_inject("DELETE FROM " . DB_TABLE_SESSIONS . " WHERE user_id = '" . $id_post . "'", __LINE__, __FILE__);
					
					AppContext::get_mail_service()->send_from_properties($user_mail, addslashes($LANG['ban_title_mail']), sprintf(addslashes($LANG['ban_mail']), HOST, addslashes(MailServiceConfig::load()->get_mail_signature())));
				}
				
				MemberExtendedFieldsService::update_fields($id_post);
				
				AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
			}
			else
				AppContext::get_response()->redirect('/admin/admin_members' . url('.php?id=' .  $id_post . '&error=incomplete') . '#message_helper');
		}
	}
	else
		AppContext::get_response()->redirect('/admin/admin_members' . url('.php?id=' .  $id_post . '&error=incomplete') . '#message_helper');
}
elseif ($add && !empty($_POST['add'])) //Ajout du membre.
{
	$login = !empty($_POST['login2']) ? TextHelper::strprotect(substr($_POST['login2'], 0, 25)) : '';
	$password = retrieve(POST, 'password2', '', TSTRING_UNCHANGE);
	$password_bis = retrieve(POST, 'password2_bis', '', TSTRING_UNCHANGE);
	$password_hash = !empty($password) ? strhash($password) : '';
	$level = retrieve(POST, 'level2', 0);
	$mail = strtolower(retrieve(POST, 'mail2', ''));
	
	if (check_mail($mail))
	{	
		//Vérirication de l'unicité du membre et du mail
		$check_user = $Sql->query("SELECT COUNT(*) as compt FROM " . DB_TABLE_MEMBER . " WHERE login = '" . $login . "'", __LINE__, __FILE__);
		$check_mail = $Sql->query("SELECT COUNT(*) as compt FROM " . DB_TABLE_MEMBER . " WHERE user_mail = '" . $mail . "'", __LINE__, __FILE__);
		if ($check_user >= 1) 
			AppContext::get_response()->redirect('/admin/admin_members' . url('.php?error=pseudo_auth&add=1') . '#message_helper');
		elseif ($check_mail >= 1) 
			AppContext::get_response()->redirect('/admin/admin_members' . url('.php?error=auth_mail&add=1') . '#message_helper');
		else
		{
			if (strlen($password) >= 6 && strlen($password_bis) >= 6)
			{
				if (!empty($login))
				{	
					//On insere le nouveau membre.
					$Sql->query_inject("INSERT INTO " . DB_TABLE_MEMBER . " (login,password,level,user_groups,user_lang,user_theme,user_mail,user_timezone,user_show_mail,timestamp,user_avatar,user_msg,user_local,user_msn,user_yahoo,user_web,user_occupation,user_hobbies,user_desc,user_sex,user_born,user_sign,user_pm,user_warning,user_readonly,last_connect,test_connect,activ_pass,new_pass,user_ban,user_aprob) 
					VALUES('" . $login . "', '" . $password_hash . "', '" . $level . "', '', '" . UserAccountsConfig::load()->get_default_lang() . "', '', '" . $mail . "', '" . GeneralConfig::load()->get_site_timezone() . "', '1', '" . time() . "', '', 0, '', '', '', '', '', '', '', 0, '0000-00-00', '', 0, 0, 0, 0, 0, '', '', 0, 1)", __LINE__, __FILE__);
					
					//On régénère le cache
					StatsCache::invalidate();
						
					AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT); 	
				}
				else
					AppContext::get_response()->redirect('/member/member' . url('.php?error=incomplete&add=1') . '#message_helper');
			}
			else //Longueur minimale du password
				AppContext::get_response()->redirect('/admin/admin_members' . url('.php?id=' .  $id_get . '&error=pass_mini&add=1') . '#message_helper');
		}
	}
	else
		AppContext::get_response()->redirect('/admin/admin_members' . url('.php?error=invalid_mail&add=1') . '#message_helper');
}
elseif (!empty($id) && $delete) //Suppression du membre.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	//On supprime dans la bdd.
	$Sql->query_inject("DELETE FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $id . "'", __LINE__, __FILE__);
	
	
	Uploads::Empty_folder_member($id); //Suppression de tout les fichiers et dossiers du membre.
	
	//On régénère le cache
	StatsCache::invalidate();
		
	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
}
elseif ($add)
{
	$template = new FileTemplate('admin/admin_members_management2.tpl');
	
	//Gestion des erreurs.
	switch ($get_error)
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
	if (!empty($errstr))
		$Template->put('message_helper', MessageHelper::display($errstr, E_USER_NOTICE));  
		
	$template->put_all(array(
		'C_USERS_ADD' => true,
		'L_USERS_MANAGEMENT' => $LANG['members_management'],
		'L_USERS_ADD' => $LANG['members_add'],
		'L_USERS_CONFIG' => $LANG['members_config'],
		'L_USERS_PUNISHMENT' => $LANG['punishment_management'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_PASSWORD' => $LANG['password'],
		'L_PASSWORD_CONFIRM' => $LANG['confirm_password'],
		'L_MAIL' => $LANG['mail'],
		'L_RANK' => $LANG['rank'],
		'L_USER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_RESET' => $LANG['reset'],
		'L_ADD' => $LANG['add']
	));
	
	$template->display(); 	
}
elseif (!empty($id))	
{		
	$template = new FileTemplate('admin/admin_members_management2.tpl');
	
	$mbr = $Sql->query_array(DB_TABLE_MEMBER, '*', "WHERE user_id = '" . $id . "'", __LINE__, __FILE__);

	$user_born = '';
	$array_user_born = explode('-', $mbr['user_born']);
	$date_birth = explode('/', $LANG['date_birth_parse']);
	for ($i = 0; $i < 3; $i++)
	{
		if ($date_birth[$i] == 'DD')
		{	
			$user_born .= $array_user_born[2 - $i];
			$born_day = $array_user_born[2 - $i];
		}
		elseif ($date_birth[$i] == 'MM')
		{	
			$user_born .= $array_user_born[2 - $i];
			$born_month = $array_user_born[2 - $i];
		}
		elseif ($date_birth[$i] == 'YYYY')	
		{
			$user_born .= $array_user_born[2 - $i];				
			$born_year = $array_user_born[2 - $i];
		}
		$user_born .= ($i != 2) ? '/' : '';	
	}
	
	//Gestion des erreurs.
	switch ($get_error)
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
	if (!empty($errstr))
		$Template->put('message_helper', MessageHelper::display($errstr, E_USER_NOTICE));  

	if (isset($LANG[$get_l_error]))
		$Template->put('message_helper', MessageHelper::display($errstr, E_USER_WARNING));   

	$user_sex = '';
	if (!empty($mbr['user_sex']))
		$user_sex = ($mbr['user_sex'] == 1) ? '/images/man.png' : '/images/woman.png';
	
	//Rang d'autorisation.
	$array_ranks = array(0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	$ranks_options = '';
	for ($i = 0 ; $i <= 2 ; $i++)
	{
		$selected = ($mbr['level'] == $i) ? 'selected="selected"' : '' ;
		$ranks_options .= '<option value="' . $i . '" ' . $selected . '>' . $array_ranks[$i] . '</option>';
	}
	
	//Groupes.	
	$i = 0;
	$groups_options = '';
	$groupe_cache = GroupsCache::load()->get_groups();
	foreach ($groupe_cache as $id => $values)
	{		
		$selected = '';		
		$search_group = array_search($id, explode('|', $mbr['user_groups']));		
		if (is_numeric($search_group))
			$selected = 'selected="selected"';	
			
		$groups_options .= '<option value="' . $id . '" id="g' . $i . '" ' . $selected . '>' . $values['name'] . '</option>';
		$i++;
	}

	//Temps de bannissement.
	$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 326592000);
	$array_sanction = array($LANG['no'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '1 ' . $LANG['day'], '2 ' . $LANG['days'], '1 ' . $LANG['week'], '2 ' . $LANG['weeks'], '1 ' . $LANG['month'], $LANG['life']); 
	$diff = ($mbr['user_ban'] - time());	
	$key_sanction = 0;
	if ($diff > 0)
	{
		//Retourne la sanction la plus proche correspondant au temp de bannissement. 
		for ($i = 12; $i > 0; $i--)
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
	$ban_options = '';
	foreach ($array_time as $key => $time)
	{
		$selected = ( $key_sanction == $key ) ? 'selected="selected"' : '' ;		
		$ban_options .= '<option value="' . $time . '" ' . $selected . '>' . $array_sanction[$key] . '</option>';
	}

	//Durée de la sanction.
	$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 326592000); 	
	$array_sanction = array($LANG['no'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '1 ' . $LANG['day'], '2 ' . $LANG['days'], '1 ' . $LANG['week'], '2 ' . $LANG['weeks'], '1 ' . $LANG['month'], $LANG['life']); 
	$diff = ($mbr['user_readonly'] - time());	
	$key_sanction = 0;
	if ($diff > 0)
	{
		//Retourne la sanction la plus proche correspondant au temp de bannissement. 
		for ($i = 12; $i > 0; $i--)
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
	$readonly_options = '';
	foreach ($array_time as $key => $time)
	{
		$selected = ($key_sanction == $key) ? ' selected="selected"' : '' ;
		$readonly_options .= '<option value="' . $time . '"' . $selected . '>' . $array_sanction[$key] . '</option>';
	}
		
	//On crée le formulaire select
	$warning_options = '';
	$j = 0;
	for ($j = 0; $j <=10; $j++)
	{
		$selected = ((10 * $j) == $mbr['user_warning']) ? ' selected="selected"' : '';
		$warning_options .= '<option value="' . 10 * $j . '"' . $selected . '>' . 10 * $j . '%</option>';
	}
	
	//Gestion LANG par défaut.
	$array_identifier = '';
	$lang_identifier = PATH_TO_ROOT .'/images/stats/other.png';
	$langs_cache = LangsCache::load();
	foreach (array_keys($langs_cache->get_installed_langs()) as $lang)
	{
		$info_lang = load_ini_file(PATH_TO_ROOT .'/lang/', $lang);
		$selected = '';
		if (UserAccountsConfig::load()->get_default_lang() == $lang)
		{
			$selected = ' selected="selected"';
			$lang_identifier = PATH_TO_ROOT .'/images/stats/countries/' . $info_lang['identifier'] . '.png';
		}
		$array_identifier .= 'array_identifier[\'' . $lang . '\'] = \'' . $info_lang['identifier'] . '\';' . "\n";
		$template->assign_block_vars('select_lang', array(
			'NAME' => !empty($info_lang['name']) ? $info_lang['name'] : $lang,
			'IDNAME' => $lang,
			'SELECTED' => $selected
		));
	}
	
	//Gestion thème par défaut.
	foreach(ThemesCache::load()->get_installed_themes() as $theme => $properties)
	{
		if ($theme != 'default')
		{
			$selected = (UserAccountsConfig::load()->get_default_theme() == $theme) ? ' selected="selected"' : '';
			$info_theme = load_ini_file(PATH_TO_ROOT .'/templates/' . $theme . '/config/', get_ulang());
			$template->assign_block_vars('select_theme', array(
				'NAME' => $info_theme['name'],
				'IDNAME' => $theme,
				'SELECTED' => $selected
			));
		}
	}
	
	//Editeur texte par défaut.
	$editors = array('bbcode' => 'BBCode', 'tinymce' => 'Tinymce');
	$editor_options = '';
	foreach ($editors as $code => $name)
	{
		$selected = ($code == $mbr['user_editor']) ? 'selected="selected"' : '';
		$editor_options .= '<option value="' . $code . '" ' . $selected . '>' . $name . '</option>';
	}
	
	//Gestion fuseau horaire par défaut.
	$timezone_options = '';
	for ($i = -12; $i <= 14; $i++)
	{
		$selected = ($i == $mbr['user_timezone']) ? 'selected="selected"' : '';
		$name = (!empty($i) ? ($i > 0 ? ' + ' . $i : ' - ' . -$i) : '');
		$timezone_options .= '<option value="' . $i . '" ' . $selected . '> [GMT' . $name . ']</option>';
	}
		
	//Sex par défaut
	$i = 0;
	$array_sex = array('--', $LANG['male'], $LANG['female'], );
	$sex_options = '';
	foreach ($array_sex as $value_sex)
	{		
		$selected = ($i == $mbr['user_sex']) ? 'selected="selected"' : '';
		$sex_options .= '<option value="' . $i . '" ' . $selected . '>' . $value_sex . '</option>';
		$i++;
	}
	
	//Champs supplémentaires.
	ExtendFieldMember::display($Template, retrieve(GET, 'id', 0));
	
	$user_accounts_config = UserAccountsConfig::load();
	
	//On assigne les variables pour le POST en précisant l'user_id.
	$template->put_all(array(
		'C_USERS_MANAGEMENT' => true,
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
		'NBR_GROUP' => $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "group", __LINE__, __FILE__),
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
		'SIGN' => FormatingHelper::unparse($mbr['user_sign'], FormatingHelper::NO_EDITOR_UNPARSE),
		'BIOGRAPHY' => FormatingHelper::unparse($mbr['user_desc'], FormatingHelper::NO_EDITOR_UNPARSE),
		'USER_AVATAR' => !empty($mbr['user_avatar']) ? '<img src="' . $mbr['user_avatar'] . '" alt="" />' : '<em>' . $LANG['no_avatar'] . '</em>',
		'AVATAR_LINK' => $mbr['user_avatar'],
		'SHOW_MAIL_CHECKED' => ($mbr['user_show_mail'] == 0) ? 'checked="checked"' : '',
		'THEME' => get_utheme(),
		'WEIGHT_MAX' => $user_accounts_config->get_max_avatar_weight(),
		'HEIGHT_MAX' => $user_accounts_config->get_max_avatar_height(),
		'WIDTH_MAX' => $user_accounts_config->get_max_avatar_width(),
		'USER_SIGN_EDITOR' => display_editor('user_sign'),
		'USER_DESC_EDITOR' => display_editor('user_desc'),
		'L_REQUIRE_MAIL' => $LANG['require_mail'],
		'L_REQUIRE_RANK' => $LANG['require_rank'],
		'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
		'L_REQUIRE' => $LANG['require'],
		'L_CONFIRM_DEL_USER' => $LANG['confirm_del_member'],
		'L_USERS_MANAGEMENT' => $LANG['members_management'],
		'L_USERS_ADD' => $LANG['members_add'],
		'L_USERS_CONFIG' => $LANG['members_config'],
		'L_USERS_PUNISHMENT' => $LANG['punishment_management'],
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
		'L_USER_SIGN' => $LANG['member_sign'],
		'L_USER_SIGN_EXPLAIN' => $LANG['member_sign_explain'],
		'L_USER_BIOGRAPHY' => $LANG['biography'],
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
		'L_USER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_WEBSITE' => $LANG['website'],
		'L_REGISTERED' => $LANG['registered'],
		'L_DELETE' => $LANG['delete'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));

	$template->display();
}
else
{			
	$template = new FileTemplate('admin/admin_members_management.tpl');
	 
	$template->put_all(array(
		'C_DISPLAY_SEARCH_RESULT' => false
	));
	
	$search = retrieve(POST, 'login_mbr', ''); 
	if (!empty($search)) //Moteur de recherche des members
	{
		$search = str_replace('*', '%', $search);
		$req = "SELECT user_id, login FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '".$search."%'";
		$nbr_result = $Sql->query("SELECT COUNT(*) as compt FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '%".$search."%'", __LINE__, __FILE__);

		if (!empty($nbr_result))
		{			
			$result = $Sql->query_while ($req, __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result)) //On execute la requête dans une boucle pour afficher tout les résultats.
			{ 
				$template->assign_block_vars('search', array(
					'RESULT' => '<a href="'. PATH_TO_ROOT .'/admin/admin_members.php?id=' . $row['user_id'] . '">' . $row['login'] . '</a><br />'
				));
				$template->put_all(array(
					'C_DISPLAY_SEARCH_RESULT' => true
				));
			}
			$Sql->query_close($result);
		}
		else
		{
			$template->put_all(array(
				'C_DISPLAY_SEARCH_RESULT' => true
			));
			$template->assign_block_vars('search', array(
				'RESULT' => $LANG['no_result']
			));
		}		
	}

	$nbr_membre = $Sql->count_table(DB_TABLE_MEMBER, __LINE__, __FILE__);
	//On crée une pagination si le nombre de membre est trop important.
	 
	$Pagination = new DeprecatedPagination();
	 
	$get_sort = retrieve(GET, 'sort', '');	
	switch ($get_sort)
	{
		case 'time' : 
		$sort = 'timestamp';
		break;		
		case 'last' : 
		$sort = 'last_connect';
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
	
	$get_mode = retrieve(GET, 'mode', '');	
	$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';	
	$unget = (!empty($get_sort) && !empty($mode)) ? '&amp;sort=' . $get_sort . '&amp;mode=' . $get_mode : '';

	$template->put_all(array(
		'PAGINATION' => $Pagination->display('admin_members.php?p=%d' . $unget, $nbr_membre, 'p', 25, 3),	
		'THEME' => get_utheme(),
		'LANG' => get_ulang(),
		'KERNEL_EDITOR' => display_editor(),
		'L_REQUIRE_MAIL' => $LANG['require_mail'],
		'L_REQUIRE_PASS' => $LANG['require_pass'],
		'L_REQUIRE_RANK' => $LANG['require_rank'],
		'L_REQUIRE_LOGIN' => $LANG['require_pseudo'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_CONFIRM_DEL_USER' => $LANG['confirm_del_member'],
		'L_CONFIRM_DEL_ADMIN' => $LANG['confirm_del_admin'],
		'L_CONTENTS' => $LANG['content'],
		'L_SUBMIT' => $LANG['submit'],
		'L_UPDATE' => $LANG['update'],
		'L_USERS_MANAGEMENT' => $LANG['members_management'],
		'L_USERS_ADD' => $LANG['members_add'],
		'L_USERS_CONFIG' => $LANG['members_config'],
		'L_USERS_PUNISHMENT' => $LANG['members_punishment'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_PASSWORD' => $LANG['password'],
		'L_MAIL' => $LANG['mail'],
		'L_RANK' => $LANG['rank'],
		'L_APROB' => $LANG['aprob'],
		'L_USER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_SEARCH_USER' => $LANG['search_member'],
		'L_JOKER' => $LANG['joker'],
		'L_SEARCH' => $LANG['search'],
		'L_LAST_CONNECT' => $LANG['last_connect'],
		'L_REGISTERED' => $LANG['registered'],
		'L_DELETE' => $LANG['delete']
	));
		
	$result = $Sql->query_while("SELECT login, user_id, user_mail, timestamp, last_connect, level, user_aprob
	FROM " . DB_TABLE_MEMBER . " 
	ORDER BY " . $sort . " " . $mode . 
	$Sql->limit($Pagination->get_first_msg(25, 'p'), 25), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
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
		
		$user_web = !empty($row['user_web']) ? '<a href="' . $row['user_web'] . '"><img src="'. PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="' . $row['user_web'] . '" title="' . $row['user_web'] . '" /></a>' : '';
		
		$template->assign_block_vars('member', array(
			'IDMBR' => $row['user_id'],
			'NAME' => $row['login'],
			'RANK' => $rank,
			'MAIL' => $row['user_mail'],
			'LAST_CONNECT' => gmdate_format('date_format_short', $row['last_connect']),
			'LEVEL' => $row['level'],
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'APROB' => ($row['user_aprob'] == 0) ? $LANG['no'] : $LANG['yes']		
		));
	}
	$Sql->query_close($result);
	
	$template->display(); 
}
require_once('../admin/admin_footer.php');

?>