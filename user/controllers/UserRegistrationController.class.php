<?php
/*##################################################
 *                       UserRegistrationController.class.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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
		$form = new HTMLForm(__CLASS__);
		$this->member_extended_fields_service = new MemberExtendedFieldsService($form);

		$fieldset = new FormFieldsetHTML('registration', $this->lang['registration']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldHTML('validation_method', $this->get_accounts_validation_method_explain()));
		
		$fieldset->add_field(new FormFieldTextEditor('login', $this->lang['pseudo'], '', array(
			'description' => $this->lang['pseudo.explain'], 'required' => true),
			array(new FormFieldConstraintLengthRange(3, 25), new FormFieldConstraintLoginExist())
		));		
		$fieldset->add_field(new FormFieldTextEditor('email', $this->lang['email'], '', array(
			'description' => LangLoader::get_message('valid', 'main'), 'required' => true),
			array(new FormFieldConstraintMailAddress(), new FormFieldConstraintMailExist())
		));
		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['password'], '', array(
			'description' => $this->lang['password.explain'], 'required' => true),
			array(new FormFieldConstraintLengthRange(6, 12))
		));
		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['password.confirm'], '', array(
			'required' => true),
			array(new FormFieldConstraintLengthRange(6, 12))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('user_hide_mail', $this->lang['email.hide'], FormFieldCheckbox::CHECKED));
		
		$options_fieldset = new FormFieldsetHTML('options', LangLoader::get_message('options', 'main'));
		$form->add_fieldset($options_fieldset);
		
		$options_fieldset->add_field(new FormFieldTimezone('timezone', $this->lang['timezone.choice'], GeneralConfig::load()->get_site_timezone(), array('description' => $this->lang['timezone.choice.explain'])));
		
		if (count(ThemesManager::get_activated_and_authorized_themes_map()) > 1)
		{
			$options_fieldset->add_field(new FormFieldThemesSelect('theme', $this->lang['theme'], $this->user_accounts_config->get_default_theme(),
				array('check_authorizations' => true, 'events' => array('change' => $this->build_javascript_picture_themes()))
			));
			$options_fieldset->add_field(new FormFieldFree('preview_theme', $this->lang['theme.preview'], '<img id="img_theme" src="'. $this->get_picture_theme() .'" alt="" style="vertical-align:top; max-height:180px;" />'));
		}
		
		$options_fieldset->add_field(new FormFieldEditors('text-editor', $this->lang['text-editor'], ContentFormattingConfig::load()->get_default_editor()));
		
		$options_fieldset->add_field(new FormFieldLangsSelect('lang', $this->lang['lang'], $this->user_accounts_config->get_default_lang(), array('check_authorizations' => true)));	
		
		$this->member_extended_fields_service->display_form_fields();

    	$agreement_text = FormatingHelper::second_parse($this->user_accounts_config->get_registration_agreement());
    	if (!empty($agreement_text))
    	{
    		$agreement_fieldset = new FormFieldsetHTML('agreement_fieldset', $this->lang['agreement']);
    		$form->add_fieldset($agreement_fieldset);
    	
			$agreement = new FormFieldHTML('agreement.required', $this->lang['agreement.agree.required'] . '<br /><br />');
			$agreement_fieldset->add_field($agreement);
	
			$agreement = new FormFieldHTML('agreement', '<div id="id-message-helper" class="notice" style="max-width:none;width:90%;max-height:250px;overflow-y:auto;">' . $agreement_text . '</div>');
			$agreement_fieldset->add_field($agreement);
			
			$agreement_fieldset->add_field(new FormFieldCheckbox('agree', $this->lang['agreement.agree'], 
				FormFieldCheckbox::UNCHECKED, 
				array('required' => $this->lang['agreement.agree.required'])
			));
    	}
    	
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

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
		$has_error = false;
		
		$registration_pass = $this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::MAIL_USER_ACCOUNTS_VALIDATION ? KeyGenerator::generate_key(15) : '';
		$user_aprobation = $this->user_accounts_config->get_member_accounts_validation_method() == UserAccountsConfig::AUTOMATIC_USER_ACCOUNTS_VALIDATION;

		$user = new User();
		$user->set_display_name($this->form->get_value('login'));
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
		
		$auth_method = new PHPBoostAuthenticationMethod($this->form->get_value('login'), $this->form->get_value('password'));
		$auth_method->set_association_parameters($user_aprobation, $registration_pass);

		try {
			$user_id = UserService::create($user, $auth_method, $this->member_extended_fields_service);
		} catch (MemberExtendedFieldErrorsMessageException $e) {
			$has_error = true;
			$this->tpl->put('MSG', MessageHelper::display($e->getMessage(), MessageHelper::NOTICE));
		}
			
		if (!$has_error)
		{
			UserRegistrationService::send_email_confirmation($user_id, $user->get_email(), $this->form->get_value('login'), $this->form->get_value('login'), $this->form->get_value('password'), $registration_pass);
				
			$this->confirm_registration($user_id);
		}
	}
	
	private function confirm_registration($user_id)
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
		$title = $this->lang['registration'];
		$response = new UserDisplayResponse();
		$response->set_page_title($title);
		$response->add_breadcrumb($this->lang['user'], UserUrlBuilder::users()->rel());
		$response->add_breadcrumb($title, UserUrlBuilder::registration()->rel());
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