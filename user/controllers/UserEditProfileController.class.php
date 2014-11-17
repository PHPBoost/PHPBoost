<?php
/*##################################################
 *                       UserEditProfileController.class.php
 *                            -------------------
 *   begin                : October 09, 2011
 *   copyright            : (C) 2011 K�vin MASSY
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

class UserEditProfileController extends AbstractController
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
	
	private $user;
	private $internal_auth_infos;
	private $user_auth_types;
	
	private $member_extended_fields_service;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$user_id = $request->get_getint('user_id', AppContext::get_current_user()->get_id());
		
		try {
			$this->user = UserService::get_user($user_id);
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		try {
			$this->internal_auth_infos = PHPBoostAuthenticationMethod::get_auth_infos($user_id);
		} catch (RowNotFoundException $e) {
		}

		$this->user_auth_types = AuthenticationService::get_user_types_authentication($this->user->get_id());
		
		if (!$this->check_authorizations($user_id))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		$associate_type = $request->get_getvalue('associate', false);
		if ($associate_type)
		{
			if (!in_array($associate_type, $this->user_auth_types))
			{
				$authentication_method = AuthenticationService::get_authentication_method($associate_type);
				AuthenticationService::associate($authentication_method, $user_id);
				AppContext::get_response()->redirect(UserUrlBuilder::edit_profile($user_id));
			}
		}

		$dissociate_type = $request->get_getvalue('dissociate', false);
		if ($dissociate_type)
		{
			if (in_array($dissociate_type, $this->user_auth_types) && count($this->user_auth_types) > 1)
			{
				$authentication_method = AuthenticationService::get_authentication_method($dissociate_type);
				AuthenticationService::dissociate($authentication_method, $user_id);
				AppContext::get_response()->redirect(UserUrlBuilder::edit_profile($user_id));
			}
		}
		
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
		}
		
		$this->tpl->put('FORM', $this->form->display());

		return $this->build_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('user-common');
		$this->tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$this->tpl->add_lang($this->lang);
		$this->user_accounts_config = UserAccountsConfig::load();
	}
	
	private function check_authorizations()
	{
		return AppContext::get_current_user()->get_id() == $this->user->get_id() || AppContext::get_current_user()->check_level(User::ADMIN_LEVEL);
	}
	
	private function build_form()
	{
		$auth_types = AuthenticationService::get_activated_types_authentication();
		
		$form = new HTMLForm(__CLASS__);
		$this->member_extended_fields_service = new MemberExtendedFieldsService($form);
		
		$fieldset = new FormFieldsetHTML('edit_profile', $this->lang['profile.edit']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('display_name', $this->lang['display_name'], $this->user->get_display_name(), array('description'=> $this->lang['display_name.explain'], 'required' => true),
			array(new FormFieldConstraintLengthRange(3, 20))
		));	

		$fieldset->add_field(new FormFieldTextEditor('email', $this->lang['email'], $this->user->get_email(), array(
			'required' => true, 'description' => LangLoader::get_message('valid', 'main')),
			array(new FormFieldConstraintMailAddress(), new FormFieldConstraintMailExist($this->user->get_id()))
		));
				
		$fieldset->add_field(new FormFieldCheckbox('user_hide_mail', $this->lang['email.hide'], !$this->user->get_show_email()));

		$fieldset->add_field(new FormFieldCheckbox('delete_account', $this->lang['delete-account'], FormFieldCheckbox::UNCHECKED));
		


		/* ************* */


		$connect_fieldset = new FormFieldsetHTML('connect', 'connect');
		$form->add_fieldset($connect_fieldset);

		if (in_array(PHPBoostAuthenticationMethod::AUTHENTICATION_METHOD, $this->user_auth_types))
		{
			$connect_fieldset->add_field(new FormFieldFree('internal_auth', 'internal <i class="fa fa-success"></i>', '<a onclick="javascript:HTMLForms.getField(\'custom_login\').enable();HTMLForms.getField(\'password\').enable();HTMLForms.getField(\'password_bis\').enable();HTMLForms.getField(\'old_password\').enable();">Modifier</a>'));
		}
		else
		{
			$connect_fieldset->add_field(new FormFieldFree('internal_auth', 'internal <i class="fa fa-error"></i>', '<a onclick="javascript:HTMLForms.getField(\'custom_login\').enable();HTMLForms.getField(\'password\').enable();HTMLForms.getField(\'password_bis\').enable();">Créer une authentification interne</a>'));
		}

		$connect_fieldset->add_field(new FormFieldCheckbox('custom_login', $this->lang['login.custom'], false, array('description'=> $this->lang['login.custom.explain'], 'hidden' => true, 'events' => array('click' => '
			if (HTMLForms.getField("custom_login").getValue()) {
				HTMLForms.getField("login").enable();
			} else { 
				HTMLForms.getField("login").disable();
			}'
		))));

		$connect_fieldset->add_field(new FormFieldTextEditor('login', $this->lang['login'], $this->internal_auth_infos['login'], array('hidden' => true),
			array(new FormFieldConstraintLengthRange(3, 25), new FormFieldConstraintPHPBoostAuthLoginExists($this->user->get_id()))
		));

		$connect_fieldset->add_field(new FormFieldPasswordEditor('old_password', $this->lang['password.old'], '', array(
			'description' => $this->lang['password.old.explain'], 'hidden' => true))
		);

		$connect_fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['password'], '', array(
			'description' => $this->lang['password.explain'], 'hidden' => true),
			array(new FormFieldConstraintLengthRange(6, 12))
		));
		$connect_fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['password.confirm'], '', array('hidden' => true),
			array(new FormFieldConstraintLengthRange(6, 12))
		));

		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));

		if (in_array(FacebookAuthenticationMethod::AUTHENTICATION_METHOD, $this->user_auth_types))
		{
			$connect_fieldset->add_field(new FormFieldFree('fb_auth', 'FB <i class="fa fa-success"></i>', '<a href="'. UserUrlBuilder::edit_profile($this->user->get_id())->absolute() .'?dissociate=fb">dissocier votre compte Facebook</a>'));
		}
		else
		{
			$connect_fieldset->add_field(new FormFieldFree('fb_auth', 'FB <i class="fa fa-error"></i>', '<a href="'. UserUrlBuilder::edit_profile($this->user->get_id())->absolute() .'?associate=fb">associer votre compte Facebook</a>'));
		}


		/* ************* */


		$options_fieldset = new FormFieldsetHTML('options', LangLoader::get_message('options', 'main'));
		$form->add_fieldset($options_fieldset);
		
		$options_fieldset->add_field(new FormFieldTimezone('timezone', $this->lang['timezone.choice'], 
			$this->user->get_timezone(), array('description' => $this->lang['timezone.choice.explain'])
		));
		
		if (count(ThemesManager::get_activated_and_authorized_themes_map()) > 1)
		{
			$options_fieldset->add_field(new FormFieldThemesSelect('theme', $this->lang['theme'], $this->user->get_theme(),
				array('check_authorizations' => true, 'events' => array('change' => $this->build_javascript_picture_themes()))
			));
			$options_fieldset->add_field(new FormFieldFree('preview_theme', $this->lang['theme.preview'], '<img id="img_theme" src="'. $this->get_picture_theme($this->user->get_theme()) .'" alt="" style="vertical-align:top; max-height:180px;" />'));
		}
		
		$options_fieldset->add_field(new FormFieldEditors('text-editor', $this->lang['text-editor'], $this->user->get_editor()));
		
		$options_fieldset->add_field(new FormFieldLangsSelect('lang', $this->lang['lang'], $this->user->get_locale(), array('check_authorizations' => true)));	
		
		$this->member_extended_fields_service->display_form_fields($this->user->get_id());
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}
	
	private function save()
	{
		$has_error = false;
		
		$user_id = $this->user->get_id();
		
		if ($this->form->get_value('delete_account'))
		{
			UserService::delete_by_id($user_id);
		}
		else
		{
			if ($this->form->has_field('theme'))
			{
				$this->user->set_theme($this->form->get_value('theme')->get_raw_value());
			}
			
			$this->user->set_locale($this->form->get_value('lang')->get_raw_value());
			$this->user->set_display_name($this->form->get_value('display_name'));
			$this->user->set_email($this->form->get_value('email'));
			$this->user->set_locale($this->form->get_value('lang')->get_raw_value());
			$this->user->set_editor($this->form->get_value('text-editor')->get_raw_value());
			$this->user->set_show_email(!$this->form->get_value('user_hide_mail'));
			$this->user->set_timezone($this->form->get_value('timezone')->get_raw_value());
			
			try {
				UserService::update($this->user, $this->member_extended_fields_service);
			} catch (MemberExtendedFieldErrorsMessageException $e) {
				$has_error = true;
				$this->tpl->put('MSG', MessageHelper::display($e->getMessage(), MessageHelper::NOTICE));
			}

			$login = $this->form->get_value('email');
			if ($this->form->get_value('custom_login', false))
			{
				$login = $this->form->get_value('login');
			}

			$password = $this->form->get_value('password');
			if ($this->internal_auth_infos === null && !empty($password))
			{
				$authentication_method = new PHPBoostAuthenticationMethod($login, $password);
				AuthenticationService::associate($authentication_method, $user_id);
			}
			elseif (!empty($password))
			{
				$old_password = $this->form->get_value('old_password');
				if (!empty($old_password))
				{
					$old_password_hashed = KeyGenerator::string_hash($old_password);

					if ($old_password_hashed == $this->internal_auth_infos['password'])
					{
						PHPBoostAuthenticationMethod::update_auth_infos($user_id, $login, $this->internal_auth_infos['approved'], KeyGenerator::string_hash($password));
						$has_error = false;
					}
					else
					{
						$has_error = true;
						$this->tpl->put('MSG', MessageHelper::display($this->lang['profile.edit.password.error'], MessageHelper::NOTICE));
					}
				}
			}
			else
			{
				PHPBoostAuthenticationMethod::update_auth_infos($user_id, $login);
			}
		}
		
		if (!$has_error)
		{
			AppContext::get_response()->redirect(UserUrlBuilder::edit_profile($user_id));
		}
	}

	private function build_response()
	{
		$response = new UserDisplayResponse();
		$response->set_page_title($this->lang['profile.edit']);
		$response->add_breadcrumb($this->lang['user'], UserUrlBuilder::users()->rel());
		$response->add_breadcrumb($this->lang['profile.edit'], UserUrlBuilder::edit_profile($this->user->get_id())->rel());
		return $response->display($this->tpl);
	}
	
	private function build_javascript_picture_themes()
	{
		$text = 'var theme = new Array;' . "\n";
		foreach (ThemesManager::get_activated_themes_map() as $theme)
		{
			$picture = $theme->get_configuration()->get_first_picture();
			$text .= 'theme["' . $theme->get_id() . '"] = "' . TPL_PATH_TO_ROOT .'/templates/' . $theme->get_id() . '/' . $picture . '";' . "\n";
		}
		$text .= 'var theme_id = HTMLForms.getField("theme").getValue(); document.images[\'img_theme\'].src = theme[theme_id];';
		return $text;
	}
	
	private function get_picture_theme($user_theme)
	{
		$picture = ThemesManager::get_theme($user_theme)->get_configuration()->get_first_picture();
		return TPL_PATH_TO_ROOT .'/templates/' . $user_theme . '/' . $picture;
	}
}
?>