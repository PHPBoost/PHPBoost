<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 11
 * @since       PHPBoost 3.0 - 2011 10 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UserRegistrationController extends AbstractController
{
	private $view;

	private $lang;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	private $user_accounts_config;

	private $member_extended_fields_service;

	public function execute(HTTPRequestCustom $request)
	{
		$this->get_right_controller_regarding_authorizations();
		$this->init();
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
		}

		$this->view->put('FORM', $this->form->display());
		return $this->build_response($this->view);
	}

	private function init()
	{
		$this->lang = LangLoader::get_all_langs();
		$this->view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE FORM #');
		$this->user_accounts_config = UserAccountsConfig::load();
	}

	private function build_form()
	{
		$security_config = SecurityConfig::load();
		$form = new HTMLForm(__CLASS__);
		$form->set_layout_title($this->lang['user.registration']);
		$this->member_extended_fields_service = new MemberExtendedFieldsService($form);

		$fieldset = new FormFieldsetHTML('registration', $this->lang['form.parameters']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldHTML('validation_method', $this->get_accounts_validation_method_explain()));

		$fieldset->add_field($display_name = new FormFieldTextEditor('display_name', $this->lang['user.display.name'], '',
			array(
				'maxlength' => 100, 'required' => true,
				'description'=> $this->lang['user.display.name.clue'],
				'events' => array('blur' => '
					if (!HTMLForms.getField("login").getValue() && HTMLForms.getField("display_name").validate() == "") {
						HTMLForms.getField("login").setValue(HTMLForms.getField("display_name").getValue().replace(/\s/g, \'\'));
					}'
				)
			),
			array(new FormFieldConstraintLengthRange(3, 100), new FormFieldConstraintDisplayNameExists())
		));

		$fieldset->add_field($email = new FormFieldMailEditor('email', $this->lang['user.email'], '',
			array('required' => true),
			array(new FormFieldConstraintMailExist())
		));

		$fieldset->add_field(new FormFieldCheckbox('user_hide_mail', $this->lang['user.email.hide'], FormFieldCheckbox::CHECKED));

		$fieldset->add_field($custom_login_checked = new FormFieldCheckbox('custom_login', $this->lang['user.username.custom'], false,
			array(
				'description'=> $this->lang['user.username.custom.clue'],
				'events' => array('click' => '
					if (HTMLForms.getField("custom_login").getValue()) {
						HTMLForms.getField("login").enable();
					} else {
						HTMLForms.getField("login").disable();
					}'
				)
			)
		));

		$fieldset->add_field($login = new FormFieldTextEditor('login', $this->lang['user.username'], '',
			array('hidden' => true, 'maxlength' => 25),
			array(new FormFieldConstraintLengthRange(3, 25), new FormFieldConstraintPHPBoostAuthLoginExists())
		));

		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['user.password'], '',
			array(
				'required' => true, 'autocomplete' => false,
				'description' => StringVars::replace_vars($this->lang['user.password.clue'], array('number' => $security_config->get_internal_password_min_length()))
			),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()), new FormFieldConstraintPasswordStrength())
		));

		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['user.password.confirm'], '',
			array('required' => true, 'autocomplete' => false),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()), new FormFieldConstraintPasswordStrength())
		));

		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));

		if ($security_config->are_login_and_email_forbidden_in_password())
		{
			$form->add_constraint(new FormConstraintFieldsNotIncluded($email, $password));
			$form->add_constraint(new FormConstraintFieldsNotIncluded($custom_login_checked == 'on' ? $login : $display_name, $password));
		}

		$options_fieldset = new FormFieldsetHTML('options', $this->lang['common.options']);
		$form->add_fieldset($options_fieldset);

		$options_fieldset->add_field(new FormFieldTimezone('timezone', $this->lang['user.timezone.choice'], GeneralConfig::load()->get_site_timezone(),
			array('description' => $this->lang['user.timezone.choice.clue'])
		));

		if (count(ThemesManager::get_activated_and_authorized_themes_map()) > 1)
		{
			$options_fieldset->add_field(new FormFieldThemesSelect('theme', $this->lang['user.theme'], $this->user_accounts_config->get_default_theme(),
				array(
					'check_authorizations' => true,
					'events' => array('change' => $this->build_javascript_picture_themes())
				)
			));

			$options_fieldset->add_field(new FormFieldFree('preview_theme', $this->lang['user.theme.preview'], '<img id="img_theme" src="'. $this->get_picture_theme() .'" alt="' . $this->lang['user.theme.preview'] . '" class="preview-img" />'));
		}

		$options_fieldset->add_field(new FormFieldEditors('text-editor', $this->lang['user.text.editor'], ContentFormattingConfig::load()->get_default_editor()));

		$options_fieldset->add_field(new FormFieldLangsSelect('lang', $this->lang['user.lang'], $this->user_accounts_config->get_default_lang(),
			array('check_authorizations' => true)
		));

		$this->member_extended_fields_service->display_form_fields();

		$agreement_text = FormatingHelper::second_parse($this->user_accounts_config->get_registration_agreement());
		if (!empty($agreement_text))
		{
			$agreement_fieldset = new FormFieldsetHTML('agreement_fieldset', $this->lang['user.agreement']);
			$form->add_fieldset($agreement_fieldset);

			$agreement = new FormFieldHTML('agreement.required', $this->lang['user.agreement.agree.required'] . '<br /><br />');
			$agreement_fieldset->add_field($agreement);

			$agreement = new FormFieldHTML('agreement', '<div id="id-message-helper" class="message-helper bgc notice user-agreement">' . $agreement_text . '</div>');
			$agreement_fieldset->add_field($agreement);

			$agreement_fieldset->add_field(new FormFieldCheckbox('agree', $this->lang['user.agreement.agree'], FormFieldCheckbox::UNCHECKED,
				array('required' => $this->lang['user.agreement.agree.required'])
			));
		}

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function get_accounts_validation_method_explain()
	{
		if ($this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::MAIL_USER_ACCOUNTS_VALIDATION)
		{
			return '<div id="registration-validation-mail" class="message-helper bgc notice">'. $this->lang['user.registration.validation.email.clue'] . '</div>';
		}
		elseif ($this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION)
		{
			return '<div id="registration-validation-admin" class="message-helper bgc notice">'. $this->lang['user.registration.validation.administrator.clue'] . '</div>';
		}
		else
		{
			return '';
		}
	}

	private function save()
	{
		$has_error = false;

		$registration_pass = $this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::MAIL_USER_ACCOUNTS_VALIDATION ? KeyGenerator::generate_key(15) : '';
		$user_aprobation = $this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::AUTOMATIC_USER_ACCOUNTS_VALIDATION;

		$user = new User();
		$user->set_display_name($this->form->get_value('display_name'));
		$user->set_level(User::MEMBER_LEVEL);
		$user->set_email($this->form->get_value('email'));
		$user->set_show_email(!$this->form->get_value('user_hide_mail'));
		$user->set_locale($this->form->get_value('lang')->get_raw_value());
		$user->set_editor($this->form->get_value('text-editor')->get_raw_value());
		$user->set_timezone($this->form->get_value('timezone')->get_raw_value());

		if ($this->form->has_field('theme'))
		{
			$user->set_theme($this->form->get_value('theme')->get_raw_value());
		}

		$login = $this->form->get_value('email');
		if ($this->form->get_value('custom_login'))
		{
			$login = $this->form->get_value('login');
		}

		$auth_method = new PHPBoostAuthenticationMethod($login, $this->form->get_value('password'));
		$auth_method->set_association_parameters($user_aprobation, $registration_pass);

		try {
			$user_id = UserService::create($user, $auth_method, $this->member_extended_fields_service);
		} catch (MemberExtendedFieldErrorsMessageException $e) {
			$has_error = true;
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($e->getMessage(), MessageHelper::NOTICE));
		}

		if (!$has_error && $user_id)
		{
			HooksService::execute_hook_action('user_registration', 'user', array_merge($user->get_properties(), array('title' => $user->get_display_name(), 'url' => UserUrlBuilder::profile($user_id)->rel())));
			UserRegistrationService::send_email_confirmation($user_id, $user->get_email(), $this->form->get_value('display_name'), $login, $this->form->get_value('password'), $registration_pass);

			$this->confirm_registration($user_id);
		}
	}

	private function confirm_registration($user_id)
	{
		if ($this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::MAIL_USER_ACCOUNTS_VALIDATION)
		{
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['user.registration.success.email.validation'], MessageHelper::SUCCESS));
		}
		elseif ($this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION)
		{
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['user.registration.success.administrator.validation'], MessageHelper::SUCCESS));
		}
		else
		{
			$session = AppContext::get_session();
			if ($session != null)
			{
				Session::delete($session);
			}
			AppContext::set_session(Session::create($user_id, true));

			AppContext::get_response()->redirect(Environment::get_home_page());
		}
	}

	private function build_response(View $view)
	{
		$title = $this->lang['user.registration'];
		$response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($title, $this->lang['user.user']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['user.seo.registration']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(UserUrlBuilder::registration());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['user.user'], UserUrlBuilder::home()->rel());
		$breadcrumb->add($title, UserUrlBuilder::registration()->rel());

		return $response;
	}

	public function get_right_controller_regarding_authorizations()
	{
		if (!UserAccountsConfig::load()->is_registration_enabled() || AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
		{
			AppContext::get_response()->redirect(Environment::get_home_page());
		}
		return $this;
	}

	private function build_javascript_picture_themes()
	{
		$text = 'var theme = new Array;' . "\n";
		foreach (ThemesManager::get_activated_themes_map() as $theme)
		{
			$text .= 'theme["' . $theme->get_id() . '"] = "' . Url::to_rel($this->get_picture_theme($theme->get_id())) . '";' . "\n";
		}
		$text .= 'var theme_id = HTMLForms.getField("theme").getValue(); document.images[\'img_theme\'].src = theme[theme_id];';
		return $text;
	}

	private function get_picture_theme()
	{
		$theme_id = $this->user_accounts_config->get_default_theme();
		$picture = ThemesManager::get_theme($theme_id)->get_configuration()->get_first_picture();
		return TPL_PATH_TO_ROOT .'/templates/' . $theme_id . '/' . $picture;
	}
}
?>
