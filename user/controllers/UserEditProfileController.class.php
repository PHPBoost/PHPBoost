<?php
/*##################################################
 *                       UserEditProfileController.class.php
 *                            -------------------
 *   begin                : October 09, 2011
 *   copyright            : (C) 2011 Kévin MASSY
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

	public function execute(HTTPRequest $request)
	{
		$this->init();
		
		$user_id = $request->get_getint('user_id', AppContext::get_current_user()->get_attribute('user_id'));
		if (!$this->check_authorizations($user_id))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		else if (!UserService::user_exists_by_id($user_id))
		{
			$error_controller = PHPBoostErrors::unexisting_member();
			DispatchManager::redirect($error_controller);
		}
		
		$this->build_form($user_id);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save($user_id);
		}
		
		$this->tpl->put('FORM', $this->form->display());

		return $this->build_response($user_id);
	}

	private function init()
	{
		$this->lang = LangLoader::get('user-common');
		$this->tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$this->tpl->add_lang($this->lang);
		$this->user_accounts_config = UserAccountsConfig::load();
	}
	
	private function check_authorizations($user_id)
	{
		return AppContext::get_current_user()->get_id() == $user_id || AppContext::get_current_user()->check_level(User::ADMIN_LEVEL);
	}
	
	private function build_form($user_id)
	{
		$form = new HTMLForm('member_edit_profile');
		
		$fieldset = new FormFieldsetHTML('edit_profile', $this->lang['profile.edit']);
		$form->add_fieldset($fieldset);
		
		$row = PersistenceContext::get_sql()->query_array(DB_TABLE_MEMBER, '*', "WHERE user_aprob = 1 AND user_id = '" . $user_id . "' " , __LINE__, __FILE__);

		$fieldset->add_field(new FormFieldTextEditor('email', $this->lang['email'], $row['user_mail'], array(
			'class' => 'text', 'maxlength' => 255, 'description' => LangLoader::get_message('valid', 'main')),
		array(new FormFieldConstraintMailAddress(), new FormFieldConstraintMailExist($row['user_id']))
		));
		
		$fieldset->add_field(new FormFieldPasswordEditor('old_password', $this->lang['password.old'], '', array(
			'class' => 'text', 'maxlength' => 25, 'description' => $this->lang['password.old.explain']))
		);
		
		$fieldset->add_field($new_password = new FormFieldPasswordEditor('new_password', $this->lang['password.new'], ''));
		$fieldset->add_field($new_password_bis = new FormFieldPasswordEditor('new_password_bis', $this->lang['password.confirm'], ''));
		
		$fieldset->add_field(new FormFieldCheckbox('user_hide_mail', $this->lang['email.hide'], $row['user_show_mail']));
		
		$fieldset->add_field(new FormFieldCheckbox('delete_account', $this->lang['delete-account'], FormFieldCheckbox::UNCHECKED));
		
		$options_fieldset = new FormFieldsetHTML('options', LangLoader::get_message('options', 'main'));
		$form->add_fieldset($options_fieldset);
		
		$options_fieldset->add_field(new FormFieldTimezone('timezone', $this->lang['timezone.choice'], $row['user_timezone'], array('description' => $this->lang['timezone.choice.explain'])));
		
		if (!$this->user_accounts_config->is_users_theme_forced())
		{
			$options_fieldset->add_field(new FormFieldSimpleSelectChoice('theme', $this->lang['theme'], $row['user_theme'],
				$this->build_themes_select_options(),
				array('events' => array('change' => $this->build_javascript_picture_themes()))
			));
			$options_fieldset->add_field(new FormFieldFree('preview_theme', $this->lang['theme.preview'], '<img id="img_theme" src="'. $this->get_picture_theme($row['user_theme']) .'" alt="" style="vertical-align:top; max-height:180px;" />'));
		}
		
		$options_fieldset->add_field(new FormFieldEditors('text-editor', $this->lang['text-editor'], $row['user_editor']));
		
		$options_fieldset->add_field(new FormFieldLangs('lang', $this->lang['lang'], $row['user_lang']));	
		
		$member_extended_field = new MemberExtendedField();
		$member_extended_field->set_template($form);
		$member_extended_field->set_user_id($user_id);
		MemberExtendedFieldsService::display_form_fields($member_extended_field);
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_constraint(new FormConstraintFieldsEquality($new_password, $new_password_bis));
		$form->add_button($this->submit_button);

		$this->form = $form;
	}
	
	private function save($user_id)
	{
		if ($this->form->get_value('delete_account'))
		{
			UserService::delete_account($user_id);
		}
		else
		{
			$theme = !$this->form->field_is_disabled('theme') ? $this->form->get_value('theme')->get_raw_value() : $this->user_accounts_config->get_default_theme();
			UserUpdateProfileService::update(
				$user_id, 
				$this->form->get_value('email'), 
				$this->form->get_value('lang')->get_raw_value(), 
				$this->form->get_value('timezone')->get_raw_value(), 
				$theme, 
				$this->form->get_value('text-editor')->get_raw_value(), 
				!$this->form->get_value('email.hide')
			);
		}
		
		MemberExtendedFieldsService::register_fields($this->form, $user_id);
		
		$this->tpl->put('MSG', MessageHelper::display($this->lang['profile.edit.success'], MessageHelper::SUCCESS));
		
		$old_password = $this->form->get_value('old_password');
		$new_password = $this->form->get_value('new_password');
		if (!empty($old_password) && !empty($new_password))
		{
			$old_password_hashed = KeyGenerator::string_hash($old_password);
			if ($old_password_hashed == UserUpdateProfileService::get_password($user_id))
			{
				UserService::change_password($user_id, KeyGenerator::string_hash($new_password));
			}
			else
			{
				$this->tpl->put('MSG', MessageHelper::display($this->lang['profile.edit.password.error'], MessageHelper::NOTICE));
			}
		}
		
		StatsCache::invalidate();
	}

	private function build_response($user_id)
	{
		$response = new UserDisplayResponse();
		$response->set_page_title($this->lang['profile.edit']);
		$response->add_breadcrumb($this->lang['user'], UserUrlBuilder::users()->absolute());
		$response->add_breadcrumb($this->lang['profile.edit'], UserUrlBuilder::edit_profile($user_id)->absolute());
		return $response->display($this->tpl);
	}
	
	private function build_themes_select_options()
	{
		$choices_list = array();
		foreach (ThemeManager::get_activated_themes_map() as $id => $value) 
		{
			if ($this->user_accounts_config->get_default_theme() == $id || (AppContext::get_current_user()->check_auth($value->get_authorizations(), AUTH_THEME)))
			{
				$choices_list[] = new FormFieldSelectChoiceOption($value->get_configuration()->get_name(), $id);
			}
		}
		return $choices_list;
	}
	
	private function build_javascript_picture_themes()
	{
		$text = 'var theme = new Array;' . "\n";
		foreach (ThemeManager::get_activated_themes_map() as $theme)
		{
			$text .= 'theme["' . $theme->get_id() . '"] = "' . $this->get_picture_theme($theme->get_id()) . '";' . "\n";
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