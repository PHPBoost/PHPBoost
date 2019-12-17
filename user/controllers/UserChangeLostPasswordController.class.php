<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 07 30
 * @since       PHPBoost 3.0 - 2011 10 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class UserChangeLostPasswordController extends AbstractController
{
	private $lang;
	private $tpl;
	private $form;
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$change_password_pass = $request->get_getstring('key','');
		$user_id = $change_password_pass ? PHPBoostAuthenticationMethod::change_password_pass_exists($change_password_pass) : 0;
		
		if (!$user_id)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		$this->build_form($user_id);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->change_password($user_id, $this->form->get_value('password'));
		}

		$this->tpl->put('FORM', $this->form->display());

		return $this->build_response($this->tpl, $change_password_pass);
	}

	private function init()
	{
		$this->tpl = new StringTemplate('# INCLUDE ERROR_MESSAGE ## INCLUDE FORM #');
		$this->lang = LangLoader::get('user-common');
		$this->tpl->add_lang($this->lang);
	}

	private function build_form($user_id)
	{
		$security_config = SecurityConfig::load();
		$user = UserService::get_user($user_id);
		$internal_auth_infos = PHPBoostAuthenticationMethod::get_auth_infos($user_id);
		
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('fieldset', $this->lang['change-password']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['password.new'], '',
			array('description' => StringVars::replace_vars($this->lang['password.explain'], array('number' => $security_config->get_internal_password_min_length())), 'required' => true, 'autocomplete' => false),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()), new FormFieldConstraintPasswordStrength())
		));

		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['password.confirm'], '',
			array('required' => true, 'autocomplete' => false),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()), new FormFieldConstraintPasswordStrength())
		));

		$fieldset->add_field($email = new FormFieldHidden('email', $user->get_email()));
		$fieldset->add_field($login = new FormFieldHidden('login', $internal_auth_infos['login'] && $user->get_email() !== $internal_auth_infos['login'] ? $internal_auth_infos['login'] : preg_replace('/\s+/u', '', $user->get_display_name())));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));
		
		if ($security_config->are_login_and_email_forbidden_in_password())
		{
			$form->add_constraint(new FormConstraintFieldsNotIncluded($email, $password, LangLoader::get_message('form.login_and_mail_must_not_be_contained_in_second_field', 'status-messages-common')));
			$form->add_constraint(new FormConstraintFieldsNotIncluded($login, $password, LangLoader::get_message('form.login_and_mail_must_not_be_contained_in_second_field', 'status-messages-common')));
		}
		
		$this->form = $form;
	}

	private function change_password($user_id, $password)
	{
		$maintain_config = MaintenanceConfig::load();

		PHPBoostAuthenticationMethod::update_auth_infos($user_id, null, null, KeyGenerator::string_hash($password), null, '');

		$auth_infos = array();
		try {
			$auth_infos = PHPBoostAuthenticationMethod::get_auth_infos($user_id);
		} catch (RowNotFoundException $e) {
		}

		if (!empty($auth_infos) && $auth_infos['login'])
		{
			$authentication = new PHPBoostAuthenticationMethod($auth_infos['login'], $password);
			$user_id = AuthenticationService::authenticate($authentication);

			$current_user = CurrentUser::from_session();

			if ($user_id && $maintain_config->is_under_maintenance() && !$current_user->check_auth($maintain_config->get_auth(), MaintenanceConfig::ACCESS_WHEN_MAINTAIN_ENABLED_AUTHORIZATIONS))
			{
				$session = AppContext::get_session();
				Session::delete($session);
				$this->tpl->put('ERROR_MESSAGE', MessageHelper::display(LangLoader::get_message('user.not_authorized_during_maintain', 'status-messages-common'), MessageHelper::NOTICE));
			}
			else
			{
				if ($user_id)
				{
					AppContext::get_response()->redirect(Environment::get_home_page());
				}
				if ($authentication->has_error())
				{
					$session = AppContext::get_session();
					Session::delete($session);

					$this->tpl->put('ERROR_MESSAGE', MessageHelper::display($authentication->get_error_msg(), MessageHelper::NOTICE));
				}
			}
		}
		else
		{
			$session = AppContext::get_session();
			Session::delete($session);

			AppContext::get_response()->redirect(Environment::get_home_page());
		}
	}

	private function build_response(View $view, $key)
	{
		$response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['change-password'], $this->lang['user']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['user'], UserUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['change-password'], UserUrlBuilder::change_password($key)->rel());

		return $response;
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
