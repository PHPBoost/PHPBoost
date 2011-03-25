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

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display($this->lang['members.success-saving-config'], E_USER_SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return $this->build_response($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('members-common');
	}

	private function build_form()
	{
		$user_account_config = UserAccountsConfig::load();
		$registration_activation = $user_account_config->is_registration_enabled() ? '1' : '0';
		$member_accounts_validation_method = (string)$user_account_config->get_member_accounts_validation_method();
		$unactivated_accounts_timeout = (string)$user_account_config->get_unactivated_accounts_timeout();
		$captcha_activation = $user_account_config->is_registration_captcha_enabled() ? '1' : '0';
		$captcha_difficulty = (string)$user_account_config->get_registration_captcha_difficulty();
		$theme_forced = $user_account_config->is_users_theme_forced() ? '1' : '0';
		$upload_avatar_server = $user_account_config->is_avatar_upload_enabled() ? '1' : '0';
		$activation_resize_avatar = $user_account_config->is_avatar_auto_resizing_enabled() ? '1' : '0';
		$maximal_width_avatar = $user_account_config->get_max_avatar_width();
		$maximal_height_avatar = $user_account_config->get_max_avatar_height();
		$maximal_weight_avatar = $user_account_config->get_max_avatar_weight();
		$default_avatar_activation = $user_account_config->is_default_avatar_enabled() ? '1' : '0';
		$default_avatar_link = $user_account_config->get_default_avatar_name();
		$authorization_read_member_profile = $user_account_config->get_auth_read_members();
		$welcome_message_contents = FormatingHelper::unparse($user_account_config->get_welcome_message());
		
		$form = new HTMLForm('members-config');
		
		$fieldset = new FormFieldsetHTML('members_config', $this->lang['members.config-members']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldRadioChoice('members-activation', $this->lang['members.registration-activation'], $registration_activation,
			array(
				new FormFieldRadioChoiceOption($this->lang['members.yes'], '1'),
				new FormFieldRadioChoiceOption($this->lang['members.no'], '0')
			)
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('type-activation-members', $this->lang['members.type-activation'], $member_accounts_validation_method,
			array(
				new FormFieldSelectChoiceOption($this->lang['members.type-activation.auto'], '1'),
				new FormFieldSelectChoiceOption($this->lang['members.type-activation.mail'], '2'),
				new FormFieldSelectChoiceOption($this->lang['members.type-activation.admin'], '3')
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('unactivated-accounts-timeout', $this->lang['members.unactivated-accounts-timeout'], $unactivated_accounts_timeout, array(
			'class' => 'text', 'maxlength' => 4, 'size' => 4,'description' => $this->lang['members.unactivated-accounts-timeout-explain']),
		array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldRadioChoice('captcha-activation', $this->lang['members.captcha-activation'], $captcha_activation,
			array(
				new FormFieldRadioChoiceOption($this->lang['members.yes'], '1'),
				new FormFieldRadioChoiceOption($this->lang['members.no'], '0')
			)
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('captcha-difficulty', $this->lang['members.captcha-difficulty'], $captcha_difficulty,
			array(
				new FormFieldSelectChoiceOption('0', '0'),
				new FormFieldSelectChoiceOption('1', '1'),
				new FormFieldSelectChoiceOption('2', '2'),
				new FormFieldSelectChoiceOption('3', '3'),
				new FormFieldSelectChoiceOption('4', '4'),
			)
		));
		
		$fieldset->add_field(new FormFieldRadioChoice('theme-choice-permission', $this->lang['members.theme-choice-permission'], $theme_forced,
			array(
				new FormFieldRadioChoiceOption($this->lang['members.yes'], '1'),
				new FormFieldRadioChoiceOption($this->lang['members.no'], '0')
			)
		));
		
		$fieldset = new FormFieldsetHTML('avatar_management', $this->lang['members.avatars-management']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldRadioChoice('upload-avatar-server', $this->lang['members.upload-avatar-server-authorization'], $upload_avatar_server,
			array(
				new FormFieldRadioChoiceOption($this->lang['members.yes'], '1'),
				new FormFieldRadioChoiceOption($this->lang['members.no'], '0')
			)
		));
		
		$fieldset->add_field(new FormFieldRadioChoice('activation-resize-avatar', $this->lang['members.activation-resize-avatar'], $activation_resize_avatar,
			array(
				new FormFieldRadioChoiceOption($this->lang['members.yes'], '1'),
				new FormFieldRadioChoiceOption($this->lang['members.no'], '0')
			),
			array('description' => $this->lang['members.activation-resize-avatar-explain'])
		));
		
		$fieldset->add_field(new FormFieldTextEditor('maximal-width-avatar', $this->lang['members.maximal-width-avatar'], $maximal_width_avatar, array(
			'class' => 'text', 'maxlength' => 4, 'size' => 4,'description' => $this->lang['members.maximal-width-avatar-explain']),
		array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('maximal-height-avatar', $this->lang['members.maximal-height-avatar'], $maximal_height_avatar, array(
			'class' => 'text', 'maxlength' => 4, 'size' => 4,'description' => $this->lang['members.maximal-height-avatar-explain']),
		array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('maximal-weight-avatar', $this->lang['members.maximal-weight-avatar'], $maximal_weight_avatar, array(
			'class' => 'text', 'maxlength' => 4, 'size' => 4,'description' => $this->lang['members.maximal-weight-avatar-explain']),
		array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldRadioChoice('default-avatar-activation', $this->lang['members.default-avatar-activation'], $default_avatar_activation,
			array(
				new FormFieldRadioChoiceOption($this->lang['members.yes'], '1'),
				new FormFieldRadioChoiceOption($this->lang['members.no'], '0')
			),
			array('description' => $this->lang['members.default-avatar-activation-explain'])
		));
		
		$fieldset->add_field(new FormFieldTextEditor('default-avatar-link', $this->lang['members.default-avatar-link'], $default_avatar_link, array(
			'class' => 'text', 'description' => $this->lang['members.default-avatar-link-explain'], 'events' => array('change' => 'document.images[\'img_avatar\'].src = "' . PATH_TO_ROOT . '/templates/'. get_utheme() .'/images/" + HTMLForms.getField("default-avatar-link").getValue()'))
		));
		
		$fieldset->add_field(new FormFieldFree('preview', LangLoader::get_message('preview', 'main'), '<img id="img_avatar" src="' . PATH_TO_ROOT . '/templates/'. get_utheme() .'/images/'. $default_avatar_link .'" alt="" style="vertical-align:top" />'));

		
		$fieldset = new FormFieldsetHTML('authorization', $this->lang['members.authorization']);
		$form->add_fieldset($fieldset);
		
		$auth_settings = new AuthorizationsSettings(array(new ActionAuthorization($this->lang['members.authorization-read-member-profile'], AUTH_READ_MEMBERS)));
		$auth_settings->build_from_auth_array($authorization_read_member_profile);
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$fieldset->add_field($auth_setter);
		
		$fieldset = new FormFieldsetHTML('welcome_message', $this->lang['members.welcome-message']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldRichTextEditor('welcome-message-contents', $this->lang['members.welcome-message-content'], $welcome_message_contents, array(
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
		$user_account_config->set_registration_enabled($this->form->get_value('members-activation')->get_raw_value());
		$user_account_config->set_member_accounts_validation_method($this->form->get_value('type-activation-members')->get_raw_value());
		$user_account_config->set_registration_captcha_enabled($this->form->get_value('captcha-activation')->get_raw_value());
		$user_account_config->set_registration_captcha_difficulty($this->form->get_value('captcha-difficulty')->get_raw_value());
		$user_account_config->set_force_theme_enabled($this->form->get_value('theme-choice-permission')->get_raw_value());
		$user_account_config->set_avatar_upload_enabled($this->form->get_value('upload-avatar-server')->get_raw_value());
		$user_account_config->set_unactivated_accounts_timeout($this->form->get_value('unactivated-accounts-timeout'));
		$user_account_config->set_default_avatar_name_enabled($this->form->get_value('default-avatar-activation')->get_raw_value());
		$user_account_config->set_avatar_auto_resizing_enabled($this->form->get_value('activation-resize-avatar')->get_raw_value());
		$user_account_config->set_default_avatar_name($this->form->get_value('default-avatar-link'));
		$user_account_config->set_max_avatar_width($this->form->get_value('maximal-width-avatar'));
		$user_account_config->set_max_avatar_height($this->form->get_value('maximal-height-avatar'));
		$user_account_config->set_max_avatar_weight($this->form->get_value('maximal-weight-avatar'));
		$user_account_config->set_auth_read_members($this->form->get_value('authorizations')->build_auth_array());
		$user_account_config->set_welcome_message(FormatingHelper::strparse($this->form->get_value('welcome-message-contents')));
		UserAccountsConfig::save();
	}

	private function build_response(View $view)
	{
		$response = new AdminMenuDisplayResponse($view);
		$response->set_title($this->lang['members.members-management']);
		$response->add_link($this->lang['members.members-management'], DispatchManager::get_url('/admin/member/index.php', '/member/'), '/templates/' . get_utheme() . '/images/admin/members.png');
		$response->add_link($this->lang['members.add-member'], DispatchManager::get_url('/admin/member/index.php', '/member/add'), '/templates/' . get_utheme() . '/images/admin/members.png');
		$response->add_link($this->lang['members.config-members'], DispatchManager::get_url('/admin/member/index.php', '/member/config'), '/templates/' . get_utheme() . '/images/admin/members.png');
		$response->add_link($this->lang['members.members-punishment'], DispatchManager::get_url('/admin/member/index.php', '/member/punishment'), '/templates/' . get_utheme() . '/images/admin/members.png');
		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['members.config-members']);
		return $response;
	}
}

?>