<?php
/*##################################################
 *                               MemberUserEditorExtendedField.class.php
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
 
class MemberUserEditorExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('regex', 'possible_values', 'default_values'));
		$this->set_name(LangLoader::get_message('type.user-editor','admin-extended-fields-common'));
		$this->field_used_once = true;
		$this->field_used_phpboost_config = true;
	}
	
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$value = ContentFormattingConfig::load()->get_default_editor();
		$fieldset->add_field(new FormFieldSimpleSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), $value,
		array(
			new FormFieldSelectChoiceOption('BBCode', '1'),
			new FormFieldSelectChoiceOption('TinyMCE', '2'),
		),
			array('description' => $member_extended_field->get_description(), 'required' =>(bool)$member_extended_field->get_required())
		));	
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$member_value = $member_extended_field->get_value();
		$value = !empty($member_value) ? $member_value : ContentFormattingConfig::load()->get_default_editor();
		$fieldset->add_field(new FormFieldSimpleSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), $value,
		array(
			new FormFieldSelectChoiceOption('BBCode', '1'),
			new FormFieldSelectChoiceOption('TinyMCE', '2'),
		),
			array('description' => $member_extended_field->get_description(), 'required' =>(bool)$member_extended_field->get_required())
		));
	}
	
	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		$value = $member_extended_field->get_value();
		if ($value !== null)
		{
			$fieldset->add_field(new FormFieldFree($member_extended_field->get_field_name(), $member_extended_field->get_name(), $this->get_name_editor($value)));
		}
	}
	
	public function return_value(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		return $form->get_value($field_name)->get_raw_value();
	}
	
	private function get_name_editor($value)
	{
		switch ($value) 
		{
			case 1:
				return 'BBCode';
				break;
			case 2:
				return 'TinyMCE';
				break;		
			default:
				return '';
		}
	}
}
?>