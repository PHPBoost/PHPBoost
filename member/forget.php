<?php
/*##################################################
 *                                forget.php
 *                            -------------------
 *   begin                : August 08 2005
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../kernel/begin.php'); 
define('TITLE', $LANG['title_forget']);
require_once('../kernel/header.php'); 

$activ_confirm = retrieve(GET, 'activate', false);
$activ_get = retrieve(GET, 'activ', '');
$user_get = retrieve(GET, 'u', 0);
$forget = retrieve(POST, 'forget', '');

if (!$User->check_level(MEMBER_LEVEL))
{
	if (!$activ_confirm)
	{	
		$Template->set_filenames(array(
			'forget'=> 'member/forget.tpl'
		));
			
		if (!empty($forget))
		{
			$user_mail = retrieve(POST, 'mail', '');
			$login = retrieve(POST, 'name', '');

			if (!empty($user_mail) && check_mail($user_mail))
			{	
				$user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE user_mail = '" . $user_mail . "' AND login = '" . $login . "'", __LINE__, __FILE__);
				if (!empty($user_id)) //Succés mail trouvé, en crée un nouveau mdp, et la clée d'activ et on l'envoi au membre
				{
					$new_pass = substr(strhash(uniqid(rand(), true)), 0, 6); //Génération du nouveau mot de pass unique!
					$activ_pass =  substr(strhash(uniqid(rand(), true)), 0, 30); //Génération de la clée d'activation!
					
					$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET activ_pass = '" . $activ_pass . "', new_pass = '" . strhash($new_pass) . "' WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__); //Insertion de la clée d'activation dans la bdd.
					
					AppContext::get_mail_service()->send_from_properties($user_mail, $LANG['forget_mail_activ_pass'], sprintf($LANG['forget_mail_pass'], $login, HOST, (HOST . DIR), $user_id, $activ_pass, $new_pass, MailServiceConfig::load()->get_mail_signature()));

					//Affichage de la confirmation.
					AppContext::get_response()->redirect('/member/forget.php?error=forget_mail_send');
				}
				else
					$Template->put('message_helper', MessageHelper::display($LANG['e_mail_forget'], E_USER_NOTICE));
			}
			else
				$Template->put('message_helper', MessageHelper::display($LANG['e_incomplete'], E_USER_NOTICE));
		}
		
		$get_error = retrieve(GET, 'error', '', TSTRING_UNCHANGE);			
		$errno = E_USER_NOTICE;
		switch ($get_error)
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
		if (!empty($errstr))
			$Template->put('message_helper', MessageHelper::display($errstr, $errno));			
	
		$Template->put_all(array(
			'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
			'L_REQUIRE_MAIL' => $LANG['require_mail'],
			'L_REQUIRE' => $LANG['require'],
			'L_NEW_PASS' => $LANG['forget_pass'],
			'L_PSEUDO' => $LANG['pseudo'],
			'L_MAIL' => $LANG['mail'],
			'L_NEW_PASS_FORGET' => $LANG['forget_pass_send'],
			'L_SUBMIT' => $LANG['submit']
		));
		
		$Template->pparse('forget');
	}
	elseif (!empty($activ_get) && !empty($user_get) && $activ_confirm)
	{
		$user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $user_get . "' AND activ_pass = '" . $activ_get . "'", __LINE__, __FILE__);
		if (!empty($user_id))
		{
			//Mise é jour du nouveau password.
			$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET password = new_pass WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
			
			//Effacement des clées d'activations.
			$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET activ_pass = '', new_pass = '' WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
			
			//Affichage de la confirmation de réussite.
			AppContext::get_response()->redirect('/member/error.php?e=e_forget_confirm_change');
		}
		else //Affichage de l'echec.
			AppContext::get_response()->redirect('/member/forget.php?error=forget_echec_change');
	}	
	else //Affichage de l'echec.
		AppContext::get_response()->redirect('/member/forget.php?error=forget_echec_change');
}
else
	AppContext::get_response()->redirect(Environment::get_home_page());

require_once('../kernel/footer.php'); 

?>