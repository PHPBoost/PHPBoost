<?php
/*##################################################
 *                               MemberMultipleChoiceExtendedField.class.php
 *                            -------------------
 *   begin                : December 08, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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
 
class MemberMultipleChoiceExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('regex', 'default_value'));
		$this->set_name(LangLoader::get_message('type.multiple-check','admin-user-common'));
	}
	
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$options = array();
		$default_values = array();
		foreach ($member_extended_field->get_possible_values() as $name => $parameters)
		{
			$option = new FormFieldMultipleCheckboxOption($name, stripslashes($parameters['title']));
			$options[] = $option;
			
			if ($parameters['is_default'])
			{
				$default_values[] = $option;
			}
		}
		
		$fieldset->add_field(new FormFieldMultipleCheckbox($member_extended_field->get_field_name(), $member_extended_field->get_name(), $default_values, $options, array('required' => (bool)$member_extended_field->get_required(), 'description' => $member_extended_field->get_description())));
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$options = array();
		$default_values = $this->unserialise_value($member_extended_field->get_value());
		foreach ($member_extended_field->get_possible_values() as $name => $parameters)
		{
			$options[] = new FormFieldMultipleCheckboxOption($name, stripslashes($parameters['title']));
		}

		$fieldset->add_field(new FormFieldMultipleCheckbox($member_extended_field->get_field_name(), $member_extended_field->get_name(), $default_values, $options, array('required' => (bool)$member_extended_field->get_required(), 'description' => $member_extended_field->get_description())));
	}
	
	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		$value = implode(', ', $this->unserialise_value($member_extended_field->get_value()));
		if (!empty($value))
		{
			$fieldset->add_field(new FormFieldFree($member_extended_field->get_field_name(), $member_extended_field->get_name(), $value));
		}
	}
	
	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		$array = array();
		if ($form->has_field($field_name))
		{
			foreach ($form->get_value($field_name) as $field => $value)
			{
				$array[] = $value->get_label();
			}
		}
		return $this->serialise_value($array);
	}
	
	private function serialise_value(Array $array)
	{
		return implode('|', $array);
	}
	
	private function unserialise_value($string)
	{
		return explode('|', $string);
	}
}
?>
