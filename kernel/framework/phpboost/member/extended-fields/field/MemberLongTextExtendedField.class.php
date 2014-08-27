<?php
/*##################################################
 *                               MemberLongTextExtendedField.class.php
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
 
class MemberLongTextExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('possible_values'));
		$this->set_name(LangLoader::get_message('type.long-text','admin-user-common'));
	}
	
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$fieldset->add_field(new FormFieldRichTextEditor($member_extended_field->get_field_name(), $member_extended_field->get_name(), $member_extended_field->get_default_value(), array(
			'required' => (bool)$member_extended_field->get_required(), 'rows' => 5, 'cols' => 47, 'description' => $member_extended_field->get_description()),
			array($this->constraint($member_extended_field->get_regex()))
		));
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$fieldset->add_field(new FormFieldRichTextEditor($member_extended_field->get_field_name(), $member_extended_field->get_name(), $member_extended_field->get_value(), array(
			'required' => (bool)$member_extended_field->get_required(), 'rows' => 5, 'cols' => 47, 'description' => $member_extended_field->get_description()),
			array($this->constraint($member_extended_field->get_regex()))
		));
	}
	
	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		$value = FormatingHelper::second_parse($member_extended_field->get_value());
		if ($value !== null)
		{
			$fieldset->add_field(new FormFieldFree($member_extended_field->get_field_name(), $member_extended_field->get_name(), $value));
		}
	}
	
	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		return $form->get_value($field_name);
	}
}
?>