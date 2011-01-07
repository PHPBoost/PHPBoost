<?php
/*##################################################
 *                               MemberDateExtendedField.class.php
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
 
class MemberDateExtendedField extends AbstractMemberExtendedField
{
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$fieldset->add_field(new FormFieldDate($member_extended_field->get_field_name(), $member_extended_field->get_name(), new Date(DATE_FORMAT_SHORT, TIMEZONE_AUTO, $member_extended_field->get_default_values()), 
			array('description' => $this->lang['valid'], 'required' =>(bool)$member_extended_field->get_required())
		));
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$fieldset->add_field(new FormFieldDate($member_extended_field->get_field_name(), $member_extended_field->get_name(), new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $member_extended_field->get_value()), 
			array('description' => $this->lang['valid'], 'required' =>(bool)$member_extended_field->get_required())
		));
	}
	
	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$date = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $member_extended_field->get_value());
		$fieldset->add_field(new FormFieldFree($member_extended_field->get_field_name(), $member_extended_field->get_name(), $date->format(DATE_FORMAT_SHORT)));
	}
	
	public function return_value(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		return $form->get_value($field_name)->format(TIMESTAMP);
	}
}
?>