<?php
/*##################################################
 *                               contact.php
 *                            -------------------
 *   begin                : July 29, 2005
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

require_once('../kernel/begin.php');
require_once('../contact/contact_begin.php');
require_once('../kernel/header.php'); 

$mail_from = retrieve(POST, 'mail_email', '', TSTRING_UNSECURE);
$mail_objet = retrieve(POST, 'mail_objet', '', TSTRING_UNSECURE);
$mail_contents = retrieve(POST, 'mail_contents', '', TSTRING_UNSECURE);
$mail_valid = retrieve(POST, 'mail_valid', '');
$get_verif_code = retrieve(POST, 'verif_code', '', TSTRING_UNSECURE);

###########################Envoi##############################
if( !empty($mail_valid) )
{
	$Template->Set_filenames(array(
		'contact'=> 'contact/contact.tpl'
	));	
		
	//Code de vérification si activé
	$check_verif_code = true;
	if( @extension_loaded('gd') && $CONFIG_CONTACT['contact_verifcode'] )
	{
		$user_id = substr(strhash(USER_IP), 0, 8);
		$verif_code = $Sql->Query("SELECT code FROM ".PREFIX."verif_code WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);	

		if( empty($verif_code) || ($verif_code != $get_verif_code) )
			$check_verif_code = false;
		else //On efface le code qui a été utilisé.
			$Sql->Query_inject("DELETE FROM ".PREFIX."verif_code WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);			
	}
	
	if( $check_verif_code || !$CONFIG_CONTACT['contact_verifcode'] ) //Code de vérification si activé
	{
		include_once('../kernel/framework/mail.class.php');
		$Mail = new Mail();

		if( $Mail->Send_mail($CONFIG['mail'], $mail_objet, $mail_contents, $mail_from, '', 'user') ) //Succès mail
			redirect(HOST . SCRIPT . transid('?error=success', '', '&') . '#errorh');
		else //Erreur mail
			redirect(HOST . SCRIPT . transid('?error=error', '', '&') . '#errorh');
	}
	else //Champs incomplet!
		redirect(HOST . SCRIPT . transid('?error=verif', '', '&') . '#errorh');
}
elseif( !empty($_POST['mail_valid']) && ( empty($mail_email) || empty($mail_contents) ) ) //Champs incomplet!
	redirect(HOST . SCRIPT . transid('?error=incomplete', '', '&') . '#errorh');
else
{	
	###########################Affichage##############################
	$Template->Set_filenames(array(
		'contact'=> 'contact/contact.tpl'
	));
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);
	elseif( $get_error == 'verif' )
		$Errorh->Error_handler($LANG['e_incorrect_verif_code'], E_USER_WARNING);
	elseif( $get_error == 'success' )//Message de succès.
		$Errorh->Error_handler($LANG['success_mail'], E_USER_SUCCESS);
	elseif( $get_error == 'error' )//Message de succès.
		$Errorh->Error_handler($LANG['error_mail'], E_USER_WARNING);
		
	//Code de vérification, anti-bots.
	if( @extension_loaded('gd') && $CONFIG_CONTACT['contact_verifcode'] )
	{
		$Template->Assign_vars(array(
			'L_REQUIRE_VERIF_CODE' => 'if(document.getElementById(\'verif_code\').value == "") {
				alert("' . $LANG['require_verif_code'] . '");
				return false;
			}'
		));		
		$Template->Assign_block_vars('verif_code', array(
		));
	}
		
	$Template->Assign_vars(array(
		'MAIL' => $Member->Get_attribute('user_mail'),
		'L_REQUIRE_MAIL' => $LANG['require_mail'],
		'L_REQUIRE_TEXT' => $LANG['require_text'] ,
		'L_CONTACT_MAIL' => $LANG['contact_mail'],
		'L_MAIL' => $LANG['mail'],
		'L_VERIF_CODE' => $LANG['verif_code'],
		'L_REQUIRE' => $LANG['require'],
		'L_VALID_MAIL' => $LANG['valid_mail'],
		'L_OBJET' => $LANG['objet'],
		'L_CONTENTS' => $LANG['contents'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset'],
		'U_ACTION_CONTACT' => SID
	));

	$Template->Pparse('contact'); 
}

require_once('../kernel/footer.php'); 

?>