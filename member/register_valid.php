<?php
/*##################################################
 *                                register_valid.php
 *                            -------------------
 *   begin                : August 04 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

include_once('../includes/begin.php'); 
define('TITLE', $LANG['title_register']);
include_once('../includes/header.php'); 

$cache->load_file('member');
if( !$CONFIG_MEMBER['activ_register'] )
{
	header('location: ' . get_start_page());
	exit;
}

$user_mail = !empty($_POST['mail']) ? strtolower($_POST['mail']) : '';
if( !empty($_POST['register_valid']) && !empty($user_mail) && preg_match('`^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-zA-Z]{2,4}$`', $user_mail) )
{	
	//Info de connexion
	$login = clean_user($_POST['log']) ;			
	$password = !empty($_POST['pass']) ? trim($_POST['pass']) : '';
	$password_md5 = !empty($password) ? md5($password) : '';
	$password_bis = !empty($_POST['pass_bis']) ? trim($_POST['pass_bis']) : '';
	$password_bis_md5 = !empty($password_bis) ? md5($password_bis) : '';
			
	//Configuration
	$user_mail = securit($user_mail);
	$user_show_mail = !empty($_POST['user_show_mail']) ? 1 : 0;
	$user_lang = !empty($_POST['user_lang']) ? securit($_POST['user_lang']) : '';
	$user_theme = !empty($_POST['user_theme']) ? securit($_POST['user_theme']) : '';	
	
	//Informations.
	$user_avatar = !empty($_POST['user_avatar']) ? securit($_POST['user_avatar']) : '';
	$user_local = !empty($_POST['user_local']) ? securit($_POST['user_local']) : '';
	$user_occupation = !empty($_POST['user_occupation']) ? securit($_POST['user_occupation']) : '';
	$user_hobbies = !empty($_POST['user_hobbies']) ? securit($_POST['user_hobbies']) : '';
	$user_desc = !empty($_POST['user_desc']) ? $_POST['user_desc'] : '';
	$user_sex = !empty($_POST['user_sex']) ? numeric($_POST['user_sex']) : 0;
	$user_sign = !empty($_POST['user_sign']) ? $_POST['user_sign'] : '';
	$user_msn = !empty($_POST['user_msn']) ? securit($_POST['user_msn']) : '';
	$user_yahoo = !empty($_POST['user_yahoo']) ? securit($_POST['user_yahoo']) : '';
	
	//Gestion de la date de naissance.
	$user_born = strtodate($_POST['user_born'], $LANG['date_birth_parse']);
		
	//Validité de l'adresse du site.
	$user_web = !empty($_POST['user_web']) ? securit($_POST['user_web']) : '';
	$user_web = ( !empty($user_web) && preg_match('!^http(s)?://[a-z0-9._/-]+\.[-[:alnum:]]+\.[a-zA-Z]{2,4}(.*)$!', $_POST['user_web']) ) ? $user_web : '';
	
		
	//Code de vérification si activé
	$check_verif_code = true;
	if( $CONFIG_MEMBER['verif_code'] == '1' && @extension_loaded('gd') )
	{
		$user_id = substr(md5(USER_IP), 0, 8);
		$verif_code = $sql->query("SELECT code FROM ".PREFIX."verif_code WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);	
		$get_verif_code = !empty($_POST['verif_code']) ? trim($_POST['verif_code']) : '';

		if( empty($verif_code) || ($verif_code != $get_verif_code) )
			$check_verif_code = false;
		else //On efface le code qui a été utilisé.
			$sql->query_inject("DELETE FROM ".PREFIX."verif_code WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);			
	}
	
	if( $check_verif_code ) //Code de vérification si activé
	{
		if( strlen($login) >= 3 && strlen($password) >= 6 && strlen($password_bis) >= 6 )
		{
			if( !empty($login) && !empty($user_mail) && $password_md5 === $password_bis_md5 )
			{
				####Vérification de la validité de l'avatar####
				$user_avatar = '';
				//Gestion upload d'avatar.				
				$dir = '../images/avatars/';
				include_once('../includes/upload.class.php');
				$upload = new Upload($dir);
				
				if( is_writable($dir) && $CONFIG_MEMBER['activ_up_avatar'] == 1 )
				{
					if( $_FILES['avatars']['size'] > 0 )
					{
						$upload->upload_file('avatars', '`([a-z0-9])+\.(jpg|gif|png|bmp)+`i', UNIQ_NAME, $CONFIG_MEMBER['weight_max']*1024);
						
						if( !empty($upload->error) ) //Erreur, on arrête ici
						{
							header('Location:' . HOST . DIR . '/member/register' . transid('.php?erroru=' . $upload->error) . '#errorh');
							exit;					
						}
						else
						{
							$path = $dir . $upload->filename['avatars'];
							$error = $upload->validate_img($path, $CONFIG_MEMBER['width_max'], $CONFIG_MEMBER['height_max'], DELETE_ON_ERROR);
							if( !empty($error) ) //Erreur, on arrête ici
							{
								header('Location:' . HOST . DIR . '/member/register' . transid('.php?erroru=' . $error) . '#errorh');
								exit;					
							}
							else
								$user_avatar = $path; //Avatar uploadé et validé.
						}
					}
				}
				
				if( !empty($_POST['avatar']) )
				{
					$path = securit($_POST['avatar']);
					$error = $upload->validate_img($path, $CONFIG_MEMBER['width_max'], $CONFIG_MEMBER['height_max'], DELETE_ON_ERROR);
					if( !empty($error) ) //Erreur, on arrête ici
					{
						header('Location:' . HOST . DIR . '/member/register' . transid('.php?erroru=' . $error) . '#errorh');
						exit;						
					}
					else
						$user_avatar = $path; //Avatar posté et validé.
				}
				
				$admin_sign = $CONFIG['sign'];
						
				$check_user = $sql->query("SELECT COUNT(*) as compt FROM ".PREFIX."member WHERE login = '" . $login . "'", __LINE__, __FILE__);
				$check_mail = $sql->query("SELECT COUNT(*) as compt FROM ".PREFIX."member WHERE user_mail = '" . $user_mail . "'", __LINE__, __FILE__);
			
				if( $check_user >= 1 ) 
				{
					header('Location:' . HOST . DIR . '/member/register' . transid('.php?error=pseudo_auth') . '#errorh');
					exit;
				}
				elseif( $check_mail >= 1 ) 
				{
					header('Location:' . HOST . DIR . '/member/register' . transid('.php?error=mail_auth') . '#errorh');
					exit;
				}
				else //Succes.
				{
					$user_aprob = ($CONFIG_MEMBER['activ_mbr'] == 0) ? 1 : 0;
					$activ_mbr = ($CONFIG_MEMBER['activ_mbr'] == 1) ? substr(md5(uniqid(rand(), true)), 0, 15) : ''; //Génération de la clée d'activation!
					
					//Suppression des images des stats concernant les membres, si l'info à été modifiée.
					@unlink('../cache/sex.png');
					@unlink('../cache/theme.png');
					
					$sql->query_inject("INSERT INTO ".PREFIX."member (login,password,level,user_groups,user_lang,user_theme,user_mail,user_show_mail,timestamp,user_avatar,user_msg,user_local,user_msn,user_yahoo,user_web,user_occupation,user_hobbies,user_desc,user_sex,user_born,user_sign,user_pm,user_warning,last_connect,test_connect,activ_pass,new_pass,user_ban,user_aprob) 
					VALUES ('" . $login . "', '" . $password_md5 . "', 0, '0', '" . $user_lang . "', '" . $user_theme . "', '" . $user_mail . "', '" . $user_show_mail . "', '" . time() . "', '" . $user_avatar . "', 0, '" . $user_local . "', '" . $user_msn . "', '" . $user_yahoo . "', '" . $user_web . "', '" . $user_occupation . "', '" . $user_hobbies . "', '" . parse($user_desc) . "', '" . $user_sex . "', '" . $user_born . "', '" . parse($user_sign) . "', 0, 0, '" . time() . "', 0, '" . $activ_mbr . "', '', 0, '" . $user_aprob . "')", __LINE__, __FILE__); //Compte membre
					
					$last_mbr_id = $sql->sql_insert_id("SELECT MAX(id) FROM ".PREFIX."member"); //Dernier message inseré, on met à jour le topic.
						
					//Champs supplémentaires.
					$extend_field_exist = $sql->query("SELECT COUNT(*) FROM ".PREFIX."member_extend_cat WHERE display = 1", __LINE__, __FILE__);
					if( $extend_field_exist > 0 )
					{
						$req_update = '';
						$req_field = '';
						$req_insert = '';
						$result = $sql->query_while("SELECT field_name, field, possible_values, regex
						FROM ".PREFIX."member_extend_cat
						WHERE display = 1", __LINE__, __FILE__);
						while( $row = $sql->sql_fetch_assoc($result) )
						{
							$field = isset($_POST[$row['field_name']]) ? $_POST[$row['field_name']] : '';
							//Validation par expressions régulières.
							if( is_numeric($row['regex']) && $row['regex'] >= 1 && $row['regex'] <= 5)
							{
								$array_regex = array(
									1 => '`^[0-9]+$`',
									2 => '`^[a-z]+$`',
									3 => '`^[a-z0-9]+$`',
									4 => '`^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-zA-Z]{2,4}$`',
									5 => '`^http(s)?://[a-z0-9._/-]+\.[-[:alnum:]]+\.[a-zA-Z]{2,4}(.*)$`'
								);
								$row['regex'] = $array_regex[$row['regex']];
							}
							
							$valid_field = true;
							if( !empty($row['regex']) && $row['field'] <= 2 )
							{
								if( @preg_match($row['regex'], trim($field)) )
									$valid_field = true;
								else
									$valid_field = false;
							}
						
							if( $row['field'] == 2 )
								$field = parse($field);
							elseif( $row['field'] == 4 )
							{
								$array_field = is_array($field) ? $field : array();
								$field = '';
								foreach($array_field as $value)
									$field .= securit($value) . '|';
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
								$field = securit($field);
								
							if( !empty($field) )
							{
								if( $valid_field ) //Validation par expression régulière si présente.
								{
									$req_update .= $row['field_name'] . ' = \'' . trim($field, '|') . '\', ';
									$req_field .= $row['field_name'] . ', ';
									$req_insert .= '\'' . trim($field, '|') . '\', ';
								}
							}
						}
						$sql->close($result);	
											
						$check_member = $sql->query("SELECT COUNT(*) FROM ".PREFIX."member_extend WHERE user_id = '" . $last_mbr_id . "'", __LINE__, __FILE__);
						if( $check_member )
						{	
							if( !empty($req_update) )
								$sql->query_inject("UPDATE ".PREFIX."member_extend SET " . trim($req_update, ', ') . " WHERE user_id = '" . $last_mbr_id . "'", __LINE__, __FILE__); 
						}
						else
						{	
							if( !empty($req_insert) )
								$sql->query_inject("INSERT INTO ".PREFIX."member_extend (user_id, " . trim($req_field, ', ') . ") VALUES ('" . $last_mbr_id . "', " . trim($req_insert, ', ') . ")", __LINE__, __FILE__);
						}
					}
					
					//On régénère le cache
					$cache->generate_file('stats');
					
					//Ajout du lien de confirmation par mail si activé et activation par admin désactivé.
					if( $CONFIG_MEMBER['activ_mbr'] == 1 )
					{	
						$l_register_confirm = $LANG['confirm_register'] . '<br />' . $LANG['register_valid_email_confirm'];
						$valid = sprintf($LANG['register_valid_email'], HOST . DIR . '/member/register.php?key=' . $activ_mbr);
					}
					elseif( $CONFIG_MEMBER['activ_mbr'] == 2 )							
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
					
					include_once('../includes/mail.class.php');
					$mail = new Mail();
					
					$mail->send_mail($user_mail, sprintf(addslashes($LANG['register_title_mail']), $CONFIG['site_name']), sprintf(addslashes($LANG['register_mail']), $login, $CONFIG['site_name'], $CONFIG['site_name'], stripslashes($login), $password, $valid), $CONFIG['mail']);
					
					//On connecte le membre directement si aucune activation demandée.
					if( $CONFIG_MEMBER['activ_mbr'] == 0 )
					{
						$sql->query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "' WHERE user_id = '" . $last_mbr_id . "'", __LINE__, __FILE__); //Remise à zéro du compteur d'essais.
						$session->session_begin($last_mbr_id, $password_md5, 0, SCRIPT, QUERY_STRING, TITLE, 1); //On lance la session.
					}
					unset($password, $password_md5);
					
					//Affichage de la confirmation d'inscription.
					$URL_ERROR = get_start_page();
					$L_ERROR = sprintf($l_register_confirm, stripslashes($login));
					$DELAY_REDIRECT = 7;
					include('../includes/confirm.php');
				}
			}
			elseif( !empty($_POST['register_valid']) && $password !== $password_bis )
			{
				header('Location:' . HOST . DIR . '/member/register' . transid('.php?error=pass_same') . '#errorh');
				exit;
			}
			else
			{				
				header('Location:' . HOST . DIR . '/member/register' . transid('.php?error=incomplete') . '#errorh');
				exit;
			
			}
		}
		else
		{
			header('Location:' . HOST . DIR . '/member/register' . transid('.php?error=lenght_mini') . '#errorh');
			exit;
		
		}
	}
	else
	{
		header('Location:' . HOST . DIR . '/member/register' . transid('.php?error=verif_code') . '#errorh');
		exit;
	}
}	
elseif( !empty($user_mail) )
{
	header('Location:' . HOST . DIR . '/member/register' . transid('.php?error=invalid_mail') . '#errorh');
	exit;
}
else
{
	header('Location: ' . get_start_page());
	exit;
}
	
include('../includes/footer.php'); 

?>