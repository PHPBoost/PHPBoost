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
		
		if ($this->request->get_bool('disconnect', false))
		{
			AppContext::get_session()->csrf_get_protect();
			$session = AppContext::get_session();
			Session::delete($session);
			
			AppContext::get_response()->redirect($this->request->get_value('redirect', '/'));
		}
		
		if (AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
		{
			AppContext::get_response()->redirect(Environment::get_home_page());
		}

		$authenticate_type = $this->request->get_value('authenticate', false);
		if ($authenticate_type)
		{
			if ($authenticate_type == PHPBoostAuthenticationMethod::AUTHENTICATION_METHOD)
			{
				$login = $this->request->get_value('login', '');
				$password = $this->request->get_value('password', '');
				$autoconnect = $this->request->get_bool('autoconnect', false);
				$this->phpboost_authenticate($login, $password, $autoconnect);
			}
			else
			{
				$authentication = AuthenticationService::get_authentication_method($authenticate_type);
				$this->authenticate($authentication, true);
			}
		}
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$login = $this->form->get_value('login');
			$password = $this->form->get_value('password');
			$autoconnect = $this->form->get_value('autoconnect');
			$this->phpboost_authenticate($login, $password, $autoconnect);
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

	private function phpboost_authenticate($login, $password, $autoconnect)
	{
		$authentication = new PHPBoostAuthenticationMethod($login, $password);
		$this->authenticate($authentication, $autoconnect);
	}

	private function authenticate(AuthenticationMethod $authentication, $autoconnect)
	{
		$user_id = AuthenticationService::authenticate($authentication, $autoconnect);
		
		if ($user_id)
		{
			AppContext::get_response()->redirect($this->request->get_value('redirect', '/'));
		}

		if ($authentication->has_error())
		{
			$error = new FormFieldLabel(MessageHelper::display($authentication->get_error_msg(), MessageHelper::NOTICE)->render());
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
	
	private function build_target()
	{
		$redirect_url = $this->request->get_value('redirect', '/');
		return DispatchManager::get_url('/user/index.php', '/login?redirect=' . urlencode($redirect_url));
	}
}
?>