<?php
/*##################################################
 *                                forget.php
 *                            -------------------
 *   begin                : August 08 2005
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

include_once('../includes/begin.php'); 
define('TITLE', $LANG['title_forget']);
include_once('../includes/header.php'); 

$activ_confirm = !empty($_GET['activate']) ? securit($_GET['activate']) : '';
$activ_get = !empty($_GET['activ']) ? securit($_GET['activ']) : '';
$user_get = !empty($_GET['u']) ? numeric($_GET['u']) : '';

if( !$session->check_auth($session->data, 0) )
{
	if( empty($activ_confirm) )
	{	
		$template->set_filenames(array(
			'forget' => '../templates/' . $CONFIG['theme'] . '/forget.tpl'
		));
			
		if( !empty($_POST['forget']))
		{
			$user_mail = !empty($_POST['mail']) ? securit($_POST['mail']) : '';
			$login = !empty($_POST['name']) ? clean_user($_POST['name']) : '';

			if( !empty($user_mail) && preg_match("!^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,6}$!", $user_mail) )
			{	
				$user_id = $sql->query("SELECT user_id FROM ".PREFIX."member WHERE user_mail = '" . $user_mail . "' AND login = '" . $login . "'", __LINE__, __FILE__);
				if( !empty($user_id) ) //Succès mail trouvé, en crée un nouveau mdp, et la clée d'activ et on l'envoi au membre
				{
					$new_pass = substr(md5(uniqid(rand(), true)), 0, 6); //Génération du nouveau mot de pass unique!
					$activ_pass =  substr(md5(uniqid(rand(), true)), 0, 30); //Génération de la clée d'activation!
					
					$sql->query_inject("UPDATE ".PREFIX."member SET activ_pass = '" . $activ_pass . "', new_pass = '" . md5($new_pass) . "' WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__); //Insertion de la clée d'activation dans la bdd.
					
					include_once('../includes/mail.class.php');
					$mail = new Mail();
					$mail->send_mail($user_mail, $LANG['forget_mail_activ_pass'], sprintf($LANG['forget_mail_pass'], $login, HOST, (HOST . DIR), $user_id, $activ_pass, $new_pass), $CONFIG['mail']);	

					//Affichage de la confirmation.
					header('Location: ' . HOST . DIR . '/member/forget.php?error=forget_mail_send');
					exit;
				}
				else
					$errorh->error_handler($LANG['e_mail_forget'], E_USER_NOTICE);
			}
			else
				$errorh->error_handler($LANG['e_incomplete'], E_USER_NOTICE);
		}
		
		$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';			
		$errno = E_USER_NOTICE;
		switch($get_error)
		{ 
			case 'forget_mail_send':
				$errstr = $LANG['e_forget_mail_send'];					
			break;
			case 'forget_echec_change':
				$errstr = $LANG['e_forget_echec_change'];					
				$errno = E_USER_WARNING;
			break;
			case 'forget_confirm_change':
				$errstr = $LANG['e_forget_confirm_change'];
			break;
			default:
			$errstr = '';
		}	
		if( !empty($errstr) )
			$errorh->error_handler($errstr, $errno);			
	
		$template->assign_vars(array(
			'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
			'L_REQUIRE_MAIL' => $LANG['require_mail'],
			'L_REQUIRE' => $LANG['require'],
			'L_NEW_PASS' => $LANG['forget_pass'],
			'L_PSEUDO' => $LANG['pseudo'],
			'L_MAIL' => $LANG['mail'],
			'L_NEW_PASS_FORGET' => $LANG['forget_pass_send'],
			'L_SUBMIT' => $LANG['submit']
		));
		
		$template->pparse('forget');
	}
	elseif( !empty($activ_get) && !empty($user_get) && $activ_confirm === 'true' )
	{
		$user_id = $sql->query("SELECT user_id FROM ".PREFIX."member WHERE user_id = '" . $user_get . "' AND activ_pass = '" . $activ_get . "'", __LINE__, __FILE__);
		if( !empty($user_id) )
		{
			//Mise à jour du nouveau password
			$sql->query_inject("UPDATE ".PREFIX."member SET password = new_pass WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
			
			//Effacement des clées d'activations.
			$sql->query_inject("UPDATE ".PREFIX."member SET activ_pass = '', new_pass = '' WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
			
			//Affichage de l'echec
			header('Location: ' . HOST . DIR . '/member/forget.php?error=forget_confirm_change');
			exit;
		}
		else
		{
			//Affichage de l'echec
			header('Location: ' . HOST . DIR . '/member/forget.php?error=forget_echec_change');
			exit;
		}
	}	
	else
	{
		//Affichage de l'echec
		header('Location: ' . HOST . DIR . '/member/forget.php?error=forget_echec_change');
		exit;	
	}
}
else
{
	header('location: ' . get_start_page());
	exit;
}

include_once('../includes/footer.php'); 

?>