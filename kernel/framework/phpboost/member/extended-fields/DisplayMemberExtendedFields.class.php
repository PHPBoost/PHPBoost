<?php
/*##################################################
 *                      	 DisplayMemberExtendedFields.class.php
 *                            -------------------
 *   begin                : September 27, 2010
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

/**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 */
class DisplayMemberExtendedFields
{
	/*
	 * This function required object DisplayMemberExtendedField containing user_id. If user is not registered, use object DisplayMemberExtendedField, and define user_id of null.
	 */
	public function display(DisplayMemberExtendedField $member_extended_field)
	{
		// TODO Change for a new function verification if the field is activate
		$extend_fields_cache = ExtendFieldsCache::load()->get_extend_fields();

		if (count($extend_fields_cache) > 0)
		{
			$user_id = $member_extended_field->get_user_id();
			if($user_id !== null)
			{
				
			}
			else
			{
				
			}
		}
	}

	private function display_for_visitor($member_extended_field)
	{
		$extend_fields_cache = ExtendFieldsCache::load()->get_extend_fields();	
		foreach ($extend_fields_cache as $id => $extended_field)
		{
			if ($extended_field['display'] == 1)
			{		
				$extended_field = new ExtendedField();
				$extended_field->set_name($extended_field['name']);
				$extended_field->set_field_name($extended_field['field_name']);
				$extended_field->set_content($extended_field['contents']);
				$extended_field->set_field_type($extended_field['field']);
				$extended_field->set_possible_values()$extended_field['possible_values'];
				$extended_field->set_default_values($extended_field['default_values']);
				$extended_field->set_is_required($extended_field['required']);
				$extended_field->set_display($extended_field['display']);
				$extended_field->set_regex($extended_field['regex']);
				$extended_field->set_authorization($extended_field['auth']);
			
				$this->display_field($member_extended_field);
			}
		}
		
	}
	
	private function display_for_member($member_extended_field)
	{
		$user_id = $member_extended_field->get_user_id();
		$result = PersistenceContext::get_sql()->query_while("SELECT exc.name, exc.contents, exc.field, exc.required, exc.field_name, exc.possible_values, exc.default_values, ex.*
		FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " exc
		LEFT JOIN " . DB_TABLE_MEMBER_EXTEND . " ex ON ex.user_id = '" . $user_id . "'
		WHERE exc.display = 1
		ORDER BY exc.class", __LINE__, __FILE__);
		while ($extended_field = PersistenceContext::get_sql()->fetch_assoc($result))
		{
			if ($extended_field['display'] == 1)
			{
				$extended_field = new DisplayMemberExtendedField();
				$extended_field->set_name($extended_field['name']);
				$extended_field->set_field_name($extended_field['field_name']);
				$extended_field->set_content($extended_field['contents']);
				$extended_field->set_field_type($extended_field['field']);
				$extended_field->set_possible_values()$extended_field['possible_values'];
				$extended_field->set_default_values($extended_field['default_values']);
				$extended_field->set_is_required($extended_field['required']);
				$extended_field->set_display($extended_field['display']);
				$extended_field->set_regex($extended_field['regex']);
				$extended_field->set_authorization($extended_field['auth']);
				
				$this->display_field($member_extended_field);
			}
		}
		PersistenceContext::get_sql()->query_close($result);
	}
	
	private function display_field(DisplayMemberExtendedField $extended_field)
	{
		$type_field = $extended_field->get_field_type();
		if (is_numeric($type_field))
		{
			switch ($type_field)
			{
				case 1:
					return $this->display_short_field($extended_field);
				break;
				case 2:
					return $this->display_long_field($extended_field);
				break;
				case 3:
					return $this->display_simple_select($extended_field);
				break;
				case 4:
					return $this->display_multiple_select($extended_field);
				break;
				case 5:
					return $this->display_simple_choice($extended_field);
				break;
				case 6:
					return $this->display_multiple_choice($extended_field);
				break;
				default:
					// TODO Error
			}
		}
	}
	
	private function display_short_field(DisplayMemberExtendedField $extended_field)
	{
	
	}
	
	private function display_long_field(DisplayMemberExtendedField $extended_field)
	{
	
	}
	
	private function display_simple_select(DisplayMemberExtendedField $extended_field)
	{
	
	}
	
	private function display_multiple_select(DisplayMemberExtendedField $extended_field)
	{
	
	}
	
	private function display_simple_choice(DisplayMemberExtendedField $extended_field)
	{
	
	}
	
	private function display_multiple_choice(DisplayMemberExtendedField $extended_field)
	{
	
	}
}
?>