<?php
/*##################################################
 *                               MemberUserPMToMailExtendedField.class.php
 *                            -------------------
 *   begin                : September 19, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 
class MemberUserPMToMailExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('field_required', 'regex', 'possible_values', 'default_value'));
		$this->set_name(LangLoader::get_message('type.user_pmtomail','admin-user-common'));
		$this->field_used_once = true;
		$this->field_used_phpboost_config = true;
	}
	
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$fieldset->add_field(new FormFieldCheckbox($member_extended_field->get_field_name(), $member_extended_field->get_name(), false, 
			array('description' => $member_extended_field->get_description(), 'required' => (bool)$member_extended_field->get_required())
		));
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$fieldset->add_field(new FormFieldCheckbox($member_extended_field->get_field_name(), $member_extended_field->get_name(), $member_extended_field->get_value(), 
			array('description' => $member_extended_field->get_description(), 'required' => (bool)$member_extended_field->get_required())
		));
	}
	
	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		//The field is not displayed in the member profile
		return false;
	}
	
	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		if ($form->has_field($field_name))
			return (int)$form->get_value($field_name);
		
		return '';
	}
}
?>