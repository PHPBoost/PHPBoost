<?php
/*##################################################
 *                       RegisterController.class.php
 *                            -------------------
 *   begin                : September 18, 2010 2009
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

class RegisterController extends AbstractController
{
	private $lang;
	
	private $submit_welcome_button;
	
	private $submit_button;
	
	public function execute(HTTPRequest $request)
	{
		$this->lang = LangLoader::get('main');
		
		$view = new StringTemplate('<script type="text/javascript">
				<!--
				function change_img_theme(id, value)
				{
					if(document.images )
						document.images[id].src = "../templates/" + value + "/theme/images/theme.jpg";
				}
				-->		
				</script>
				# INCLUDE form #');

		$form = $this->build_form_register();
		
		// TODO Welcome
		// $form = $this->build_form_welcome_register();
		
		if (isset($this->submit_button) && $this->submit_button->has_been_submited())
		{
			if ($form->validate())
			{
				$this->user_registration_database($form, $view);
			}
		}
	
		$view->add_lang($this->lang);
		
		$view->add_subtemplate('form', $form->display());
		return new SiteDisplayResponse($view);
	}
	
	private function user_registration_database(&$form, &$view)
	{
		
		if (UserAccountsConfig::load()->is_registration_enabled())
		{
			RegisterHelper::registeration($form);
		}
		else
		{
			// Error, registeration disable
			// Redirect ..
		}
		
	}
	
	private function confirmation_registeration()
	{
		/*
		if (UserAccountsConfig::load()->get_member_accounts_validation_method() == 1)
		{
			// Activation par mail
		}
		elseif (UserAccountsConfig::load()->get_member_accounts_validation_method() == 2)
		{
			// Activation admin
		}
		elseif (UserAccountsConfig::load()->get_member_accounts_validation_method() == 2)
		{
			// Mail envoyé
		}
		*/
	}
	
	private function warn_confirmation_register()
	{
		// TODO
		if (UserAccountsConfig::load()->get_member_accounts_validation_method() == 1)
		{
			// Activation par mail
			return '<strong>'. $this->lang['activ_mbr_mail'] . '<strong>';
		}
		elseif (UserAccountsConfig::load()->get_member_accounts_validation_method() == 2)
		{
			// Activation admin
			return '<strong>'. $this->lang['activ_mbr_admin'] . '<strong>';
		}
		
	}
	
	private function build_form_register()
	{
		if (!UserAccountsConfig::load()->is_registration_enabled())
		{
			AppContext::get_response()->redirect(Environment::get_home_page());
		}
		if(AppContext::get_user()->check_level(MEMBER_LEVEL))
		{
			// TODO return $this->already_registered();
			return $this->build_form_not_registered();
		}
		else
		{
			return $this->build_form_not_registered();
		}
	
	}
	
	private function already_registered()
	{
		// You are already registered !
		// Redirect ...
		// TODO
		
		$form = new HTMLForm('already_register');
		
		$fieldset = new FormFieldsetHTML('already_register', 'Vous êtes déjà enregistré !');
		$fieldset->set_description('Vous êtes déjà enregistré !');
		$form->add_fieldset($fieldset);
		
		return $form;
	}
	
	private function build_form_welcome_register()
	{
		$form = new HTMLForm('register_terms');
		
		$fieldset = new FormFieldsetHTML('register_terms', $this->lang['register_terms']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldHTML('agreement', UserAccountsConfig::load()->get_registration_agreement()));
		$fieldset->add_field(new FormFieldCheckbox('register_accept', $this->lang['register_accept'], false));
		
		$this->submit_welcome_button = new FormButtonDefaultSubmit();
		$form->add_button(new FormButtonDefaultSubmit());
		return $form;
	}
	
	private function build_form_not_registered()
	{
		$form = new HTMLForm('register');
		
		// Register
		$fieldset = new FormFieldsetHTML('registration', $this->lang['register']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldHTML('name', $this->warn_confirmation_register()));
		
		$fieldset->add_field(new FormFieldTextEditor('login', $this->lang['pseudo'], '', array(
			'class' => 'text', 'maxlength' => 25, 'size' => 25, 'description' => $this->lang['pseudo_how']),
			array(new FormFieldConstraintLengthRange(3, 25))
		));		
		$fieldset->add_field(new FormFieldTextEditor('mail', $this->lang['mail'], '', array(
			'class' => 'text', 'maxlength' => 255, 'description' => $this->lang['valid']),
		array(new FormFieldConstraintMailAddress())
		));
		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['password'], '', array(
			'class' => 'text', 'maxlength' => 25, 'description' => $this->lang['password_how']),
		array(new FormFieldConstraintLengthRange(6, 12))
		));
		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['confirm_password'], '', array(
			'class' => 'text', 'maxlength' => 25),
		array(new FormFieldConstraintLengthRange(6, 12))
		));
		$fieldset->add_field(new FormFieldSelectChoice('user_lang', $this->lang['choose_lang'], UserAccountsConfig::load()->get_default_lang(),
			RegisterFormUtil::return_array_lang_for_formbuilder()
		));
		$fieldset->add_field(new FormFieldCaptcha());
		
		//Options
		$fieldset2 = new FormFieldsetHTML('option', $this->lang['options']);
		$form->add_fieldset($fieldset2);
		
		$fieldset2->add_field(new FormFieldSelectChoice('user_theme', $this->lang['choose_theme'], UserAccountsConfig::load()->get_default_theme(),
			RegisterFormUtil::return_array_theme_for_formubuilder(),
			array('events' => array('change' => 'change_img_theme(\'img_theme\', HTMLForms.getField("user_theme").getValue())'))
		));
		$fieldset2->add_field(new FormFieldFree('preview_theme', $this->lang['preview'], '<img id="img_theme" src="../templates/'. UserAccountsConfig::load()->get_default_theme() .'/theme/images/theme.jpg" alt="" style="vertical-align:top" />'));
		
		$fieldset2->add_field(new FormFieldSelectChoice('user_editor', $this->lang['choose_editor'], ContentFormattingConfig::load()->get_default_editor(),
			RegisterFormUtil::return_array_editor_for_formubuilder()
		));
		$fieldset2->add_field(new FormFieldSelectChoice('user_timezone', $this->lang['timezone_choose'], GeneralConfig::load()->get_site_timezone(),
			RegisterFormUtil::return_array_timezone_for_formubuilder()
		));
		$fieldset2->add_field(new FormFieldCheckbox('user_hide_mail', $this->lang['hide_mail'], true));
		
		//Infos
		$fieldset3 = new FormFieldsetHTML('informations', $this->lang['info']);
		$form->add_fieldset($fieldset3);
		
		$fieldset3->add_field(new FormFieldTextEditor('user_web', $this->lang['web_site'], '', array(
			'class' => 'text', 'maxlength' => 255, 'description' => 'Url valide'),
		array(new FormFieldConstraintUrl())
		));
		
		$fieldset3->add_field(new FormFieldTextEditor('user_local', $this->lang['localisation'], '', array(
			'class' => 'text', 'maxlength' => 255))
		);
		
		$fieldset3->add_field(new FormFieldTextEditor('user_occupation', $this->lang['job'], '', array(
			'class' => 'text', 'maxlength' => 255))
		);
		
		$fieldset3->add_field(new FormFieldTextEditor('user_hobbies', $this->lang['hobbies'], '', array(
			'class' => 'text', 'maxlength' => 255))
		);
		
		$fieldset3->add_field(new FormFieldSelectChoice('user_sex', $this->lang['sex'], '',
			array(
				new FormFieldSelectChoiceOption('--', '0'),
				new FormFieldSelectChoiceOption($this->lang['male'], '1'),
				new FormFieldSelectChoiceOption($this->lang['female'], '2'),
			)
		));
		
		$fieldset3->add_field(new FormFieldDate('user_born', $this->lang['date_of_birth'], null, 
			array('description' => $this->lang['valid'])
		));
		
		$fieldset3->add_field(new FormFieldMultiLineTextEditor('user_sign', $this->lang['sign'], '',
		array('rows' => 4, 'cols' => 27, 'description' => $this->lang['sign_where'])
		));
		
		//Contact
		$fieldset4 = new FormFieldsetHTML('contact', $this->lang['contact']);
		$form->add_fieldset($fieldset4);
		
		$fieldset4->add_field(new FormFieldTextEditor('user_msn', 'MSN', '', array(
			'class' => 'text', 'maxlength' => 255), array(new FormFieldConstraintMailAddress()))
		);
		$fieldset4->add_field(new FormFieldTextEditor('user_yahoo', 'Yahoo', '', array(
			'class' => 'text', 'maxlength' => 255), array(new FormFieldConstraintMailAddress()))
		);
		// Avatar
		$fieldset5 = new FormFieldsetHTML('avatar_managament', $this->lang['avatar_gestion']);
		$form->add_fieldset($fieldset5);
		
		$fieldset5->add_field(new FormFieldFilePicker('avatar', $this->lang['upload_avatar'],
			array('description' => $this->lang['upload_avatar_where']
		)));
		$fieldset5->add_field(new FormFieldTextEditor('user_avatar', $this->lang['avatar_link'], '', array(
			'class' => 'text', 'maxlength' => 255, 'description' => $this->lang['avatar_link_where'])
		));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button(new FormButtonDefaultSubmit());
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));
		return $form;
	}
}

?>