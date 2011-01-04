<?php
/*##################################################
 *                               MemberMultipleChoiceExtendedField.class.php
 *                            -------------------
 *   begin                : December 08, 2010
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
 
class MemberMultipleChoiceExtendedField extends AbstractMemberExtendedField
{
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$field = array();
		$array_values = $this->possible_values($member_extended_field);
		$i = 0;
		foreach ($array_values as $values)
		{
			$field[] = new FormFieldMultipleCheckboxOption($values, $member_extended_field->get_field_name() . '_' . $i);
			$i++;
		}
		
		$fieldset->add_field(new FormFieldMultipleCheckbox($member_extended_field->get_field_name(), $member_extended_field->get_name(), $field, $this->default_values($member_extended_field)));
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$field = array();
		$array_values = $this->possible_values($member_extended_field);
		$i = 0;
		foreach ($array_values as $values)
		{
			$field[] = new FormFieldMultipleCheckboxOption($values, $member_extended_field->get_field_name() . '_' . $i);
			$i++;
		}
		$fieldset->add_field(new FormFieldMultipleCheckbox($member_extended_field->get_field_name(), $member_extended_field->get_name(), $field, $this->default_values($member_extended_field)));
	}
	
	public function return_value(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		return $form->get_value($field_name)->get_label();
	}
	
	private function possible_values(MemberExtendedField $member_extended_field)
	{
		return explode('|', $member_extended_field->get_possible_values());
	}
	
	private function default_values(MemberExtendedField $member_extended_field)
	{
		return explode('|', $member_extended_field->get_default_values());
	}
}
?>