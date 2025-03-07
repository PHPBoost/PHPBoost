<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 06 07
 * @since       PHPBoost 3.0 - 2010 12 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminMemberConfigController extends DefaultAdminController
{
	private $user_accounts_config;
	private $security_config;
	private $authentication_config;
	private $server_configuration;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();

			$this->form->get_field_by_id('type_activation_members')->set_hidden(!$this->user_accounts_config->is_registration_enabled());
			$this->form->get_field_by_id('unactivated_accounts_timeout')->set_hidden(!$this->user_accounts_config->is_registration_enabled() || $this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION);
			$this->form->get_field_by_id('user_activation_admin_email')->set_hidden(!$this->user_accounts_config->is_registration_enabled() || $this->user_accounts_config->get_member_accounts_validation_method() !== UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION);
			// $this->form->get_field_by_id('items_per_row')->set_hidden($this->user_accounts_config->get_display_type() !== UserAccountsConfig::GRID_VIEW);
			// UserCache::invalidate();
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new AdminMembersDisplayResponse($this->view, $this->lang['user.members.config']);
	}

	private function init()
	{
		$this->user_accounts_config = UserAccountsConfig::load();
		$this->security_config = SecurityConfig::load();
		$this->server_configuration = new ServerConfiguration();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('members_config', $this->lang['user.members.config']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('members_activation', $this->lang['user.registration.activation'], $this->user_accounts_config->is_registration_enabled(),
			array(
				'class' => 'custom-checkbox top-field',
				'events' => array('change' => '
					if (HTMLForms.getField("members_activation").getValue()) {
						HTMLForms.getField("type_activation_members").enable();
						if (HTMLForms.getField("type_activation_members").getValue() != ' . UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION . ') {
							HTMLForms.getField("unactivated_accounts_timeout").enable();
							HTMLForms.getField("user_activation_admin_email").disable();
						} else {
							HTMLForms.getField("user_activation_admin_email").enable();
                        }
					} else {
						HTMLForms.getField("type_activation_members").disable();
						HTMLForms.getField("unactivated_accounts_timeout").disable();
						HTMLForms.getField("user_activation_admin_email").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('type_activation_members', $this->lang['user.activation.mode'], (string)$this->user_accounts_config->get_member_accounts_validation_method(),
			array(
				new FormFieldSelectChoiceOption($this->lang['user.activation.auto'], UserAccountsConfig::AUTOMATIC_USER_ACCOUNTS_VALIDATION),
				new FormFieldSelectChoiceOption($this->lang['user.activation.mail'], UserAccountsConfig::MAIL_USER_ACCOUNTS_VALIDATION),
				new FormFieldSelectChoiceOption($this->lang['user.activation.admin'], UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION)
			),
			array(
				'class' => 'top-field',
				'hidden' => !$this->user_accounts_config->is_registration_enabled(),
				'events' => array('change' => '
					if (HTMLForms.getField("type_activation_members").getValue() != ' . UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION . ') {
						HTMLForms.getField("unactivated_accounts_timeout").enable();
						HTMLForms.getField("user_activation_admin_email").disable();
					} else {
						HTMLForms.getField("unactivated_accounts_timeout").disable();
						HTMLForms.getField("user_activation_admin_email").enable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('user_activation_admin_email', $this->lang['user.activation.admin.email'], $this->user_accounts_config->get_administrator_accounts_validation_email(),
			array(
				'class' => 'custom-checkbox top-field',
				'hidden' => !$this->user_accounts_config->is_registration_enabled() || $this->user_accounts_config->get_member_accounts_validation_method() != UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('unactivated_accounts_timeout', $this->lang['user.unactivated.timeout'], (int)$this->user_accounts_config->get_unactivated_accounts_timeout(),
			array(
				'min' => 1, 'max' => 365,
				'description' => $this->lang['user.unactivated.timeout.clue'],
				'hidden' => !$this->user_accounts_config->is_registration_enabled() || $this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION
			),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'))
		));

		$fieldset->add_field(new FormFieldSpacer('1_separator', ''));

		$fieldset->add_field(new FormFieldCheckbox('allow_users_to_change_display_name', $this->lang['user.allow.display.name.change'], $this->user_accounts_config->are_users_allowed_to_change_display_name(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('allow_users_to_change_email', $this->lang['user.allow.email.change'], $this->user_accounts_config->are_users_allowed_to_change_email(),
			array('class' => 'custom-checkbox')
		));

		// $fieldset = new FormFieldsetHTML('display_view', $this->lang['user.display.type']);
		// $form->add_fieldset($fieldset);

		// $fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->lang['form.display.type'], $this->user_accounts_config->get_display_type(),
		// 	array(
		// 		new FormFieldSelectChoiceOption($this->lang['form.display.type.grid'], UserAccountsConfig::GRID_VIEW, array('data_option_icon' => 'fa fa-th-large')),
		// 		new FormFieldSelectChoiceOption($this->lang['form.display.type.table'], UserAccountsConfig::TABLE_VIEW, array('data_option_icon' => 'fa fa-table'))
		// 	),
		// 	array(
		// 		'select_to_list' => true,
		// 		'events' => array('change' => '
		// 		if (HTMLForms.getField("display_type").getValue() == \'' . UserAccountsConfig::GRID_VIEW . '\') {
		// 			HTMLForms.getField("items_per_row").enable();
		// 		} else {
		// 			HTMLForms.getField("items_per_row").disable();
		// 		}'
		// 	))
		// ));

		// $fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->lang['form.items.per.page'], $this->user_accounts_config->get_items_per_page(),
		// 	array(
		// 		'min' => 1, 'max' => 50, 'required' => true,
		// 	),
		// 	array(new FormFieldConstraintIntegerRange(1, 50))
		// ));

		// $fieldset->add_field(new FormFieldNumberEditor('items_per_row', $this->lang['form.items.per.row'], $this->user_accounts_config->get_items_per_row(),
		// 	array(
		// 		'hidden' => $this->user_accounts_config->get_display_type() !== UserAccountsConfig::GRID_VIEW,
		// 		'min' => 1, 'max' => 4, 'required' => true),
		// 		array(new FormFieldConstraintIntegerRange(1, 4))
		// ));

		$fieldset = new FormFieldsetHTML('security_config', $this->lang['user.security']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('internal_password_min_length', $this->lang['user.password.min.length'], $this->security_config->get_internal_password_min_length(),
			array('min' => 6, 'max' => 30),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'), new FormFieldConstraintIntegerRange(6, 30))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('internal_password_strength', $this->lang['user.password.strength'], $this->security_config->get_internal_password_strength(),
			array(
				new FormFieldSelectChoiceOption($this->lang['user.password.strength.weak'], SecurityConfig::PASSWORD_STRENGTH_WEAK),
				new FormFieldSelectChoiceOption($this->lang['user.password.strength.medium'], SecurityConfig::PASSWORD_STRENGTH_MEDIUM),
				new FormFieldSelectChoiceOption($this->lang['user.password.strength.strong'], SecurityConfig::PASSWORD_STRENGTH_STRONG),
				new FormFieldSelectChoiceOption($this->lang['user.password.strength.very.strong'], SecurityConfig::PASSWORD_STRENGTH_VERY_STRONG)
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('login_and_email_forbidden_in_password', $this->lang['user.password.forbidden.tag'], $this->security_config->are_login_and_email_forbidden_in_password(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldMultiLineTextEditor('forbidden_mail_domains', $this->lang['user.forbidden.email.domains'], implode(',', $this->security_config->get_forbidden_mail_domains()),
			array('description' => $this->lang['user.forbidden.email.domains.clue'])
		));

		$fieldset = new FormFieldsetHTML('avatar_management', $this->lang['user.avatars.management']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('upload_avatar_server', $this->lang['user.allow.avatar.upload'], $this->user_accounts_config->is_avatar_upload_enabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('activation_resize_avatar', $this->lang['user.enable.avatar.resizing'], $this->user_accounts_config->is_avatar_auto_resizing_enabled(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['user.avatar.resizing.clue']
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('maximal_width_avatar', $this->lang['user.avatar.max.width'], $this->user_accounts_config->get_max_avatar_width(),
			array('description' => $this->lang['user.avatar.max.width.clue']),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'))
		));

		$fieldset->add_field(new FormFieldNumberEditor('maximal_height_avatar', $this->lang['user.avatar.max.height'], $this->user_accounts_config->get_max_avatar_height(),
			array('description' => $this->lang['user.avatar.max.height.clue']),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'))
		));

		$fieldset->add_field(new FormFieldNumberEditor('maximal_weight_avatar', $this->lang['user.avatar.max.weight'], $this->user_accounts_config->get_max_avatar_weight(),
			array(
				'class' => 'top-field',
				'description' => $this->lang['user.avatar.max.weight.clue']
			),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'))
		));

		$fieldset->add_field(new FormFieldThumbnail('default_avatar', $this->lang['user.default.avatar'], $this->user_accounts_config->get_default_avatar_name(), UserAccountsConfig::NO_AVATAR_URL,
			array('class' => 'half-field')
		));

		$fieldset = new FormFieldsetHTML('authorization', $this->lang['form.authorizations']);
		$form->add_fieldset($fieldset);

		$auth_settings = new AuthorizationsSettings(array(new ActionAuthorization($this->lang['user.authorization.description'], UserAccountsConfig::AUTH_READ_MEMBERS_BIT)));
		$auth_settings->build_from_auth_array($this->user_accounts_config->get_auth_read_members());
		$fieldset->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$fieldset = new FormFieldsetHTML('welcome_message', $this->lang['user.welcome.message']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldRichTextEditor('welcome_message_contents', $this->lang['user.welcome.message.content'], $this->user_accounts_config->get_welcome_message(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset = new FormFieldsetHTML('members_rules', $this->lang['user.rules']);
		$fieldset->set_description($this->lang['user.rules.description']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldRichTextEditor('registration_agreement', $this->lang['user.rules.content'], UserAccountsConfig::load()->get_registration_agreement(),
			array('rows' => 8, 'cols' => 47)
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		// $this->user_accounts_config->set_display_type($this->form->get_value('display_type')->get_raw_value());
		// $this->user_accounts_config->set_items_per_page($this->form->get_value('items_per_page'));
		// if($this->form->get_value('display_type')->get_raw_value() == UserAccountsConfig::GRID_VIEW)
		// 	$this->user_accounts_config->set_items_per_row($this->form->get_value('items_per_row'));

		$this->user_accounts_config->set_registration_enabled($this->form->get_value('members_activation'));

		if (!$this->form->field_is_disabled('type_activation_members'))
		{
			$this->user_accounts_config->set_member_accounts_validation_method($this->form->get_value('type_activation_members')->get_raw_value());
		}

		if (!$this->form->field_is_disabled('unactivated_accounts_timeout'))
		{
			$this->user_accounts_config->set_unactivated_accounts_timeout($this->form->get_value('unactivated_accounts_timeout'));
		}

		if (!$this->form->field_is_disabled('user_activation_admin_email'))
		{
			$this->user_accounts_config->set_administrator_accounts_validation_email($this->form->get_value('user_activation_admin_email'));
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
		$this->user_accounts_config->set_avatar_auto_resizing_enabled($this->form->get_value('activation_resize_avatar'));
		$this->user_accounts_config->set_default_avatar_name($this->form->get_value('default_avatar'));
		$this->user_accounts_config->set_max_avatar_width($this->form->get_value('maximal_width_avatar'));
		$this->user_accounts_config->set_max_avatar_height($this->form->get_value('maximal_height_avatar'));
		$this->user_accounts_config->set_max_avatar_weight($this->form->get_value('maximal_weight_avatar'));
		$this->user_accounts_config->set_auth_read_members($this->form->get_value('authorizations')->build_auth_array());
		$this->user_accounts_config->set_welcome_message($this->form->get_value('welcome_message_contents'));
		$this->user_accounts_config->set_registration_agreement($this->form->get_value('registration_agreement'));
		UserAccountsConfig::save();

		HooksService::execute_hook_action('edit_config', 'kernel', array('title' => $this->lang['user.members.config'], 'url' => AdminMembersUrlBuilder::configuration()->rel()));
	}
}
?>
