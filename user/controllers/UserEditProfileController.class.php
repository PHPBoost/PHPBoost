<?php
/*##################################################
 *                       UserEditProfileController.class.php
 *                            -------------------
 *   begin                : October 09, 2011
 *   copyright            : (C) 2011 K�vin MASSY
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

	public function execute(HTTPRequest $request)
	{
		$this->init();
		
		$user_id = $request->get_getint('user_id', AppContext::get_current_user()->get_attribute('user_id'));
		
		try {
			$this->user = UserService::get_user('WHERE user_aprob = 1 AND user_id=:id', array('id' => $user_id));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_member();
			DispatchManager::redirect($error_controller);
		}
		
		if (!$this->check_authorizations($user_id))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
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
		$form = new HTMLForm('member_edit_profile');
		
		$fieldset = new FormFieldsetHTML('edit_profile', $this->lang['profile.edit']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('email', $this->lang['email'], $this->user->get_email(), array(
			'class' => 'text', 'maxlength' => 255, 'description' => LangLoader::get_message('valid', 'main')),
		array(new FormFieldConstraintMailAddress(), new FormFieldConstraintMailExist($this->user->get_id()))
		));
		
		$fieldset->add_field(new FormFieldPasswordEditor('old_password', $this->lang['password.old'], '', array(
			'class' => 'text', 'maxlength' => 25, 'description' => $this->lang['password.old.explain']))
		);
		
		$fieldset->add_field($new_password = new FormFieldPasswordEditor('new_password', $this->lang['password.new'], ''));
		$fieldset->add_field($new_password_bis = new FormFieldPasswordEditor('new_password_bis', $this->lang['password.confirm'], ''));
		
		$fieldset->add_field(new FormFieldCheckbox('user_hide_mail', $this->lang['email.hide'], !$this->user->get_show_email()));
		
		$fieldset->add_field(new FormFieldCheckbox('delete_account', $this->lang['delete-account'], FormFieldCheckbox::UNCHECKED));
		
		$options_fieldset = new FormFieldsetHTML('options', LangLoader::get_message('options', 'main'));
		$form->add_fieldset($options_fieldset);
		
		$options_fieldset->add_field(new FormFieldTimezone('timezone', $this->lang['timezone.choice'], $this->user->get_timezone(), array('description' => $this->lang['timezone.choice.explain'])));
		
		if (!$this->user_accounts_config->is_users_theme_forced())
		{
			$options_fieldset->add_field(new FormFieldThemesSelect('theme', $this->lang['theme'], $this->user->get_theme(),
				array('check_authorizations' => true, 'events' => array('change' => $this->build_javascript_picture_themes()))
			));
			$options_fieldset->add_field(new FormFieldFree('preview_theme', $this->lang['theme.preview'], '<img id="img_theme" src="'. Url::to_rel($this->get_picture_theme($this->user->get_theme())) .'" alt="" style="vertical-align:top; max-height:180px;" />'));
		}
		
		$options_fieldset->add_field(new FormFieldEditors('text-editor', $this->lang['text-editor'], $this->user->get_editor()));
		
		$options_fieldset->add_field(new FormFieldLangsSelect('lang', $this->lang['lang'], $this->user->get_locale(), array('check_authorizations' => true)));	
		
		$member_extended_field = new MemberExtendedField();
		$member_extended_field->set_template($form);
		$member_extended_field->set_user_id($this->user->get_id());
		MemberExtendedFieldsService::display_form_fields($member_extended_field);
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_constraint(new FormConstraintFieldsEquality($new_password, $new_password_bis));
		$form->add_button($this->submit_button);

		$this->form = $form;
	}
	
	private function save()
	{
		$user_id = $this->user->get_id();
		
		if ($this->form->get_value('delete_account'))
		{
			UserService::delete_account('WHERE user_id=:user_id', array('user_id' => $user_id));
		}
		else
		{
			if (!$this->form->field_is_disabled('theme'))
			{
				$this->user->set_theme($this->form->get_value('theme')->get_raw_value());
			}
			
			$this->user->set_email($this->form->get_value('email'));
			$this->user->set_locale($this->form->get_value('lang')->get_raw_value());
			$this->user->set_timezone($this->form->get_value('timezone')->get_raw_value());
			$this->user->set_editor($this->form->get_value('text-editor')->get_raw_value());
			$this->user->set_show_email(!$this->form->get_value('email.hide'));
			UserService::update($this->user, 'WHERE user_id=:id', array('id' => $user_id));
		}
		
		MemberExtendedFieldsService::register_fields($this->form, $user_id);
		
		$this->tpl->put('MSG', MessageHelper::display($this->lang['profile.edit.success'], MessageHelper::SUCCESS));
		
		$old_password = $this->form->get_value('old_password');
		$new_password = $this->form->get_value('new_password');
		if (!empty($old_password) && !empty($new_password))
		{
			$old_password_hashed = KeyGenerator::string_hash($old_password);
			$user_authentification = UserService::get_user_authentification('WHERE user_id=:user_id', array('user_id' => $user_id));
			if ($old_password_hashed == $user_authentification->get_password_hashed())
			{
				UserService::change_password(KeyGenerator::string_hash($new_password), 'WHERE user_id=:user_id', array('user_id' => $user_id));
			}
			else
			{
				$this->tpl->put('MSG', MessageHelper::display($this->lang['profile.edit.password.error'], MessageHelper::NOTICE));
			}
		}
		
		StatsCache::invalidate();
	}

	private function build_response()
	{
		$response = new UserDisplayResponse();
		$response->set_page_title($this->lang['profile.edit']);
		$response->add_breadcrumb($this->lang['user'], UserUrlBuilder::users()->absolute());
		$response->add_breadcrumb($this->lang['profile.edit'], UserUrlBuilder::edit_profile($this->user->get_id())->absolute());
		return $response->display($this->tpl);
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
	
	private function get_picture_theme($user_theme)
	{
		$pictures = ThemeManager::get_theme($user_theme)->get_configuration()->get_pictures();
		return PATH_TO_ROOT .'/templates/' . $user_theme . '/' . $pictures[0];
	}
}
?>