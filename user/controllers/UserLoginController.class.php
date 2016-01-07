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
	const USER_LOGIN = 1;
	const ADMIN_LOGIN = 2;

	private $login_type;
	private $redirect;

	private $view;
	private $lang;
	private $request;
	private $form;
	private $fieldset;
	private $submit_button;

	private $has_error;
	private $maintain_config;
	
	public function __construct($login_type = self::USER_LOGIN, $redirect = '')
	{
		$this->login_type = $login_type;
		if (!empty($redirect))
			$this->redirect = $redirect;
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->init($request);
		$this->build_form();
		
		if ($this->request->get_bool('disconnect', false))
		{
			AppContext::get_session()->csrf_get_protect();
			$session = AppContext::get_session();
			Session::delete($session);
			
			AppContext::get_response()->redirect($this->get_redirect_url());
		}
		
		if (AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
		{
			if (!$this->maintain_config->is_under_maintenance() || ($this->maintain_config->is_under_maintenance() && $this->maintain_config->is_authorized_in_maintenance()))
			{
				if ($this->request->get_value('redirect', '') || $this->redirect !== null)
					AppContext::get_response()->redirect($this->get_redirect_url());
				else
					AppContext::get_response()->redirect(Environment::get_home_page());
			}
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
				try {
					$authentication = AuthenticationService::get_authentication_method($authenticate_type);
				} catch (Exception $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
				
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
		$this->maintain_config = MaintenanceConfig::load();
	}
	
	private function init_vars_template()
	{
		$authentication_config = AuthenticationConfig::load();
		
		$this->view->put_all(array(
			'C_REGISTRATION_ENABLED' => UserAccountsConfig::load()->is_registration_enabled(),
			'C_FB_AUTH_ENABLED' => $authentication_config->is_fb_auth_available(),
			'C_GOOGLE_AUTH_ENABLED' => $authentication_config->is_google_auth_available(),
			'C_USER_LOGIN' => $this->login_type == self::USER_LOGIN && !$this->maintain_config->is_under_maintenance(),
			'C_ADMIN_LOGIN' => $this->login_type == self::ADMIN_LOGIN,
			'C_HAS_ERROR' => $this->has_error,
			'U_REGISTER' => UserUrlBuilder::registration()->rel(),
			'U_FORGET_PASSWORD' => UserUrlBuilder::forget_password()->rel(),
			'L_FORGET_PASSWORD' => $this->lang['forget-password'],
			'LOGIN_FORM' => $this->form->display(),
		));
		
		if ($this->maintain_config->is_under_maintenance())
		{
			$this->init_maintain_delay();

			$this->view->put_all(array(
				'C_MAINTAIN' => true,
				'L_MAINTAIN' => FormatingHelper::second_parse($this->maintain_config->get_message()),
			));
		}
	}

	private function phpboost_authenticate($login, $password, $autoconnect)
	{
		$authentication = new PHPBoostAuthenticationMethod($login, $password);
		$this->authenticate($authentication, $autoconnect);
	}

	private function authenticate(AuthenticationMethod $authentication, $autoconnect)
	{
		$user_id = AuthenticationService::authenticate($authentication, $autoconnect);
		
		$current_user = CurrentUser::from_session();
		
		if ($user_id && $this->maintain_config->is_under_maintenance() && !$current_user->check_auth($this->maintain_config->get_auth(), MaintenanceConfig::ACCESS_WHEN_MAINTAIN_ENABLED_AUTHORIZATIONS))
		{
			$session = AppContext::get_session();
			Session::delete($session);
			$this->view->put('ERROR_MESSAGE', MessageHelper::display(LangLoader::get_message('user.not_authorized_during_maintain', 'status-messages-common'), MessageHelper::NOTICE));
			$this->has_error = true;
		}
		else
		{
			if ($user_id)
			{
				AppContext::get_response()->redirect($this->get_redirect_url());
			}

			if ($authentication->has_error())
			{
				$this->view->put('ERROR_MESSAGE', MessageHelper::display($authentication->get_error_msg(), MessageHelper::NOTICE));
				$this->has_error = true;
			}
		}
	}

	private function build_view()
	{
		if ($this->maintain_config->is_under_maintenance() || $this->login_type == self::ADMIN_LOGIN)
		{
			$response = new SiteDisplayFrameResponse($this->view);
			$graphical_environment = $response->get_graphical_environment();
			$graphical_environment->set_page_title(($this->login_type == self::ADMIN_LOGIN ? LangLoader::get_message('administration', 'admin') : LangLoader::get_message('title_maintain', 'main')));
			$graphical_environment->display_css_login();
			return $response;
		}
		else
		{
			$response = new SiteDisplayResponse($this->view);
			$graphical_environment = $response->get_graphical_environment();
			$graphical_environment->set_page_title($this->lang['connection'], $this->lang['user']);
			return $response;
		}
	}
	
	/**
	 * @return HTMLForm
	 */
	private function build_form()
	{
		$this->form = new HTMLForm('loginForm', $this->build_target(), false);
		$this->form->set_css_class('fieldset-content');

		$this->fieldset = new FormFieldsetHTML('loginFieldset', $this->lang['connection']);
		$this->form->add_fieldset($this->fieldset);
		
		$this->fieldset->add_field(new FormFieldTextEditor('login', $this->lang['login'], '',
			array('description' => $this->lang['login.explain'], 'required' => true)
		));
		
		$this->fieldset->add_field(new FormFieldPasswordEditor('password', $this->lang['password'], '',
			array('required' => true)
		));
		
		$this->fieldset->add_field(new FormFieldCheckbox('autoconnect', $this->lang['autoconnect'], true));
		
		$this->submit_button = new FormButtonSubmit($this->lang['connection'], 'authenticate');
		$this->form->add_button($this->submit_button);
	}
	
	private function build_target()
	{
		$redirect_url = $this->request->get_value('redirect', '/');
		$redirect = $redirect_url !== '/' ? '?redirect=' . str_replace('%2F', '/', urlencode($redirect_url)) : '';

		if ($this->login_type == self::ADMIN_LOGIN)
		{
			if ($this->redirect !== null && $this->redirect)
				return DispatchManager::get_url($this->redirect, $redirect);
			else
				return DispatchManager::get_url('/admin/admin_index.php', $redirect);
		}
		return DispatchManager::get_url('/user/index.php', '/login' . $redirect);
	}

	private function get_redirect_url()
	{
		if ($this->redirect !== null && $this->redirect)
			return new Url($this->redirect);
		else
			return new Url($this->request->get_value('redirect', '/'));
	}

	private function init_maintain_delay()
	{
		$date_lang = LangLoader::get('date-common');
		$array_time = array(0 => '-1', 1 => '0', 2 => '60', 3 => '300', 4 => '900', 5 => '1800', 6 => '3600', 7 => '7200', 8 => '86400', 9 => '172800', 10 => '604800'); 
		$array_delay = array(0 => LangLoader::get_message('unspecified', 'main'), 1 => '', 2 => '1 ' . $date_lang['minute'], 3 => '5 ' . $date_lang['minutes'], 4 => '15 ' . $date_lang['minutes'], 5 => '30 ' . $date_lang['minutes'], 6 => '1 ' . $date_lang['hour'], 7 => '2 ' . $date_lang['hours'], 8 => '1 ' . $date_lang['day'], 9 => '2 ' . $date_lang['days'], 10 => '1 ' . $date_lang['week']);
		
		if (!$this->maintain_config->is_unlimited_maintenance())
		{
			$key = 0;
			$current_time = time();
			$end_timestamp = $this->maintain_config->get_end_date()->get_timestamp();
			for ($i = 10; $i >= 0; $i--)
			{
				$delay = ($end_timestamp - $current_time) - $array_time[$i];
				if ($delay >= $array_time[$i]) 
				{
					$key = $i;
					break;
				}
			}
			
			//Calcul du format de la date
			$array_release = array(Date::to_format($end_timestamp, 'Y', Timezone::SITE_TIMEZONE), (Date::to_format($end_timestamp, 'n', Timezone::SITE_TIMEZONE) - 1), Date::to_format($end_timestamp, 'j', Timezone::SITE_TIMEZONE), Date::to_format($end_timestamp, 'G', Timezone::SITE_TIMEZONE), Date::to_format($end_timestamp, 'i', Timezone::SITE_TIMEZONE), Date::to_format($end_timestamp, 's', Timezone::SITE_TIMEZONE));
		
			$array_now = array(Date::to_format(time(), 'Y', Timezone::SITE_TIMEZONE), (Date::to_format(time(), 'n', Timezone::SITE_TIMEZONE) - 1), Date::to_format(time(), 'j', Timezone::SITE_TIMEZONE), Date::to_format(time(), 'G', Timezone::SITE_TIMEZONE), Date::to_format(time(), 'i', Timezone::SITE_TIMEZONE), Date::to_format(time(), 's', Timezone::SITE_TIMEZONE));
		}
		else
		{
			$key = -1;
			$array_release = array('0', '0', '0', '0', '0', '0');
			$array_now = array('0', '0', '0', '0', '0', '0');
		}
		
		$this->view->put_all(array(
			'MAINTAIN_NOW_FORMAT' => implode(',', $array_now),
			'MAINTAIN_RELEASE_FORMAT' => implode(',', $array_release)
		));
		
		if ($this->maintain_config->get_display_duration() && !$this->maintain_config->is_unlimited_maintenance())
		{
			$this->view->put_all(array(
				'C_DISPLAY_DELAY' => true,
				'DELAY' => isset($array_delay[$key + 1]) ? $array_delay[$key + 1] : '0'
			));
		}
	}
}
?>
