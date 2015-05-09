<?php
/*##################################################
 *                               MemberExtendedFieldType.class.php
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

 /**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @package {@package}
 */
interface MemberExtendedFieldType
{
	/**
	 * @desc This function displayed field for create form
	 * @param instance of MemberExtendedField $member_extended_field.
	 */
	public function display_field_create(MemberExtendedField $member_extended_field);

	/**
	 * @desc This function displayed field for update form
	 * @param instance of MemberExtendedField $member_extended_field.
	 */
	public function display_field_update(MemberExtendedField $member_extended_field);
	
	/**
	 * @desc This function displayed field for profile
	 * @param instance of MemberExtendedField $member_extended_field.
	 */
	public function display_field_profile(MemberExtendedField $member_extended_field);
	
	/**
	 * @desc This function delete the field when the member is deleted
	 * @param instance of MemberExtendedField $member_extended_field.
	 */
	public function delete_field(MemberExtendedField $member_extended_field);
	
	/**
	 * @desc This function returned value form fields
	 * @param instance of HTMLForm $form and instance of MemberExtendedField $member_extended_field.
	 */
	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field);
	
	/**
	 * @desc Return instanciat constraint depending integer type regex.
	 * @return integer
	 */
	public function constraint($value);
	
	public function set_disable_fields_configuration(array $names);
	
	/**
	 * @return Array
	 */
	public function get_disable_fields_configuration();
	
	public function set_name($name);
	
	/**
	 * @return String
	 */
	public function get_name();
	
	/**
	 * @desc Return true if the field used once
	 * @return bool
	 */
	public function get_field_used_once();
	
	/**
	 * @desc Return true if the field used in the phpboost configuration and can't deleted
	 * @return bool
	 */
	public function get_field_used_phpboost_configuration();
}
?>