<?php
/*##################################################
 *                             register_valid.php
 *                            -------------------
 *   begin                : August 04 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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
define('TITLE', $LANG['title_register']);
require_once('../kernel/header.php');

$user_accounts_config = UserAccountsConfig::load();

if (!$user_accounts_config->is_registration_enabled())
	AppContext::get_response()->redirect(Environment::get_home_page());

$user_mail = strtolower(retrieve(POST, 'mail', ''));
$valid = retrieve(POST, 'valid', false);
if ($valid && !empty($user_mail) && check_mail($user_mail))
{
	//Info de connexion
	$login = !empty($_POST['log']) ? TextHelper::strprotect(substr($_POST['log'], 0, 25)) : '';
	$password = retrieve(POST, 'pass', '', TSTRING_UNCHANGE);
	$password_hash = strhash($password);
	$password_bis = retrieve(POST, 'pass_bis', '', TSTRING_UNCHANGE);
	$password_bis_hash = strhash($password_bis);
		
	//Configuration
	$user_show_mail = retrieve(POST, 'user_show_mail', 0) ? 1 : 0;
	$user_lang = retrieve(POST, 'user_lang', '');
	$user_theme = retrieve(POST, 'user_theme', '');
	$user_editor = retrieve(POST, 'user_editor', '');
	$user_timezone = retrieve(POST, 'user_timezone', 0);
	
	//Informations.
	$user_avatar = retrieve(POST, 'user_avatar', '');
	$user_local = retrieve(POST, 'user_local', '');
	$user_occupation = retrieve(POST, 'user_occupation', '');
	$user_hobbies = retrieve(POST, 'user_hobbies', '');
	$user_desc = retrieve(POST, 'user_desc', '', TSTRING_PARSE);
	$user_sex = retrieve(POST, 'user_sex', 0);
	$user_sign = retrieve(POST, 'user_sign', '', TSTRING_PARSE);
	$user_msn = retrieve(POST, 'user_msn', '');
	$user_yahoo = retrieve(POST, 'user_yahoo', '');
	$user_web = retrieve(POST, 'user_web', '');
	
	if (!empty($user_web) && strpos($user_web, '://') === false)
	{
		$user_web = 'http://' . $user_web;
	}
	
	//Gestion de la date de naissance.
	$user_born = strtodate(retrieve(POST, 'user_born', '0'), $LANG['date_birth_parse']);
		
	//Code de vérification si activé
	
	$Captcha = new Captcha();
	$Captcha->set_difficulty($user_accounts_config->get_registration_captcha_difficulty());
	
	if (!$user_accounts_config->is_registration_captcha_enabled() || $Captcha->is_valid()) //Code de vérification si activé
	{
		if (strlen($login) >= 3 && strlen($password) >= 6 && strlen($password_bis) >= 6)
		{
			if (!empty($login) && !empty($user_mail) && $password_hash === $password_bis_hash)
			{
				####Vérification de la validité de l'avatar####
				$user_avatar = '';
				$dir = '../images/avatars/';
				$Upload = new Upload($dir);

				if ($user_accounts_config->is_avatar_upload_enabled())
				{
					if ($user_accounts_config->is_avatar_auto_resizing_enabled() && !isset($_FILES['avatars']))
					{
						import('io/image/Image');
						import('io/image/ImageResizer');
						
						$name_image = $_FILES['avatars']['name'];
						$image = new Image($_FILES['avatars']['tmp_name']);
						$resizer = new ImageResizer();
						$resizer->resize_with_max_values($image, $user_accounts_config->get_max_avatar_height(), $user_accounts_config->get_max_avatar_height(), $dir . $name_image);
						
						$user_avatar = $dir . $name_image;
						
						// TODO gestion des erreurs 
					}
					else
					{
						if ($Upload->get_size() > 0)
						{
							$Upload->file('avatars', '`([a-z0-9()_-])+\.(jpg|gif|png|bmp)+$`i', Upload::UNIQ_NAME, $user_accounts_config->get_max_avatar_weight() * 1024);
							if ($Upload->get_error() != '') //Erreur, on arrête ici
								AppContext::get_response()->redirect('/member/register' . url('.php?erroru=' . $Upload->get_error()) . '#errorh');
							else
							{
								$path = $dir . $Upload->get_filename();
								$error = $Upload->check_img($user_accounts_config->get_max_avatar_width(), $user_accounts_config->get_max_avatar_height(), Upload::DELETE_ON_ERROR);
								if (!empty($error)) //Erreur, on arrête ici
									AppContext::get_response()->redirect('/member/register' . url('.php?erroru=' . $error) . '#errorh');
								else
									$user_avatar = $path; //Avatar uploadé et validé.
							}
						}
					}
				}
				$path = retrieve(POST, 'avatar', '');
				if (!empty($path))
				{
					$error = $Upload->check_img($user_accounts_config->get_max_avatar_width(), $user_accounts_config->get_max_avatar_height(), Upload::DELETE_ON_ERROR);
					if (!empty($error)) //Erreur, on arrête ici
						AppContext::get_response()->redirect('/member/register' . url('.php?erroru=' . $error) . '#errorh');
					else
						$user_avatar = $path; //Avatar posté et validé.
				}
				
				$admin_sign = MailServiceConfig::load()->get_mail_signature();
						
				$check_user = $Sql->query("SELECT COUNT(*) as compt FROM " . DB_TABLE_MEMBER . " WHERE login = '" . $login . "'", __LINE__, __FILE__);
				$check_mail = $Sql->query("SELECT COUNT(*) as compt FROM " . DB_TABLE_MEMBER . " WHERE user_mail = '" . $user_mail . "'", __LINE__, __FILE__);
			
				if ($check_user >= 1)
					AppContext::get_response()->redirect('/member/register' . url('.php?error=pseudo_auth') . '#errorh');
				elseif ($check_mail >= 1)
					AppContext::get_response()->redirect('/member/register' . url('.php?error=mail_auth') . '#errorh');
				else //Succes.
				{
					$user_aprob = ($user_accounts_config->get_member_accounts_validation_method() == 0) ? 1 : 0;
					$activ_mbr = ($user_accounts_config->get_member_accounts_validation_method() == 1) ? substr(strhash(uniqid(rand(), true)), 0, 15) : ''; //Génération de la clée d'activation!
					
					//Suppression des images des stats concernant les membres, si l'info à été modifiée.
					@unlink('../cache/sex.png');
					@unlink('../cache/theme.png');
					
					$Sql->query_inject("INSERT INTO " . DB_TABLE_MEMBER . " (login,password,level,user_groups,user_lang,user_theme,user_mail,user_show_mail,user_editor,user_timezone,timestamp,user_avatar,user_msg,user_local,user_msn,user_yahoo,user_web,user_occupation,user_hobbies,user_desc,user_sex,user_born,user_sign,user_pm,user_warning,last_connect,test_connect,activ_pass,new_pass,user_ban,user_aprob)
					VALUES ('" . $login . "', '" . $password_hash . "', 0, '0', '" . $user_lang . "', '" . $user_theme . "', '" . $user_mail . "', '" . $user_show_mail . "', '" . $user_editor . "', '" . $user_timezone . "', '" . time() . "', '" . $user_avatar . "', 0, '" . $user_local . "', '" . $user_msn . "', '" . $user_yahoo . "', '" . $user_web . "', '" . $user_occupation . "', '" . $user_hobbies . "', '" . $user_desc . "', '" . $user_sex . "', '" . $user_born . "', '" . $user_sign . "', 0, 0, '" . time() . "', 0, '" . $activ_mbr . "', '', 0, '" . $user_aprob . "')", __LINE__, __FILE__); //Compte membre
					
					$last_mbr_id = $Sql->insert_id("SELECT MAX(id) FROM " . DB_TABLE_MEMBER); //Id du membre qu'on vient d'enregistrer
					
					//Si son inscription nécessite une approbation, on en avertit l'administration au biais d'une alerte
					if ($user_accounts_config->get_member_accounts_validation_method() == 2)
					{
						$alert = new AdministratorAlert();
						$alert->set_entitled($LANG['member_registered_to_approbate']);
						$alert->set_fixing_url('admin/admin_members.php?id=' . $last_mbr_id);
						//Priorité 3/5
						$alert->set_priority(AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY);
						//Code pour retrouver l'alerte
						$alert->set_id_in_module($last_mbr_id);
						$alert->set_type('member_account_to_approbate');
						
						//Enregistrement
						AdministratorAlertService::save_alert($alert);
					}
					else //Régénération du cache des stats.
						StatsCache::invalidate();
					
					//Champs supplémentaires.
					$ExtendFieldMemberService = new ExtendFieldMemberService();
					$ExtendFieldMemberService->add($last_mbr_id);
					
					$error = $extend_field_member_service->errors();
					if (!empty($error))
					{
						AppContext::get_response()->redirect('/member/register' . url('.php?error=incomplete') . '#errorh');
					}
					
					//Ajout du lien de confirmation par mail si activé et activation par admin désactivé.
					if ($user_accounts_config->get_member_accounts_validation_method() == 1)
					{
						$l_register_confirm = $LANG['confirm_register'] . '<br />' . $LANG['register_valid_email_confirm'];
						$valid = sprintf($LANG['register_valid_email'], HOST . DIR . '/member/register.php?key=' . $activ_mbr);
					}
					elseif ($user_accounts_config->get_member_accounts_validation_method() == 2)
					{
						$l_register_confirm = $LANG['confirm_register'] . '<br />' . $LANG['register_valid_admin'];
						$valid = $LANG['register_valid_admin'];
					}
					else
					{
						$l_register_confirm = $LANG['confirm_register'] . '<br />' . $LANG['register_ready'];
						$valid_mail = '';
						$valid = '';
					}
					
					$site_name = GeneralConfig::load()->get_site_name();
					AppContext::get_mail_service()->send_from_properties($user_mail, sprintf($LANG['register_title_mail'], GeneralConfig::load()->get_site_name()), sprintf($LANG['register_mail'], $login, $site_name, $site_name, stripslashes($login), $password, $valid, MailServiceConfig::load()->get_mail_signature()));
					
					//On connecte le membre directement si aucune activation demandée.
					if ($user_accounts_config->get_member_accounts_validation_method() == 0)
					{
						$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect='" . time() . "' WHERE user_id = '" . $last_mbr_id . "'", __LINE__, __FILE__); //Remise à zéro du compteur d'essais.
						$Session->start($last_mbr_id, $password, 0, SCRIPT, QUERY_STRING, TITLE, 1); //On lance la session.
					}
					unset($password, $password_hash);
					
					//Affichage de la confirmation d'inscription.
					redirect_confirm(Environment::get_home_page(), sprintf($l_register_confirm, stripslashes($login)), 5);
				}
			}
			elseif (!empty($_POST['register_valid']) && $password !== $password_bis)
				AppContext::get_response()->redirect('/member/register' . url('.php?error=pass_same') . '#errorh');
			else
				AppContext::get_response()->redirect('/member/register' . url('.php?error=incomplete') . '#errorh');
		}
		else
			AppContext::get_response()->redirect('/member/register' . url('.php?error=lenght_mini') . '#errorh');
	}
	else
		AppContext::get_response()->redirect('/member/register' . url('.php?error=verif_code') . '#errorh');
}
elseif (!empty($user_mail))
	AppContext::get_response()->redirect('/member/register' . url('.php?error=invalid_mail') . '#errorh');
else
	AppContext::get_response()->redirect(Environment::get_home_page());
	
require_once('../kernel/footer.php');

?>