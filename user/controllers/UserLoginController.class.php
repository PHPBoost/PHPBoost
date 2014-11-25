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

	private $view;
	private $lang;
	private $request;
	private $form;
	private $fieldset;
	private $submit_button;

	public function __construct($login_type = self::USER_LOGIN)
	{
		$this->login_type = $login_type;
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
			'C_USER_LOGIN' => $this->login_type == self::USER_LOGIN,
			'C_ADMIN_LOGIN' => $this->login_type == self::ADMIN_LOGIN,
			'U_REGISTER' => UserUrlBuilder::registration()->rel(),
			'U_FORGET_PASSWORD' => UserUrlBuilder::forget_password()->rel(),
			'L_FORGET_PASSWORD' => $this->lang['forget-password'],
			'LOGIN_FORM' => $this->form->display(),
		));

		$maintain_config = MaintenanceConfig::load();
		if (MaintenanceConfig::load()->is_under_maintenance())
		{
			$this->init_maintain_delay($maintain_config);

			$this->view->put_all(array(
				'C_MAINTAIN' => true,
				'L_MAINTAIN' => FormatingHelper::second_parse($maintain_config->get_message()),
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
		
		if ($user_id)
		{
			AppContext::get_response()->redirect($this->get_redirect_url());
		}

		if ($authentication->has_error())
		{
			$error = new FormFieldLabel(MessageHelper::display($authentication->get_error_msg(), MessageHelper::NOTICE)->render());
			$this->fieldset->add_field($error);
		}
	}

	private function build_view()
	{
		if (MaintenanceConfig::load()->is_under_maintenance() || $this->login_type == self::ADMIN_LOGIN)
		{
			$response = new SiteDisplayFrameResponse($this->view);
			$graphical_environment = $response->get_graphical_environment();
			$graphical_environment->set_page_title($this->login_type == self::ADMIN_LOGIN ? LangLoader::get_message('administration', 'admin') : LangLoader::get_message('title_maintain', 'main'));
			return $response;
		}
		else
		{
			$response = new SiteDisplayResponse($this->view);
			$graphical_environment = $response->get_graphical_environment();
			$graphical_environment->set_page_title($this->lang['connect']);
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

		$this->fieldset = new FormFieldsetHTML('loginFieldset', $this->lang['connect']);
		$login = new FormFieldTextEditor('login', $this->lang['login'], '', array('description' => $this->lang['login.explain'], 'required' => true));
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

		if ($this->login_type == self::ADMIN_LOGIN)
		{
			 return DispatchManager::get_url('/admin/admin_index.php', '?redirect=' . urlencode($redirect_url));
		}
		else
		{
		}	return DispatchManager::get_url('/user/index.php', '/login?redirect=' . urlencode($redirect_url));
	}

	private function get_redirect_url()
	{
		$redirect = $this->request->get_value('redirect', '/');
		$redirect = $redirect !== '/' ? HOST . $redirect : $redirect;
		return new Url($redirect);
	}

	private function init_maintain_delay($maintain_config)
	{
		$date_lang = LangLoader::get('date-common');
		$array_time = array(0 => '-1', 1 => '0', 2 => '60', 3 => '300', 4 => '900', 5 => '1800', 6 => '3600', 7 => '7200', 8 => '86400', 9 => '172800', 10 => '604800'); 
		$array_delay = array(0 => LangLoader::get_message('unspecified', 'main'), 1 => '', 2 => '1 ' . $date_lang['minute'], 3 => '5 ' . $date_lang['minutes'], 4 => '15 ' . $date_lang['minutes'], 5 => '30 ' . $date_lang['minutes'], 6 => '1 ' . $date_lang['hour'], 7 => '2 ' . $date_lang['hours'], 8 => '1 ' . $date_lang['day'], 9 => '2 ' . $date_lang['days'], 10 => '1 ' . $date_lang['week']);
		
		if (!$maintain_config->is_unlimited_maintenance())
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
			$seconds = gmdate_format('s', $end_timestamp, Timezone::SITE_TIMEZONE);
			$array_release = array(
			gmdate_format('Y', $end_timestamp, Timezone::SITE_TIMEZONE), (gmdate_format('n', $end_timestamp, Timezone::SITE_TIMEZONE) - 1), gmdate_format('j', $end_timestamp, Timezone::SITE_TIMEZONE), 
			gmdate_format('G', $end_timestamp, Timezone::SITE_TIMEZONE), gmdate_format('i', $end_timestamp, Timezone::SITE_TIMEZONE), ($seconds < 10) ? trim($seconds, 0) : $seconds );
		
			$seconds = gmdate_format('s', time(), Timezone::SITE_TIMEZONE);
		    $array_now = array(
		    gmdate_format('Y', time(), Timezone::SITE_TIMEZONE), (gmdate_format('n', time(), Timezone::SITE_TIMEZONE) - 1), gmdate_format('j', time(), Timezone::SITE_TIMEZONE),
		    gmdate_format('G', time(), Timezone::SITE_TIMEZONE), gmdate_format('i', time(), Timezone::SITE_TIMEZONE), ($seconds < 10) ? trim($seconds, 0) : $seconds);
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
		
		if ($maintain_config->get_display_duration() && !$maintain_config->is_unlimited_maintenance())
		{
			$this->view->put_all(array(
				'C_DISPLAY_DELAY' => true,
				'DELAY' => isset($array_delay[$key + 1]) ? $array_delay[$key + 1] : '0'
			));
		}
	}
}
?>