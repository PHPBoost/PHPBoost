<?php
/*##################################################
 *                       AdminMemberConfigController.class.php
 *                            -------------------
 *   begin                : December 17, 2010
 *   copyright            : (C) 2010 Kévin MASSY
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

	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submitted() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display($this->lang['members.config.success-saving'], E_USER_SUCCESS, 4));
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
		$registration_activation = $user_account_config->is_registration_enabled();
		$member_accounts_validation_method = (string)$user_account_config->get_member_accounts_validation_method();
		$unactivated_accounts_timeout = (string)$user_account_config->get_unactivated_accounts_timeout();
		$captcha_activation = $user_account_config->is_registration_captcha_enabled();
		$captcha_difficulty = (string)$user_account_config->get_registration_captcha_difficulty();
		$theme_forced = $user_account_config->is_users_theme_forced();
		$upload_avatar_server = $user_account_config->is_avatar_upload_enabled();
		$activation_resize_avatar = $user_account_config->is_avatar_auto_resizing_enabled();
		$maximal_width_avatar = $user_account_config->get_max_avatar_width();
		$maximal_height_avatar = $user_account_config->get_max_avatar_height();
		$maximal_weight_avatar = $user_account_config->get_max_avatar_weight();
		$default_avatar_activation = $user_account_config->is_default_avatar_enabled();
		$default_avatar_link = $user_account_config->get_default_avatar_name();
		$authorization_read_member_profile = $user_account_config->get_auth_read_members();
		$welcome_message_contents = FormatingHelper::unparse($user_account_config->get_welcome_message());
		
		$form = new HTMLForm('members-config');
		
		$fieldset = new FormFieldsetHTML('members_config', $this->lang['members.config-members']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldCheckbox('members_activation', $this->lang['members.config.registration-activation'], $registration_activation));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('type_activation_members', $this->lang['members.config.type-activation'], $member_accounts_validation_method,
			array(
				new FormFieldSelectChoiceOption($this->lang['members.config.type-activation.auto'], '1'),
				new FormFieldSelectChoiceOption($this->lang['members.config.type-activation.mail'], '2'),
				new FormFieldSelectChoiceOption($this->lang['members.config.type-activation.admin'], '3')
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('unactivated_accounts_timeout', $this->lang['members.config.unactivated-accounts-timeout'], $unactivated_accounts_timeout, array(
			'class' => 'text', 'maxlength' => 4, 'size' => 4,'description' => $this->lang['members.config.unactivated-accounts-timeout-explain']),
		array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('captcha_activation', $this->lang['members.config.captcha-activation'], $captcha_activation));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('captcha_difficulty', $this->lang['members.config.captcha-difficulty'], $captcha_difficulty,
			array(
				new FormFieldSelectChoiceOption('0', '0'),
				new FormFieldSelectChoiceOption('1', '1'),
				new FormFieldSelectChoiceOption('2', '2'),
				new FormFieldSelectChoiceOption('3', '3'),
				new FormFieldSelectChoiceOption('4', '4'),
			)
		));
		
		$fieldset->add_field(new FormFieldCheckbox('theme_choice_permission', $this->lang['members.config.theme-choice-permission'], $theme_forced));
		
		$fieldset = new FormFieldsetHTML('avatar_management', $this->lang['members.config.avatars-management']);
		$form->add_fieldset($fieldset);
				
		$fieldset->add_field(new FormFieldCheckbox('upload_avatar_server', $this->lang['members.config.upload-avatar-server-authorization'], $upload_avatar_server));
		
		$fieldset->add_field(new FormFieldCheckbox('activation_resize_avatar', $this->lang['members.config.activation-resize-avatar'], $activation_resize_avatar,
			array('description' => $this->lang['members.activation-resize-avatar-explain'])
		));
		
		$fieldset->add_field(new FormFieldTextEditor('maximal_width_avatar', $this->lang['members.config.maximal-width-avatar'], $maximal_width_avatar, array(
			'class' => 'text', 'maxlength' => 4, 'size' => 4,'description' => $this->lang['members.config.maximal-width-avatar-explain']),
		array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('maximal_height_avatar', $this->lang['members.config.maximal-height-avatar'], $maximal_height_avatar, array(
			'class' => 'text', 'maxlength' => 4, 'size' => 4,'description' => $this->lang['members.config.maximal-height-avatar-explain']),
		array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('maximal_weight_avatar', $this->lang['members.config.maximal-weight-avatar'], $maximal_weight_avatar, array(
			'class' => 'text', 'maxlength' => 4, 'size' => 4,'description' => $this->lang['members.config.maximal-weight-avatar-explain']),
		array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('default_avatar_activation', $this->lang['members.config.default-avatar-activation'], $default_avatar_activation,
			array('description' => $this->lang['members.config.default-avatar-activation-explain'])
		));
		
		$fieldset->add_field(new FormFieldTextEditor('default_avatar_link', $this->lang['members.config.default-avatar-link'], $default_avatar_link, array(
			'class' => 'text', 'description' => $this->lang['members.default-avatar-link-explain'], 'events' => array('change' => 'document.images[\'img_avatar\'].src = "' . PATH_TO_ROOT . '/templates/'. get_utheme() .'/images/" + HTMLForms.getField("default-avatar-link").getValue()'))
		));
		
		$fieldset->add_field(new FormFieldFree('preview', LangLoader::get_message('preview', 'main'), '<img id="img_avatar" src="' . PATH_TO_ROOT . '/templates/'. get_utheme() .'/images/'. $default_avatar_link .'" alt="" style="vertical-align:top" />'));

		$fieldset = new FormFieldsetHTML('authorization', $this->lang['members.config.authorization']);
		$form->add_fieldset($fieldset);
		
		$auth_settings = new AuthorizationsSettings(array(new ActionAuthorization($this->lang['members.config.authorization-read-member-profile'], AUTH_READ_MEMBERS)));
		$auth_settings->build_from_auth_array($authorization_read_member_profile);
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$fieldset->add_field($auth_setter);
		
		$fieldset = new FormFieldsetHTML('welcome_message', $this->lang['members.config.welcome-message']);
		$form->add_fieldset($fieldset);
		
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
		$user_account_config->set_member_accounts_validation_method($this->form->get_value('type_activation_members')->get_raw_value());
		$user_account_config->set_registration_captcha_enabled($this->form->get_value('captcha_activation'));
		$user_account_config->set_registration_captcha_difficulty($this->form->get_value('captcha_difficulty')->get_raw_value());
		$user_account_config->set_force_theme_enabled($this->form->get_value('theme_choice_permission'));
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