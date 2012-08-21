<?php
/*##################################################
 *                       UserRegistrationController.class.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class UserRegistrationController extends AbstractController
{
	private $tpl;
	
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

	public function execute(HTTPRequestCustom $request)
	{
		$this->get_right_controller_regarding_authorizations();
		$this->init();
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
		}
		
		$this->tpl->put('FORM', $this->form->display());
		return $this->build_response($this->tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('user-common');
		$this->tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$this->tpl->add_lang($this->lang);
		$this->user_accounts_config = UserAccountsConfig::load();
	}	
	
	private function build_form()
	{
		$form = new HTMLForm('member-registration');
		
		$fieldset = new FormFieldsetHTML('registration', $this->lang['registration']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldHTML('validation_method', $this->get_accounts_validation_method_explain()));
		
		$fieldset->add_field(new FormFieldTextEditor('login', $this->lang['pseudo'], '', array(
			'class' => 'text', 'maxlength' => 25, 'size' => 25, 'description' => $this->lang['pseudo.explain'], 'required' => true),
			array(new FormFieldConstraintLengthRange(3, 25), new FormFieldConstraintLoginExist())
		));		
		$fieldset->add_field(new FormFieldTextEditor('email', $this->lang['email'], '', array(
			'class' => 'text', 'maxlength' => 255, 'description' => LangLoader::get_message('valid', 'main'), 'required' => true),
			array(new FormFieldConstraintMailAddress(), new FormFieldConstraintMailExist())
		));
		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['password'], '', array(
			'class' => 'text', 'maxlength' => 25, 'description' => $this->lang['password.explain'], 'required' => true),
			array(new FormFieldConstraintLengthRange(6, 12))
		));
		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['password.confirm'], '', array(
			'class' => 'text', 'maxlength' => 25, 'required' => true),
			array(new FormFieldConstraintLengthRange(6, 12))
		));
		
		if ($this->user_accounts_config->is_registration_captcha_enabled())
		{
			$captcha = new Captcha();
			$captcha->set_difficulty($this->user_accounts_config->get_registration_captcha_difficulty());
			$fieldset->add_field(new FormFieldCaptcha('captcha', $captcha));
		}
		
		$fieldset->add_field(new FormFieldCheckbox('user_hide_mail', $this->lang['email.hide'], FormFieldCheckbox::CHECKED));
		
		$options_fieldset = new FormFieldsetHTML('options', LangLoader::get_message('options', 'main'));
		$form->add_fieldset($options_fieldset);
		
		$options_fieldset->add_field(new FormFieldTimezone('timezone', $this->lang['timezone.choice'], GeneralConfig::load()->get_site_timezone(), array('description' => $this->lang['timezone.choice.explain'])));
		
		if (!$this->user_accounts_config->is_users_theme_forced())
		{
			$options_fieldset->add_field(new FormFieldThemesSelect('theme', $this->lang['theme'], $this->user_accounts_config->get_default_theme(),
				array('check_authorizations' => true, 'events' => array('change' => $this->build_javascript_picture_themes()))
			));
			$options_fieldset->add_field(new FormFieldFree('preview_theme', $this->lang['theme.preview'], '<img id="img_theme" src="'. $this->get_picture_theme() .'" alt="" style="vertical-align:top; max-height:180px;" />'));
		}
		
		$options_fieldset->add_field(new FormFieldEditors('text-editor', $this->lang['text-editor'], ContentFormattingConfig::load()->get_default_editor()));
		
		$options_fieldset->add_field(new FormFieldLangsSelect('lang', $this->lang['lang'], $this->user_accounts_config->get_default_lang(), array('check_authorizations' => true)));	
		
		$member_extended_field = new MemberExtendedField();
		$member_extended_field->set_template($form);
		MemberExtendedFieldsService::display_form_fields($member_extended_field);
		
		$agreement_fieldset = new FormFieldsetHTML('agreement_fieldset', $this->lang['agreement']);
    	$form->add_fieldset($agreement_fieldset);
    	
		$agreement = new FormFieldHTML('agreement.required', $this->lang['agreement.agree.required'] . '<br /><br />');
		$agreement_fieldset->add_field($agreement);

		$agreement = new FormFieldHTML('agreement', 
			'<div style="width:auto;height:250px;overflow-y:scroll;border:1px solid #DFDFDF;background-color:#F1F4F1;margin-bottom:10px;">' . $this->user_accounts_config->get_registration_agreement() . '</div>'
		);
		$agreement_fieldset->add_field($agreement);
		
		$agreement_fieldset->add_field(new FormFieldCheckbox('agree', $this->lang['agreement.agree'], 
			FormFieldCheckbox::UNCHECKED, 
			array('required' => $this->lang['agreement.agree.required'])
		));
    	
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));
		$form->add_button($this->submit_button);

		$this->form = $form;
	}
	
	private function get_accounts_validation_method_explain()
	{
		if ($this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::MAIL_USER_ACCOUNTS_VALIDATION)
		{
			return '<strong>'. $this->lang['registration.validation.mail.explain'] . '<strong>';
		}
		elseif ($this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION)
		{
			return '<strong>'. $this->lang['registration.validation.administrator.explain'] . '<strong>';
		}
		else
		{
			return '';
		}
	}
	
	private function save()
	{
		$activation_key = $this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::MAIL_USER_ACCOUNTS_VALIDATION ? KeyGenerator::generate_key(15) : '';
		$user_aprobation = $this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::AUTOMATIC_USER_ACCOUNTS_VALIDATION ? '1' : '0';

		$user_authentification = new UserAuthentification(TextHelper::htmlspecialchars($this->form->get_value('login')), $this->form->get_value('password'));
		$user = new User();
		$user->set_level(User::MEMBER_LEVEL);
		$user->set_email($this->form->get_value('email'));
		$user->set_show_email(!$this->form->get_value('user_hide_mail'));
		$user->set_locale($this->form->get_value('lang')->get_raw_value());
		$user->set_timezone($this->form->get_value('timezone')->get_raw_value());
		$user->set_editor($this->form->get_value('text-editor')->get_raw_value());
		$user->set_approbation($user_aprobation);
		$user->set_approbation_pass($activation_key);
		$user_id = UserService::create($user_authentification, $user);
		
		if (!$this->form->field_is_disabled('theme'))
		{
			$user->set_theme($this->form->get_value('theme')->get_raw_value());
		}
		
		try {
			MemberExtendedFieldsService::register_fields($this->form, $user_id);
			
			UserRegistrationService::send_email_confirmation($user_id, $user->get_email(), $user_authentification->get_login(), $user_authentification->get_login(), $this->form->get_value('password'), $activation_key);
			
			StatsCache::invalidate();
			
			$this->confirm_registration($user_id, $user_authentification);
		} catch (MemberExtendedFieldErrorsMessageException $e) {
			$this->tpl->put('MSG', MessageHelper::display($e->getMessage(), MessageHelper::NOTICE));
		}
	}
	
	private function confirm_registration($user_id, $user_authentification)
	{
		if ($this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::MAIL_USER_ACCOUNTS_VALIDATION)
		{
			$this->tpl->put('MSG', MessageHelper::display($this->lang['registration.success.mail-validation'], MessageHelper::SUCCESS));
		}
		elseif ($this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION)
		{
			$this->tpl->put('MSG', MessageHelper::display($this->lang['registration.success.administrator-validation'], MessageHelper::SUCCESS));
		}
		else
		{
			UserRegistrationService::connect_user($user_id, $user_authentification->get_password_hashed());
			AppContext::get_response()->redirect(Environment::get_home_page());
		}
	}

	private function build_response(View $view)
	{
		$title = $this->lang['registration'];
		$response = new UserDisplayResponse();
		$response->set_page_title($title);
		$response->add_breadcrumb($this->lang['user'], UserUrlBuilder::users()->absolute());
		$response->add_breadcrumb($title, UserUrlBuilder::registration()->absolute());
		return $response->display($view);
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
		foreach (ThemeManager::get_activated_themes_map() as $theme)
		{
			$text .= 'theme["' . $theme->get_id() . '"] = "' . Url::to_rel($this->get_picture_theme($theme->get_id())) . '";' . "\n";
		}
		$text .= 'var theme_id = HTMLForms.getField("theme").getValue(); document.images[\'img_theme\'].src = theme[theme_id];';
		return $text;
	}
	
	private function get_picture_theme()
	{
		$theme_id = $this->user_accounts_config->get_default_theme();
		$pictures = ThemeManager::get_theme($theme_id)->get_configuration()->get_pictures();
		return TPL_PATH_TO_ROOT .'/templates/' . $theme_id . '/' . $pictures[0];
	}
}
?>