<?php
/*##################################################
 *                               MemberUserThemeExtendedFieldType.class.php
 *                            -------------------
 *   begin                : December 09, 2010
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
 
class MemberUserThemeExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('regex', 'possible_values', 'default_values'));
		$this->set_name(LangLoader::get_message('type.user-theme-choice','admin-extended-fields-common'));
		$this->field_used_once = true;
		$this->field_used_phpboost_config = true;
	}
	
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$value = UserAccountsConfig::load()->get_default_theme();
		$fieldset->add_field(new FormFieldSimpleSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), $value,
			$this->list_theme(),
			array('description' => $member_extended_field->get_description(), 'required' =>(bool)$member_extended_field->get_required(), 'events' => array('change' => $this->construct_javascript_picture_theme() . 
			
			'var theme_id = HTMLForms.getField("'. $member_extended_field->get_field_name() .'").getValue();
			document.images[\'img_theme\'].src = theme[theme_id];'))
		));
		$fieldset->add_field(new FormFieldFree('preview_theme', $this->lang['preview'], '<img id="img_theme" src="'. $this->get_picture_theme() .'" alt="" style="vertical-align:top; max-height:180px;" />'));
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$member_value = $member_extended_field->get_value();
		$value = !empty($member_value) ? $member_value : UserAccountsConfig::load()->get_default_theme();
		$fieldset->add_field(new FormFieldSimpleSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), $value,
			$this->list_theme(),
			array('description' => $member_extended_field->get_description(), 'required' =>(bool)$member_extended_field->get_required(), 'events' => array('change' => $this->construct_javascript_picture_theme() . 
			
			'var theme_id = HTMLForms.getField("'. $member_extended_field->get_field_name() .'").getValue();
			document.images[\'img_theme\'].src = theme[theme_id];'))
		));
		$fieldset->add_field(new FormFieldFree('preview_theme', $this->lang['preview'], '<img id="img_theme" src="'. $this->get_picture_theme() .'" alt="" style="vertical-align:top; max-height:180px;" />'));
	}
	
	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$member_value = $member_extended_field->get_value();
		$value = !empty($member_value) ? $member_value : UserAccountsConfig::load()->get_default_theme();
		$info_theme = @parse_ini_file(PATH_TO_ROOT . '/templates/' . $value, '/config/' . get_ulang() . '/config.ini');
		if (!empty($info_theme['name']))
		{
			$fieldset->add_field(new FormFieldFree($member_extended_field->get_field_name(), $member_extended_field->get_name(), $info_theme['name']));
		}
	}
	
	public function return_value(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		return $form->get_value($field_name)->get_raw_value();
	}
	
	private function list_theme()
	{
		$choices_list = array();
		$user_accounts_config = UserAccountsConfig::load();
		if (!$user_accounts_config->is_users_theme_forced()) // If users can choose the theme they use
		{
			foreach (ThemeManager::get_activated_themes_map() as $id => $value) 
			{
				if (UserAccountsConfig::load()->get_default_theme() == $id || (AppContext::get_user()->check_auth($value->get_authorizations(), AUTH_THEME)))
				{
					$choices_list[] = new FormFieldSelectChoiceOption($value->get_configuration()->get_name(), $id);
				}
			}
		}
		else
		{
			$choices_list[] = new FormFieldSelectChoiceOption('Base', 'base');
		}
		return $choices_list;
	}
	
	private function get_picture_theme($theme_id = null)
	{
		$theme_id = $theme_id !== null ? $theme_id : get_utheme();
		$pictures = ThemeManager::get_theme($theme_id)->get_configuration()->get_pictures();
		return PATH_TO_ROOT .'/templates/' . $theme_id . '/' . $pictures[0];
	}
	
	private function construct_javascript_picture_theme()
	{
		$text = 'var theme = new Array;' . "\n";
		foreach (ThemeManager::get_activated_themes_map() as $theme)
		{
			$text .= 'theme["' . $theme->get_id() . '"] = "' . $this->get_picture_theme($theme->get_id()) . '";' . "\n";
		}
		return $text;
	}
}
?>