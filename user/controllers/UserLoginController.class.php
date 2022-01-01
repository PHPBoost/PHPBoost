<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2012 04 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

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

		$authenticate_type = $this->request->get_value('authenticate', false);
		$was_already_authenticated = $authenticate_type && $authenticate_type != PHPBoostAuthenticationMethod::AUTHENTICATION_METHOD && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL);

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
				if (!AuthenticationService::external_auth_is_activated($authenticate_type))
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}

				$this->authenticate(AuthenticationService::get_external_auth_activated($authenticate_type)->get_authentication(), true);
			}
		}

		if (AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
		{
			if (!$this->maintain_config->is_under_maintenance() || ($this->maintain_config->is_under_maintenance() && $this->maintain_config->is_authorized_in_maintenance()))
			{
				if ($this->login_type == self::ADMIN_LOGIN && !AppContext::get_current_user()->is_admin())
					AppContext::get_response()->redirect(Environment::get_home_page());
				else if ($this->request->get_value('redirect', '') || $this->redirect !== null)
					AppContext::get_response()->redirect($this->get_redirect_url());
				else if ($was_already_authenticated)
					AppContext::get_response()->redirect(UserUrlBuilder::edit_profile(AppContext::get_current_user()->get_id())->rel());
				else
					AppContext::get_response()->redirect(Environment::get_home_page());
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
		$this->lang = LangLoader::get_all_langs();
		$this->view->add_lang($this->lang);
		$this->maintain_config = MaintenanceConfig::load();
	}

	private function init_vars_template()
	{
		$external_authentication = 0;

		foreach (AuthenticationService::get_external_auths_activated() as $id => $authentication)
		{
			$this->view->assign_block_vars('external_auth', array(
				'U_CONNECT'  => UserUrlBuilder::connect($id)->rel(),
				'ID'         => $id,
				'NAME'       => $authentication->get_authentication_name(),
				'IMAGE_HTML' => $authentication->get_image_renderer_html(),
				'CSS_CLASS'  => $authentication->get_css_class()
			));
			$external_authentication++;
		}

		$header_logo_path = '';
		$theme = ThemesManager::get_theme(AppContext::get_current_user()->get_theme());

		if ($theme)
		{
			$customize_interface = $theme->get_customize_interface();
			$header_logo_path = $customize_interface->get_header_logo_path();
		}

		$this->view->put_all(array(
			'C_REGISTRATION_ENABLED' => UserAccountsConfig::load()->is_registration_enabled(),
			'C_DISPLAY_EXTERNAL_AUTHENTICATION' => $external_authentication,
			'C_USER_LOGIN'         => $this->login_type == self::USER_LOGIN && !$this->maintain_config->is_under_maintenance(),
			'C_ADMIN_LOGIN'        => $this->login_type == self::ADMIN_LOGIN,
			'C_HAS_ERROR'          => $this->has_error,
			'SITE_NAME'            => GeneralConfig::load()->get_site_name(),
			'SITE_SLOGAN'          => GeneralConfig::load()->get_site_slogan(),
			'C_HEADER_LOGO'        => !empty($header_logo_path),
			'HEADER_LOGO'          => Url::to_rel($header_logo_path),
			'U_REGISTER'           => UserUrlBuilder::registration()->rel(),
			'U_FORGOTTEN_PASSWORD' => UserUrlBuilder::forget_password()->rel(),
			'LOGIN_FORM'           => $this->form->display(),
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
			$this->view->put('ERROR_MESSAGE', MessageHelper::display($this->lang['warning.user.not.authorized.during.maintenance'], MessageHelper::NOTICE));
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
			$graphical_environment->set_page_title(($this->login_type == self::ADMIN_LOGIN ? $this->lang['admin.administration'] : $this->lang['admin.maintenance']));
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['user.seo.login']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(UserUrlBuilder::connect());
			$graphical_environment->display_css_login();
			return $response;
		}
		else
		{
			$response = new SiteDisplayResponse($this->view);
			$graphical_environment = $response->get_graphical_environment();
			$graphical_environment->set_page_title($this->lang['user.sign.in'], $this->lang['user.user']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['user.seo.login']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(UserUrlBuilder::connect());
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

		$this->fieldset = new FormFieldsetHTML('loginFieldset', $this->lang['form.parameters']);
		$this->form->add_fieldset($this->fieldset);

		$this->fieldset->add_field(new FormFieldTextEditor('login', $this->lang['user.username'], '',
			array('description' => $this->lang['user.username.tooltip'], 'required' => true)
		));

		$this->fieldset->add_field(new FormFieldPasswordEditor('password', $this->lang['user.password'], '',
			array('required' => true)
		));

		$this->fieldset->add_field(new FormFieldCheckbox('autoconnect', $this->lang['user.auto.connect'], true));

		$this->submit_button = new FormButtonSubmit($this->lang['user.sign.in'], 'authenticate');
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
		$dispatch = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '/' : '/user';
		return DispatchManager::get_url($dispatch, '/login/' . $redirect);
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
		$array_time = array(0 => '-1', 1 => '0', 2 => '60', 3 => '300', 4 => '900', 5 => '1800', 6 => '3600', 7 => '7200', 8 => '86400', 9 => '172800', 10 => '604800');
		$array_delay = array(0 => $this->lang['common.unspecified'], 1 => '', 2 => '1 ' . $this->lang['date.minute'], 3 => '5 ' . $this->lang['date.minutes'], 4 => '15 ' . $this->lang['date.minutes'], 5 => '30 ' . $this->lang['date.minutes'], 6 => '1 ' . $this->lang['date.hour'], 7 => '2 ' . $this->lang['date.hours'], 8 => '1 ' . $this->lang['date.day'], 9 => '2 ' . $this->lang['date.days'], 10 => '1 ' . $this->lang['date.week']);

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

			// Calculating date format
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
				'DELAY'           => isset($array_delay[$key + 1]) ? $array_delay[$key + 1] : '0'
			));
		}
	}
}
?>
