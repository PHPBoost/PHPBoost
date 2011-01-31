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
define('TITLE', $LANG['forget_pass']);
require_once('../kernel/header.php'); 

$activ_get = retrieve(GET, 'activ', '');
$user_get = retrieve(GET, 'u', 0);

$tpl = new FileTemplate('member/forget.tpl');
		
if (!$User->check_level(MEMBER_LEVEL))
{
	if (!empty($activ_get) && !empty($user_get))
	{
		try 
		{
			$member = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('user_id'), 
				"WHERE user_id = :user_id AND activ_pass = :activ_pass", array('user_id' => $user_get, 'activ_pass' => $activ_get));
		} catch (RowNotFoundException $ex) {
			$tpl->put('message_helper', MessageHelper::display($LANG['e_forget_echec_change'], E_USER_NOTICE));
		}
		
		$form = new HTMLForm('changePasswordForm', '?activ=' . $activ_get . '&amp;u=' . $user_get);
		$fieldset = new FormFieldsetHTML('fieldset', $LANG['change_password']);
		
		$password = new FormFieldPasswordEditor('new_password', $LANG['new_password'], '', array(
			'class' => 'text', 'description' => $LANG['password_how'], 'required' => true),
			array(new FormFieldConstraintLength(6)));
		$fieldset->add_field($password);
		
		$password_bis = new FormFieldPasswordEditor('new_password_bis', $LANG['confirm_password'], '', array(
			'class' => 'text', 'description' => $LANG['password_how'], 'required' => true),
			array(new FormFieldConstraintLength(6)));
		$fieldset->add_field($password_bis);
		$form->add_fieldset($fieldset);
		
		$buttons_fieldset = new FormFieldsetSubmit('buttons', array('css_class' => 'fieldset_submit center'));
		$submit_button = new FormButtonSubmit($LANG['submit'], 'change_password', '');
		$buttons_fieldset->add_element($submit_button);
		$form->add_fieldset($buttons_fieldset);
		
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));
		
		if ($submit_button->has_been_submited() && $form->validate())
		{
			//Mise à jour du nouveau password.
			PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('password' => strhash($form->get_value('new_password')), 'activ_pass' => ''), 
				"WHERE user_id = :user_id", array('user_id' => $member['user_id']));
			
			//Affichage de la confirmation.
			$tpl->put('message_helper', MessageHelper::display($LANG['e_forget_confirm_change'], E_USER_SUCCESS));
		} else {
			$tpl->put('forget_password_form', $form->display());
		}
	}	
	else 
	{	
		$form = new HTMLForm('changePasswordForm');
		$fieldset = new FormFieldsetHTML('fieldset', $LANG['forget_pass']);
		
		$fieldset->add_field(new FormFieldLabel($LANG['forget_pass_send']));
		$fieldset->add_field(new FormFieldTextEditor('login', $LANG['pseudo'], '', array(
			'class' => 'text', 'description' => '', 'required' => true)
		));
		$fieldset->add_field(new FormFieldMailEditor('mail', $LANG['mail'], '', array(
			'class' => 'text', 'description' => '', 'required' => true)
		));
		$form->add_fieldset($fieldset);
		$buttons_fieldset = new FormFieldsetSubmit('buttons', array('css_class' => 'fieldset_submit center'));
		$submit_button = new FormButtonSubmit($LANG['submit'], 'forget_password', '');
		$buttons_fieldset->add_element($submit_button);
		$form->add_fieldset($buttons_fieldset);
		
		if ($submit_button->has_been_submited() && $form->validate()) 
		{
			//Succés membre trouvé, on envoie la clée pour changer le password par mail au membre
			try 
			{
				$member = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('user_id'), 
					"WHERE user_mail = :mail AND login = :login", array('mail' => $form->get_value('mail'), 'login' => $form->get_value('login')));
				
				$activ_pass = substr(strhash(uniqid(rand(), true)), 0, 30); //Génération de la clée d'activation!
				PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('activ_pass' => $activ_pass), 
					"WHERE user_id = :user_id", array('user_id' => $member['user_id'])); //Insertion de la clée d'activation dans la bdd.
			
				AppContext::get_mail_service()->send_from_properties($form->get_value('mail'), $LANG['change_password'], sprintf($LANG['forget_mail_pass'], $form->get_value('login'), HOST, (HOST . DIR), $member['user_id'], $activ_pass, MailServiceConfig::load()->get_mail_signature()));

				//Affichage de la confirmation.
				$tpl->put('message_helper', MessageHelper::display($LANG['e_forget_mail_send'], E_USER_SUCCESS));
			} catch (RowNotFoundException $ex) {
				$tpl->put('message_helper', MessageHelper::display($LANG['e_mail_forget'], E_USER_NOTICE));
			}
		} else {
			$tpl->put('forget_password_form', $form->display());
		}
	}
}
else
{
	AppContext::get_response()->redirect(Environment::get_home_page());
}

$tpl->display();
	
require_once('../kernel/footer.php'); 

?>