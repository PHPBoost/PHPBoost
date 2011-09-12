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
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	public function execute(HTTPRequest $request)
	{
		if (AppContext::get_user()->check_level(MEMBER_LEVEL))
		{
			AppContext::get_response()->redirect(Environment::get_home_page());
		}

		$this->activation_key = $request->get_getstring('key','');

		$this->init();

		$this->tpl->put('FORM', $this->build_form()->display());

		$this->validate_form();
		
		return $this->build_response($this->tpl);
	}

	private function init()
	{
		$this->tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');

		$this->lang = LangLoader::get('main');
		$this->error_lang = LangLoader::get('errors');

		$this->tpl->add_lang($this->lang);
	}
	
	private function build_form()
	{
		if (!empty($this->activation_key))
		{
			return $this->change_password_build_form();
		}
		else
		{
			return $this->send_activation_key_build_form();
		}
	}
	
	private function validate_form()
	{
		if (!empty($this->activation_key))
		{
			if($this->submit_button->has_been_submited() && $this->form->validate())
			{
				if ($this->check_activ_pass_exist())
				{
					$user_id = $this->get_user_id();
					$new_password = $this->form->get_value('new_password');
					if (!empty($new_password))
					{
						MemberUpdateProfileHelper::change_password(KeyGenerator::string_hash($new_password), $user_id);
						$this->clear_activation_key($user_id);
						$this->connect_user($user_id, $new_password);
						$this->tpl->put('MSG', MessageHelper::display($this->error_lang['e_forget_confirm_change'], E_USER_SUCCESS));
					}
				}
				else
				{
					$this->tpl->put('MSG', MessageHelper::display($this->error_lang['e_forget_echec_change'], E_USER_NOTICE));
				}
			}
		}
		else 
		{
			if($this->submit_button->has_been_submited() && $this->form->validate())
			{
				if($this->check_member_exist())
				{
					$activ_pass = KeyGenerator::generate_key(15);
					$this->save_activ_pass($activ_pass);
					$this->send_activation_key_mail($activ_pass);
					 
					$this->tpl->put('MSG', MessageHelper::display($this->error_lang['e_forget_mail_send'], E_USER_SUCCESS));
				}
				else
				{
					$this->tpl->put('MSG', MessageHelper::display($this->error_lang['e_mail_forget'], E_USER_NOTICE));
				}
			}
		}
	}

	private function send_activation_key_build_form()
	{
		$form = new HTMLForm('send_activation_key');
		$fieldset = new FormFieldsetHTML('fieldset', $this->lang['forget_pass']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldLabel($this->lang['forget_pass_send']));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('field_choice', 'Sélectionnez le champ que vous voulez renseigner (mail ou pseudo)',
			'1', 
			array(
				new FormFieldSelectChoiceOption($this->lang['mail'], '1'), 
				new FormFieldSelectChoiceOption($this->lang['pseudo'], '2')
			)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('information', $this->lang['pseudo'] .' / '.$this->lang['mail'], '', array(
			'class' => 'text', 'description' => '', 'required' => true)
		));
		
		$this->submit_button = new FormButtonSubmit($this->lang['submit'], 'forget_password');
		$form->add_button($this->submit_button);
			
		$this->form = $form;
		return $this->form;
	}

	private function change_password_build_form()
	{
		$form = new HTMLForm('change_password_form');
		$fieldset = new FormFieldsetHTML('fieldset', $this->lang['change_password']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field($password = new FormFieldPasswordEditor('new_password', $this->lang['new_password'], '', array(
			'class' => 'text', 'maxlength' => 25, 'description' => $this->lang['password_how'], 'required' => true),
			array(new FormFieldConstraintLength(6))
		));
			
		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('new_password_bis', $this->lang['confirm_password'], '', array(
			'class' => 'text', 'maxlength' => 25, 'description' => $this->lang['password_how'], 'required' => true),
			array(new FormFieldConstraintLength(6))
		));
			
		$this->submit_button = new FormButtonSubmit($this->lang['submit'], 'change_password');
		$form->add_button($this->submit_button);
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));
			
		$this->form = $form;
		return $this->form;
	}

	private function check_activ_pass_exist()
	{
		return (bool)PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, "WHERE activ_pass = :activ_pass",
		array('activ_pass' => $this->activation_key));
	}

	private function check_member_exist()
	{
		if($this->form->get_value('field_choice')->get_raw_value() == 1)
		{
			return (bool)PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, "WHERE user_mail = :mail",
				array('mail' => $this->form->get_value('information')
			));
		}
		elseif($this->form->get_value('field_choice')->get_raw_value() == 2)
		{
			return (bool)PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, "WHERE login = :login",
				array('login' => $this->form->get_value('information')
			));
		}
		else 
		{
			return false;
		}
	}

	private function get_user_id()
	{
		$member = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('user_id'),
        				"WHERE activ_pass = :activ_pass", array('activ_pass' => $this->activation_key));
		return $member['user_id'];
	}

	private function save_activ_pass($activ_pass)
	{
		if($this->form->get_value('field_choice')->get_raw_value() == 1)
		{
			PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('activ_pass' => $activ_pass),
				"WHERE user_mail = :mail", array('mail' => $this->form->get_value('information')));
		}
		else 
		{	
			PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('activ_pass' => $activ_pass),
				"WHERE login = :login", array('login' => $this->form->get_value('information')));
		}
	}

	private function send_activation_key_mail($activ_pass)
	{
		if($this->form->get_value('field_choice')->get_raw_value() == 1)
		{
			$sql_member = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('user_mail'),
				"WHERE user_mail = :mail", array('mail' => $this->form->get_value('information')));
			$user_mail = $sql_member['user_mail'];
		}
		else 
		{
			$sql_member = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('login'),
				"WHERE login = :login", array('login' => $this->form->get_value('information')));
			$user_mail = $sql_member['user_mail'];
		}
		
		$subject = $this->lang['forget_pass'] . ' - ' . GeneralConfig::load()->get_site_name();
		$content = StringVars::replace_vars($this->lang['forget_mail_pass'], array('login' => $user_login, 'host' => HOST,
			'host_dir' => (HOST . DIR), 'key' => $activ_pass, 'signature' => MailServiceConfig::load()->get_mail_signature()));

		$this->send_mail($user_mail, $subject, $content);
	}

	private function send_mail($mail, $subject, $content)
	{
		$mail = new Mail();
		$mail->add_recipient($mail);
		$mail->set_sender(MailServiceConfig::load()->get_default_mail_sender(), GeneralConfig::load()->get_site_name());
		$mail->set_subject($subject);
		$mail->set_content($content);
		AppContext::get_mail_service()->try_to_send($mail);
	}

	private function clear_activation_key($user_id)
	{
		PersistenceContext::get_querier()->inject("UPDATE " . DB_TABLE_MEMBER . " SET activ_pass = :activ_pass  WHERE user_id = :user_id",
		array('activ_pass' => 0, 'user_id' => $user_id));
	}

	private function connect_user($user_id, $password)
	{
		AppContext::get_session()->start($user_id, 
		$password, 0, SCRIPT, QUERY_STRING, '', true);
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