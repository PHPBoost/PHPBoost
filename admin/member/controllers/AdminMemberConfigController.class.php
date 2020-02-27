<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 27
 * @since       PHPBoost 3.0 - 2010 12 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminMemberConfigController extends AdminController
{
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
	private $security_config;
	private $authentication_config;
	private $server_configuration;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();

			$this->form->get_field_by_id('type_activation_members')->set_hidden(!$this->user_accounts_config->is_registration_enabled());
			$this->form->get_field_by_id('unactivated_accounts_timeout')->set_hidden(!$this->user_accounts_config->is_registration_enabled() || $this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION);

			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminMembersDisplayResponse($tpl, $this->lang['members.members-management']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-user-common');
		$this->user_accounts_config = UserAccountsConfig::load();
		$this->security_config = SecurityConfig::load();
		$this->server_configuration = new ServerConfiguration();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('members_config', $this->lang['members.config-members']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('members_activation', $this->lang['members.config.registration-activation'], $this->user_accounts_config->is_registration_enabled(),
			array(
				'class' => 'custom-checkbox',
				'events' => array('change' => '
					if (HTMLForms.getField("members_activation").getValue()) {
						HTMLForms.getField("type_activation_members").enable();
						if (HTMLForms.getField("type_activation_members").getValue() != ' . UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION . ') {
							HTMLForms.getField("unactivated_accounts_timeout").enable();
						}
					} else {
						HTMLForms.getField("type_activation_members").disable();
						HTMLForms.getField("unactivated_accounts_timeout").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('type_activation_members', $this->lang['members.config.type-activation'], (string)$this->user_accounts_config->get_member_accounts_validation_method(),
			array(
				new FormFieldSelectChoiceOption($this->lang['members.config.type-activation.auto'], UserAccountsConfig::AUTOMATIC_USER_ACCOUNTS_VALIDATION),
				new FormFieldSelectChoiceOption($this->lang['members.config.type-activation.mail'], UserAccountsConfig::MAIL_USER_ACCOUNTS_VALIDATION),
				new FormFieldSelectChoiceOption($this->lang['members.config.type-activation.admin'], UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION)
			),
			array(
				'hidden' => !$this->user_accounts_config->is_registration_enabled(),
				'events' => array('change' => '
					if (HTMLForms.getField("type_activation_members").getValue() != ' . UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION . ') {
						HTMLForms.getField("unactivated_accounts_timeout").enable();
					} else {
						HTMLForms.getField("unactivated_accounts_timeout").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('unactivated_accounts_timeout', $this->lang['members.config.unactivated-accounts-timeout'], (int)$this->user_accounts_config->get_unactivated_accounts_timeout(),
			array(
				'min' => 1, 'max' => 365,
				'description' => $this->lang['members.config.unactivated-accounts-timeout-explain'],
				'hidden' => !$this->user_accounts_config->is_registration_enabled() || $this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION
			),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'))
		));

		$fieldset->add_field(new FormFieldSpacer('1_separator', ''));

		$fieldset->add_field(new FormFieldCheckbox('allow_users_to_change_display_name', $this->lang['members.config.allow_users_to_change_display_name'], $this->user_accounts_config->are_users_allowed_to_change_display_name(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('allow_users_to_change_email', $this->lang['members.config.allow_users_to_change_email'], $this->user_accounts_config->are_users_allowed_to_change_email(),
			array('class' => 'custom-checkbox')
		));

		$fieldset = new FormFieldsetHTML('security_config', $this->lang['members.config-security']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('internal_password_min_length', $this->lang['security.config.internal-password-min-length'], $this->security_config->get_internal_password_min_length(),
			array('min' => 6, 'max' => 30),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'), new FormFieldConstraintIntegerRange(6, 30))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('internal_password_strength', $this->lang['security.config.internal-password-strength'], $this->security_config->get_internal_password_strength(),
			array(
				new FormFieldSelectChoiceOption($this->lang['security.config.internal-password-strength.weak'], SecurityConfig::PASSWORD_STRENGTH_WEAK),
				new FormFieldSelectChoiceOption($this->lang['security.config.internal-password-strength.medium'], SecurityConfig::PASSWORD_STRENGTH_MEDIUM),
				new FormFieldSelectChoiceOption($this->lang['security.config.internal-password-strength.strong'], SecurityConfig::PASSWORD_STRENGTH_STRONG),
				new FormFieldSelectChoiceOption($this->lang['security.config.internal-password-strength.very-strong'], SecurityConfig::PASSWORD_STRENGTH_VERY_STRONG)
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('login_and_email_forbidden_in_password', $this->lang['security.config.login-and-email-forbidden-in-password'], $this->security_config->are_login_and_email_forbidden_in_password(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldMultiLineTextEditor('forbidden_mail_domains', $this->lang['security.config.forbidden-mail-domains'], implode(',', $this->security_config->get_forbidden_mail_domains()),
			array('description' => $this->lang['security.config.forbidden-mail-domains.explain'])
		));

		$fieldset = new FormFieldsetHTML('avatar_management', $this->lang['members.config.avatars-management']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('upload_avatar_server', $this->lang['members.config.upload-avatar-server-authorization'], $this->user_accounts_config->is_avatar_upload_enabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('activation_resize_avatar', $this->lang['members.config.activation-resize-avatar'], $this->user_accounts_config->is_avatar_auto_resizing_enabled(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['members.activation-resize-avatar-explain']
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('maximal_width_avatar', $this->lang['members.config.maximal-width-avatar'], $this->user_accounts_config->get_max_avatar_width(),
			array('description' => $this->lang['members.config.maximal-width-avatar-explain']),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'))
		));

		$fieldset->add_field(new FormFieldNumberEditor('maximal_height_avatar', $this->lang['members.config.maximal-height-avatar'], $this->user_accounts_config->get_max_avatar_height(),
			array('description' => $this->lang['members.config.maximal-height-avatar-explain']),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'))
		));

		$fieldset->add_field(new FormFieldNumberEditor('maximal_weight_avatar', $this->lang['members.config.maximal-weight-avatar'], $this->user_accounts_config->get_max_avatar_weight(),
			array(
				'class' => 'top-field',
				'description' => $this->lang['members.config.maximal-weight-avatar-explain']
			),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'))
		));

		$fieldset->add_field(new FormFieldCheckbox('default_avatar_activation', $this->lang['members.config.default-avatar-activation'], $this->user_accounts_config->is_default_avatar_enabled(),
			array(
				'class' => 'top-field custom-checkbox',
				'description' => $this->lang['members.config.default-avatar-activation-explain']
			)
		));

		$default_avatar_link = $this->user_accounts_config->get_default_avatar_name();
		$fieldset->add_field(new FormFieldTextEditor('default_avatar_link', $this->lang['members.config.default-avatar-link'], $default_avatar_link,
			array(
				'class' => 'top-field',
				'description' => $this->lang['members.default-avatar-link-explain'],
				'events' => array('change' => 'jQuery("#img_avatar").attr("src", "' . TPL_PATH_TO_ROOT . '/templates/'. AppContext::get_current_user()->get_theme() .'/images/" + HTMLForms.getField("default_avatar_link").getValue())')
			)
		));

		$fieldset->add_field(new FormFieldFree('preview', LangLoader::get_message('preview', 'main'), '<img id="img_avatar" src="' . Url::to_rel('/templates/'. AppContext::get_current_user()->get_theme() .'/images/'. $default_avatar_link) .'" alt="' . LangLoader::get_message('preview', 'main') . '" />'));

		$fieldset = new FormFieldsetHTML('authorization', $this->lang['members.config.authorization']);
		$form->add_fieldset($fieldset);

		$auth_settings = new AuthorizationsSettings(array(new ActionAuthorization($this->lang['members.config.authorization-read-member-profile'], UserAccountsConfig::AUTH_READ_MEMBERS_BIT)));
		$auth_settings->build_from_auth_array($this->user_accounts_config->get_auth_read_members());
		$fieldset->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$fieldset = new FormFieldsetHTML('welcome_message', $this->lang['members.config.welcome-message']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldRichTextEditor('welcome_message_contents', $this->lang['members.config.welcome-message-content'], $this->user_accounts_config->get_welcome_message(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset = new FormFieldsetHTML('members_rules', $this->lang['members.rules']);
		$fieldset->set_description($this->lang['members.rules.registration-agreement-description']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldRichTextEditor('registration_agreement', $this->lang['members.rules.registration-agreement'], UserAccountsConfig::load()->get_registration_agreement(),
			array('rows' => 8, 'cols' => 47)
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->user_accounts_config->set_registration_enabled($this->form->get_value('members_activation'));

		if (!$this->form->field_is_disabled('type_activation_members'))
		{
			$this->user_accounts_config->set_member_accounts_validation_method($this->form->get_value('type_activation_members')->get_raw_value());
		}

		if (!$this->form->field_is_disabled('unactivated_accounts_timeout'))
		{
			$this->user_accounts_config->set_unactivated_accounts_timeout($this->form->get_value('unactivated_accounts_timeout'));
		}

		$this->user_accounts_config->set_allow_users_to_change_display_name($this->form->get_value('allow_users_to_change_display_name'));
		$this->user_accounts_config->set_allow_users_to_change_email($this->form->get_value('allow_users_to_change_email'));

		$this->security_config->set_internal_password_min_length($this->form->get_value('internal_password_min_length'));
		$this->security_config->set_internal_password_strength($this->form->get_value('internal_password_strength')->get_raw_value());

		if ($this->form->get_value('login_and_email_forbidden_in_password'))
			$this->security_config->forbid_login_and_email_in_password();
		else
			$this->security_config->allow_login_and_email_in_password();

		$this->security_config->set_forbidden_mail_domains($this->form->get_value('forbidden_mail_domains') ? explode(',', str_replace(array(' ', ';'), array('', ','), $this->form->get_value('forbidden_mail_domains'))) : array());

		SecurityConfig::save();

		$this->user_accounts_config->set_avatar_upload_enabled($this->form->get_value('upload_avatar_server'));
		$this->user_accounts_config->set_default_avatar_name_enabled($this->form->get_value('default_avatar_activation'));
		$this->user_accounts_config->set_avatar_auto_resizing_enabled($this->form->get_value('activation_resize_avatar'));
		$this->user_accounts_config->set_default_avatar_name($this->form->get_value('default_avatar_link'));
		$this->user_accounts_config->set_max_avatar_width($this->form->get_value('maximal_width_avatar'));
		$this->user_accounts_config->set_max_avatar_height($this->form->get_value('maximal_height_avatar'));
		$this->user_accounts_config->set_max_avatar_weight($this->form->get_value('maximal_weight_avatar'));
		$this->user_accounts_config->set_auth_read_members($this->form->get_value('authorizations')->build_auth_array());
		$this->user_accounts_config->set_welcome_message($this->form->get_value('welcome_message_contents'));
		$this->user_accounts_config->set_registration_agreement($this->form->get_value('registration_agreement'));
		UserAccountsConfig::save();
	}
}
?>
