<?php
/*##################################################
 *                               MemberDateExtendedField.class.php
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
 
class MemberDateExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('regex', 'possible_values', 'default_value'));
		$this->set_name(LangLoader::get_message('type.date','admin-user-common'));
	}
	
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$value = $member_extended_field->get_default_value() ? new Date($member_extended_field->get_default_value()) : null;
		$fieldset->add_field(new FormFieldDate($member_extended_field->get_field_name(), $member_extended_field->get_name(), $value, 
			array('description' => $member_extended_field->get_description(), 'required' =>(bool)$member_extended_field->get_required())
		));
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$value = $member_extended_field->get_value() ? new Date($member_extended_field->get_value()) : null;
		$fieldset->add_field(new FormFieldDate($member_extended_field->get_field_name(), $member_extended_field->get_name(), $value, 
			array('description' => $member_extended_field->get_description(), 'required' =>(bool)$member_extended_field->get_required())
		));
	}
	
	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		if ($member_extended_field->get_value())
		{
			$date = new Date($member_extended_field->get_value());
			$fieldset->add_field(new FormFieldFree($member_extended_field->get_field_name(), $member_extended_field->get_name(), $date->format(Date::FORMAT_DAY_MONTH_YEAR)));
		}
	}
	
	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		if ($form->has_field($field_name))
			return $form->get_value($field_name)->format(Date::FORMAT_TIMESTAMP);
		
		return '';
	}
}
?>