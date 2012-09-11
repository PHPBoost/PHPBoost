<?php
/*##################################################
 *                       AdminMemberConfigController.class.php
 *                            -------------------
 *   begin                : December 17, 2010
 *   copyright            : (C) 2010 Kevin MASSY
 *   email                : soldier.weasel@gmail.com
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

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			AppContext::get_response()->redirect(AdminMembersUrlBuilder::configuration());
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminMembersDisplayResponse($tpl, $this->lang['members.members-management']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-members-common');
	}

	private function build_form()
	{
		$user_account_config = UserAccountsConfig::load();
		
		$form = new HTMLForm('members-config');
		
		$fieldset = new FormFieldsetHTML('members_config', $this->lang['members.config-members']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldCheckbox('members_activation', $this->lang['members.config.registration-activation'], $user_account_config->is_registration_enabled(), 
		array('events' => array('change' => '
				if (HTMLForms.getField("members_activation").getValue()) { 
					HTMLForms.getField("type_activation_members").enable(); 
				} else { 
					HTMLForms.getField("type_activation_members").disable(); 
				}'
		))));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('type_activation_members', $this->lang['members.config.type-activation'], (string)$user_account_config->get_member_accounts_validation_method(),
			array(
				new FormFieldSelectChoiceOption($this->lang['members.config.type-activation.auto'], UserAccountsConfig::AUTOMATIC_USER_ACCOUNTS_VALIDATION),
				new FormFieldSelectChoiceOption($this->lang['members.config.type-activation.mail'], UserAccountsConfig::MAIL_USER_ACCOUNTS_VALIDATION),
				new FormFieldSelectChoiceOption($this->lang['members.config.type-activation.admin'], UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION)
			), array('hidden' => !$user_account_config->is_registration_enabled())
		));

		$fieldset->add_field(new FormFieldTextEditor('unactivated_accounts_timeout', $this->lang['members.config.unactivated-accounts-timeout'], (string)$user_account_config->get_unactivated_accounts_timeout(), array(
			'class' => 'text', 'maxlength' => 4, 'size' => 4,'description' => $this->lang['members.config.unactivated-accounts-timeout-explain']),
		array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('captcha_activation', $this->lang['members.config.captcha-activation'], $user_account_config->is_registration_captcha_enabled(),
		array('events' => array('change' => '
				if (HTMLForms.getField("captcha_activation").getValue()) { 
					HTMLForms.getField("captcha_difficulty").enable(); 
				} else { 
					HTMLForms.getField("captcha_difficulty").disable(); 
				}'
		))));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('captcha_difficulty', $this->lang['members.config.captcha-difficulty'], (string)$user_account_config->get_registration_captcha_difficulty(),
			array(
				new FormFieldSelectChoiceOption('0', '0'),
				new FormFieldSelectChoiceOption('1', '1'),
				new FormFieldSelectChoiceOption('2', '2'),
				new FormFieldSelectChoiceOption('3', '3'),
				new FormFieldSelectChoiceOption('4', '4'),
			), array('hidden' => !$user_account_config->is_registration_captcha_enabled())
		));

		$fieldset->add_field(new FormFieldCheckbox('theme_choice_permission', $this->lang['members.config.theme-choice-permission'], !$user_account_config->is_users_theme_forced()));
		
		$fieldset = new FormFieldsetHTML('avatar_management', $this->lang['members.config.avatars-management']);
		$form->add_fieldset($fieldset);
				
		$fieldset->add_field(new FormFieldCheckbox('upload_avatar_server', $this->lang['members.config.upload-avatar-server-authorization'], $user_account_config->is_avatar_upload_enabled()));
		
		$fieldset->add_field(new FormFieldCheckbox('activation_resize_avatar', $this->lang['members.config.activation-resize-avatar'], $user_account_config->is_avatar_auto_resizing_enabled(),
			array('description' => $this->lang['members.activation-resize-avatar-explain'])
		));
		
		$fieldset->add_field(new FormFieldTextEditor('maximal_width_avatar', $this->lang['members.config.maximal-width-avatar'], $user_account_config->get_max_avatar_width(), array(
			'class' => 'text', 'maxlength' => 4, 'size' => 4,'description' => $this->lang['members.config.maximal-width-avatar-explain']),
		array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('maximal_height_avatar', $this->lang['members.config.maximal-height-avatar'], $user_account_config->get_max_avatar_height(), array(
			'class' => 'text', 'maxlength' => 4, 'size' => 4,'description' => $this->lang['members.config.maximal-height-avatar-explain']),
		array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('maximal_weight_avatar', $this->lang['members.config.maximal-weight-avatar'], $user_account_config->get_max_avatar_weight(), array(
			'class' => 'text', 'maxlength' => 4, 'size' => 4,'description' => $this->lang['members.config.maximal-weight-avatar-explain']),
		array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('default_avatar_activation', $this->lang['members.config.default-avatar-activation'], $user_account_config->is_default_avatar_enabled(),
			array('description' => $this->lang['members.config.default-avatar-activation-explain'])
		));
		
		$default_avatar_link = $user_account_config->get_default_avatar_name();
		$fieldset->add_field(new FormFieldTextEditor('default_avatar_link', $this->lang['members.config.default-avatar-link'], $default_avatar_link, array(
			'class' => 'text', 'description' => $this->lang['members.default-avatar-link-explain'], 'events' => array('change' => '$(\'img_avatar\').src = "' . TPL_PATH_TO_ROOT . '/templates/'. get_utheme() .'/images/" + HTMLForms.getField("default_avatar_link").getValue()'))
		));
		
		$fieldset->add_field(new FormFieldFree('preview', LangLoader::get_message('preview', 'main'), '<img id="img_avatar" src="' . Url::to_rel('/templates/'. get_utheme() .'/images/'. $default_avatar_link) .'" alt="" style="vertical-align:top" />'));

		$fieldset = new FormFieldsetHTML('authorization', $this->lang['members.config.authorization']);
		$form->add_fieldset($fieldset);
		
		$auth_settings = new AuthorizationsSettings(array(new ActionAuthorization($this->lang['members.config.authorization-read-member-profile'], AUTH_READ_MEMBERS)));
		$auth_settings->build_from_auth_array($user_account_config->get_auth_read_members());
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$fieldset->add_field($auth_setter);
		
		$fieldset = new FormFieldsetHTML('welcome_message', $this->lang['members.config.welcome-message']);
		$form->add_fieldset($fieldset);
		
		$welcome_message_contents = FormatingHelper::unparse($user_account_config->get_welcome_message());
		$fieldset->add_field(new FormFieldRichTextEditor('welcome_message_contents', $this->lang['members.config.welcome-message-content'], $welcome_message_contents, array(
			'class' => 'text', 'rows' => 8, 'cols' => 47)
		));
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function save()
	{
		$user_account_config = UserAccountsConfig::load();
		$user_account_config->set_registration_enabled($this->form->get_value('members_activation'));
		
		if (!$this->form->field_is_disabled('type_activation_members'))
		{
			$user_account_config->set_member_accounts_validation_method($this->form->get_value('type_activation_members')->get_raw_value());
		}
		
		$user_account_config->set_registration_captcha_enabled($this->form->get_value('captcha_activation'));
		
		if (!$this->form->field_is_disabled('captcha_difficulty'))
		{
			$user_account_config->set_registration_captcha_difficulty($this->form->get_value('captcha_difficulty')->get_raw_value());
		}
		
		$user_account_config->set_force_theme_enabled(!$this->form->get_value('theme_choice_permission'));
		$user_account_config->set_avatar_upload_enabled($this->form->get_value('upload_avatar_server'));
		$user_account_config->set_unactivated_accounts_timeout($this->form->get_value('unactivated_accounts_timeout'));
		$user_account_config->set_default_avatar_name_enabled($this->form->get_value('default_avatar_activation'));
		$user_account_config->set_avatar_auto_resizing_enabled($this->form->get_value('activation_resize_avatar'));
		$user_account_config->set_default_avatar_name($this->form->get_value('default_avatar_link'));
		$user_account_config->set_max_avatar_width($this->form->get_value('maximal_width_avatar'));
		$user_account_config->set_max_avatar_height($this->form->get_value('maximal_height_avatar'));
		$user_account_config->set_max_avatar_weight($this->form->get_value('maximal_weight_avatar'));
		$user_account_config->set_auth_read_members($this->form->get_value('authorizations')->build_auth_array());
		$user_account_config->set_welcome_message(FormatingHelper::strparse($this->form->get_value('welcome_message_contents')));
		UserAccountsConfig::save();
	}
}
?>