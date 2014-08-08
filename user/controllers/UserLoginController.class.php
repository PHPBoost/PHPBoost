<?php
/*##################################################
 *                           UserLoginController.class.php
 *                            -------------------
 *   begin                : April 05, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class UserLoginController extends AbstractController
{
	private $view;
	private $lang;
	private $request;
	private $form;
	private $fieldset;
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init($request);
		$this->build_form();
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$username = $this->form->get_value('login');
			$password = $this->form->get_value('password');
			$autoconnect = $this->form->get_value('autoconnect');
			$this->authenticate($username, $password, $autoconnect);
		}
		
		$this->init_vars_template();
		
		return $this->build_view();
	}
	
	private function init(HTTPRequestCustom $request)
	{
		$this->request = $request;
		$this->view = new FileTemplate('user/UserLoginController.tpl');
		$this->lang = LangLoader::get('user-common');
		$this->view->add_lang($this->lang);
	}
	
	private function init_vars_template()
	{
		$this->view->put_all(array(
			'C_REGISTRATION_ENABLED' => UserAccountsConfig::load()->is_registration_enabled(),
			'U_REGISTER' => UserUrlBuilder::registration()->rel(),
			'U_FORGET_PASSWORD' => UserUrlBuilder::forget_password()->rel(),
			'L_FORGET_PASSWORD' => $this->lang['forget-password'],
			'LOGIN_FORM' => $this->form->display(),
		));
	}

	private function authenticate($username, $password, $autoconnect)
	{
		$authentication = new PHPBoostAuthenticationMethod($username, $password);
		if ($authentication->authenticate($autoconnect))
		{
			$session = AppContext::get_session();
			if ($session != null)
			{
				Session::delete($session);
			}
			AppContext::set_session(Session::create($authentication->get_user_id(), $autoconnect));
			AppContext::get_response()->redirect($this->request->get_value('redirect', '/'));
		}
		else
		{
			$this->build_error_message($authentication);
		}
	}
	
	private function build_error_message(PHPBoostAuthenticationMethod $authentication)
	{
		$errors_lang = LangLoader::get('errors');
		$error_msg = '';
		if (!$authentication->has_user_been_found())
		{
			$error_msg = $errors_lang['e_unexist_member'];
		}
		else
		{
			$remaining_attempts = $authentication->get_remaining_attemps();
			if ($remaining_attempts > 0)
			{
				$error_msg = StringVars::replace_vars($this->lang['flood_block'], array('remaining_tries' => $remaining_attempts));
			}
			else
			{
				$error_msg = $this->lang['flood_max'];
			}
		}
		
		if (!empty($error_msg))
		{
			$error = new FormFieldLabel(MessageHelper::display($error_msg, MessageHelper::NOTICE)->render());
			$this->fieldset->add_field($error);
		}
	}

	private function build_view()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['connect']);
		return $response;
	}
	
	/**
	 * @return HTMLForm
	 */
	private function build_form()
	{
		$this->form = new HTMLForm('loginForm', $this->build_target(), false);
		$this->form->set_css_class('fieldset-content');

		$this->fieldset = new FormFieldsetHTML('loginFieldset', $this->lang['connect']);
		$login = new FormFieldTextEditor('login', $this->lang['pseudo'], '', array('required' => true));
		$this->fieldset->add_field($login);
		$password = new FormFieldPasswordEditor('password', $this->lang['password'], '', array('required' => true));
		$this->fieldset->add_field($password);
		$autoconnect = new FormFieldCheckbox('autoconnect', $this->lang['autoconnect'], true);
		$this->fieldset->add_field($autoconnect);

		$this->form->add_fieldset($this->fieldset);

		$this->submit_button = new FormButtonSubmit($this->lang['connect'], 'authenticate');
		$this->form->add_button($this->submit_button);
	}
	
	public function get_right_controller_regarding_authorizations()
	{
		if (AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
		{
			AppContext::get_response()->redirect(Environment::get_home_page());
		}

		return $this;
	}
	
	private function build_target()
	{
		$redirect_url = $this->request->get_value('redirect', '/');
		return DispatchManager::get_url('/user/index.php', '/connect?redirect=' . urlencode($redirect_url));
	}
}
?>