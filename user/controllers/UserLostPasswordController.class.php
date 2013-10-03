<?php
/*##################################################
 *                       UserLostPasswordController.class.php
 *                            -------------------
 *   begin                : July 25, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU, Kevin MASSY
 *   email                : daaxwizeman@gmail.com, kevin.massy@phpboost.com
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

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();
		
		if($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->send_email();
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
	
	private function send_email()
	{
		$change_password_pass = KeyGenerator::generate_key(15);
		$user = $this->get_user();

		UserLostPasswordService::update_change_password_pass($change_password_pass, $user->get_email());
		
		$general_config = GeneralConfig::load();
		$parameters = array(
				'pseudo' => $user->get_pseudo(),
				'host' => $general_config->get_site_url(),
				'change_password_link' => UserUrlBuilder::change_password($change_password_pass)->absolute(),
				'signature' => MailServiceConfig::load()->get_mail_signature()
		);
		$subject = $general_config->get_site_name() . ' : ' . $this->lang['forget-password'];
		$content = StringVars::replace_vars($this->lang['forget-password.mail.content'], $parameters);
		UserLostPasswordService::send_mail($user->get_email(), $subject, $content);
		
		$this->tpl->put('MSG', MessageHelper::display($this->lang['forget-password.success'], MessageHelper::SUCCESS));
	}
	
	private function get_user()
	{
		switch ($this->form->get_value('field_choice')->get_raw_value()) 
		{
			case UserLostPasswordService::LOST_PASSWORD_BY_EMAIL:
				try {
				return UserService::get_user('WHERE user_mail=:email', array('email' => $this->form->get_value('information')));
				} catch (Exception $e) {
					$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), $this->lang['forget-password.error'], MessageHelper::NOTICE);
					DispatchManager::redirect($controller);
				}
			break;
			case UserLostPasswordService::LOST_PASSWORD_BY_LOGIN:
				try {
					return UserService::get_user('WHERE login=:login', array('login' => $this->form->get_value('information')));
				} catch (Exception $e) {
					$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), $this->lang['forget-password.error'], MessageHelper::NOTICE);
					DispatchManager::redirect($controller);
				}
			break;
		}
	}
	
	public function get_right_controller_regarding_authorizations()
	{
		if (AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
		{
			AppContext::get_response()->redirect(Environment::get_home_page());
		}
		return $this;
	}
}
?>