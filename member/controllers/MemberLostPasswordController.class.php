<?php
/*##################################################
 *                       MemberLostPasswordController.class.php
 *                            -------------------
 *   begin                : July 25, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

class MemberLostPasswordController extends AbstractController
{
	private $tpl;
	private $lang;
	private $error_lang;
	private $activation_key;
	/**
	 * @var HTMLForm
	 */
	private $send_activation_key_form;
	private $change_password_form;
	/**
	 * @var FormButtonSubmit
	 */
	private $send_activation_key_submit_button;
	private $change_password_submit_button;
	
	public function execute(HTTPRequest $request)
	{			
		if (AppContext::get_user()->check_level(MEMBER_LEVEL)) 
		{
			AppContext::get_response()->redirect(Environment::get_home_page());
		}
		
		$this->activation_key = $request->get_getstring('key','');
		
		$this->init();
		
		if (!empty($this->activation_key))
		{
			$this->change_password_build_form();
			
			if($this->change_password_submit_button->has_been_submited() && $this->change_password_form->validate())
			{
        		//Vérification de l'existence de la clé d'activation
				$member = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('user_id'), 
        			"WHERE activ_pass = :activ_pass", array('activ_pass' => $this->activation_key));
				
				if(!empty($member['user_id']))
        		{
					//La clé existe ainsi que le membre, on met à jour le mot de passe
        			$password_changed = $this->change_password($member['user_id']);
        			
        			//Affiche la confirmation
					$this->tpl->put('MSG', MessageHelper::display($this->error_lang['e_forget_confirm_change'], E_USER_SUCCESS));
					
					if ($password_changed == true)
					{
						//Efface la clé d'activation de la bdd
						$this->clear_activation_key($member['user_id']);
					}
					
        		}
        		else 
        		{
        			$this->tpl->put('MSG', MessageHelper::display($this->error_lang['e_forget_echec_change'], E_USER_NOTICE));
        		}
        	}
        	
        	$this->tpl->put('FORM', $this->change_password_form->display());
		}
		else 
		{
			$this->send_activation_key_build_form();
			
			if($this->send_activation_key_submit_button->has_been_submited() && $this->send_activation_key_form->validate())
			{
				//Vérification de l'existence du membre
				$member_exist = (bool)PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, "WHERE user_mail = :mail AND login = :login", 
					array('mail' => $this->send_activation_key_form->get_value('mail'), 
        			'login' => $this->send_activation_key_form->get_value('login')));
        		
        		if($member_exist == true)
        		{
        			//Génération de la clé d'activation
        			$activ_pass = KeyGenerator::generate_key(15); 
					
					//Insertion de la clée d'activation dans la bdd
					PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('activ_pass' => $activ_pass), 
						"WHERE login = :login", array('login' => $this->send_activation_key_form->get_value('login')));
        			
        			//Envoi de la clé d'activation par mail
        			$this->send_activation_key_mail($activ_pass);
        			
        			//Affichage de la confirmation.
					$this->tpl->put('MSG', MessageHelper::display($this->error_lang['e_forget_mail_send'], E_USER_SUCCESS));
        		}
        		else 
				{
					$this->tpl->put('MSG', MessageHelper::display($this->error_lang['e_mail_forget'], E_USER_NOTICE));
				}
			}
			
			$this->tpl->put('FORM', $this->send_activation_key_form->display());
		}
		
		return $this->build_response($this->tpl);
	}
	
	private function init()
	{
		$this->tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		
		$this->lang = LangLoader::get('main');
		$this->error_lang = LangLoader::get('errors');
		
		$this->tpl->add_lang($this->lang);
	}
	
	private function send_activation_key_build_form()
	{
		//On génère le formulaire dans le cas du mot de passe oublié (donc aucune clé d'activation)
		$form = new HTMLForm('send_activation_key');
		$fieldset = new FormFieldsetHTML('fieldset', $this->lang['forget_pass']);
			
		$fieldset->add_field(new FormFieldLabel($this->lang['forget_pass_send']));
		$fieldset->add_field(new FormFieldTextEditor('login', $this->lang['pseudo'], '', array(
			'class' => 'text', 'description' => '', 'required' => true)
		));
		$fieldset->add_field(new FormFieldMailEditor('mail', $this->lang['mail'], '', array(
			'class' => 'text', 'description' => '', 'required' => true)
		));
			
		$form->add_fieldset($fieldset);
			
		$this->send_activation_key_submit_button = new FormButtonSubmit($this->lang['submit'], 'forget_password');
		$form->add_button($this->send_activation_key_submit_button);
			
		$this->send_activation_key_form = $form;
	}
	
	private function change_password_build_form()
	{
		//On génère le formulaire de changement de mot de passe si la clé d'activation existe
		$form = new HTMLForm('change_password_form');
		$fieldset = new FormFieldsetHTML('fieldset', $this->lang['change_password']);
			
		$fieldset->add_field($password = new FormFieldPasswordEditor('new_password', $this->lang['new_password'], '', array(
			'class' => 'text', 'maxlength' => 25, 'description' => $this->lang['password_how'], 'required' => true),
			array(new FormFieldConstraintLength(6))
		));
			
		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('new_password_bis', $this->lang['confirm_password'], '', array(
			'class' => 'text', 'maxlength' => 25, 'description' => $this->lang['password_how'], 'required' => true),
			array(new FormFieldConstraintLength(6))
		));
			
		$form->add_fieldset($fieldset);
			
		$this->change_password_submit_button = new FormButtonSubmit($this->lang['submit'], 'change_password');	
		$form->add_button($this->change_password_submit_button);
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));
			
		$this->change_password_form = $form;
	}
	
	private function send_activation_key_mail($activ_pass)
	{
		//Envoi de la clé d'activation par mail
		$subject = $this->lang['forget_pass'] . ' - ' . GeneralConfig::load()->get_site_name();
		$content = StringVars::replace_vars($this->lang['forget_mail_pass'], array('login' => $this->send_activation_key_form->get_value('login'), 'host' => HOST,
			'host_dir' => (HOST . DIR), 'key' => $activ_pass, 'signature' => MailServiceConfig::load()->get_mail_signature()));
		
		$mail = new Mail();
        $mail->add_recipient($this->send_activation_key_form->get_value('mail'), $this->send_activation_key_form->get_value('login'));
        $mail->set_sender(MailServiceConfig::load()->get_default_mail_sender(), GeneralConfig::load()->get_site_name());
        $mail->set_subject($subject);
        $mail->set_content($content);
        AppContext::get_mail_service()->try_to_send($mail);
	}
	
	private function change_password($user_id) 
	{
		$new_password = $this->change_password_form->get_value('new_password');
		
		if (!empty($new_password))
        {
        	MemberUpdateProfileHelper::change_password(KeyGenerator::string_hash($new_password), $user_id);
        	return $success = true;
        }
	}
	
	private function clear_activation_key($user_id)
	{
		PersistenceContext::get_querier()->inject("UPDATE " . DB_TABLE_MEMBER . " SET activ_pass = :activ_pass  WHERE user_id = :user_id",
			array('activ_pass' => 0, 'user_id' => $user_id 
		));
	}
	private function build_response(View $view)
    {
    	$response = new SiteDisplayResponse($view);
        $env = $response->get_graphical_environment();
        $env->set_page_title($this->lang['forget_pass']);
        return $response;
    }
}

?>
