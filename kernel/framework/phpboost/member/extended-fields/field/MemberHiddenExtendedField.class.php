<?php
/*##################################################
 *                               MemberHiddenExtendedField.class.php
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
 
class MemberHiddenExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{	
		parent::__construct();
		$this->set_disable_fields_configuration(array('name', 'description', 'field_type', 'regex', 'authorizations', 'possible_values', 'default_value'));
		$this->set_name('');
	}
	
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		return;
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		return;
	}
	
	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		return;
	}
	
	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		return;
	}
}
?>