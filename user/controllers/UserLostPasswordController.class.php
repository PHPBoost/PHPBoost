<?php
/*##################################################
 *                       UserLostPasswordController.class.php
 *                            -------------------
 *   begin                : July 25, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU, Kévin MASSY
 *   email                : daaxwizeman@gmail.com, soldier.weasel@gmail.com
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

class UserLostPasswordController extends AbstractController
{
	private $lang;
	private $tpl;
	private $form;
	private $submit_button;

	public function execute(HTTPRequest $request)
	{
		$this->init();

		$this->build_form();
		
		if($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->send_mail();
		}
		
		$this->tpl->put('FORM', $this->form->display());
		
		return $this->build_response($this->tpl);
	}

	private function init()
	{
		$this->tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$this->lang = LangLoader::get('user-common');
		$this->tpl->add_lang($this->lang);
	}
	
	private function build_form()
	{
		$form = new HTMLForm('send_activation_key');
		$fieldset = new FormFieldsetHTML('fieldset', $this->lang['forget-password']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('field_choice', $this->lang['forget-password.select'],
			UserLostPasswordService::LOST_PASSWORD_BY_EMAIL, 
			array(
				new FormFieldSelectChoiceOption($this->lang['email'], UserLostPasswordService::LOST_PASSWORD_BY_EMAIL), 
				new FormFieldSelectChoiceOption($this->lang['pseudo'], UserLostPasswordService::LOST_PASSWORD_BY_LOGIN)
			)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('information', $this->lang['pseudo'] .' / '.$this->lang['email'], '', array(
			'class' => 'text', 'required' => true)
		));
			
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
			
		$this->form = $form;
	}
	
	private function build_response(View $view)
	{
		$response = new UserDisplayResponse();
		$response->set_page_title($this->lang['forget-password']);
		$response->add_breadcrumb($this->lang['user'], UserUrlBuilder::users()->absolute());
		$response->add_breadcrumb($this->lang['forget-password'], UserUrlBuilder::forget_password()->absolute());
		return $response->display($view);
	}
	
	private function send_mail()
	{
		switch ($this->form->get_value('field_choice')->get_raw_value()) 
		{
			case UserLostPasswordService::LOST_PASSWORD_BY_EMAIL:
				$email = $this->form->get_value('information');
			break;
			case UserLostPasswordService::LOST_PASSWORD_BY_LOGIN:
				$login = $this->form->get_value('information');
				if (UserService::user_exists_by_login($login))
				{
					$email = UserLostPasswordService::get_email_by_login($login);
				}
				else
				{
					$email = false;
				}
			break;
		}
		
		if ($email !== false && UserService::user_exists_by_email($email))
		{
			$change_password_pass = KeyGenerator::generate_key(15);
			UserLostPasswordService::register_change_password_pass($change_password_pass, $email);
			$subject = GeneralConfig::load()->get_site_name() . ' : ' . $this->lang['forget-password'];
			$parameters = array(
					'pseudo' => UserLostPasswordService::get_pseudo_by_email($email),
					'change_password_link' => UserUrlBuilder::change_password($change_password_pass)->absolute(),
					'signature' => MailServiceConfig::load()->get_mail_signature()
			);
			$content = StringVars::replace_vars($this->lang['forget-password.mail.content'], $parameters);
			UserLostPasswordService::send_mail($email, $subject, $content);
			
			$this->tpl->put('MSG', MessageHelper::display($this->lang['forget-password.success'], MessageHelper::SUCCESS));
		}
		else
		{
			$this->tpl->put('MSG', MessageHelper::display($this->lang['forget-password.error'], MessageHelper::NOTICE));
		}
	}
	
	public function get_right_controller_regarding_authorizations()
	{
		if (AppContext::get_current_user()->check_level(MEMBER_LEVEL))
		{
			AppContext::get_response()->redirect(Environment::get_home_page());
		}
		return $this;
	}
}
?>