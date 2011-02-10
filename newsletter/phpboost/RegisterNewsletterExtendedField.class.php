<?php
/*##################################################
 *                               RegisterNewsletterExtendedField.class.php
 *                            -------------------
 *   begin                : February 07, 2010 2009
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
 
class RegisterNewsletterExtendedField extends AbstractMemberExtendedField
{
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$fieldset->add_field(new FormFieldCheckbox($member_extended_field->get_field_name(), $member_extended_field->get_name(), $member_extended_field->get_default_values(), array('required' => (bool)$member_extended_field->get_required())));
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$fieldset->add_field(new FormFieldCheckbox($member_extended_field->get_field_name(), $member_extended_field->get_name(), $member_extended_field->get_value(), array('required' => (bool)$member_extended_field->get_required())));
	}
	
	public function return_value(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		
		return $form->get_value($field_name);
	}
	
	public function register(MemberExtendedField $member_extended_field, MemberExtendedFieldsDAO $member_extended_fields_dao)
	{
		parent::register($member_extended_field, $member_extended_fields_dao);
		
		$mail = PersistenceContext::get_sql()->query("SELECT user_mail FROM " . DB_TABLE_MEMBER . " WHERE user_id = '". $member_extended_field->get_user_id() ."'", __LINE__, __FILE__);
		if ((bool)$member_extended_field->get_value())
		{
			NewsletterService::subscribe($mail);
		}
		else
		{
			NewsletterService::unsubscribe($mail);
		}
	}
}
?>