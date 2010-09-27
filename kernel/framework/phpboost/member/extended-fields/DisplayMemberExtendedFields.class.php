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
	 * This function required object DisplayMemberExtendedField containing user_id and Template. If user is not registered, use object DisplayMemberExtendedField, and define user_id of null.
	 */
	public function display(DisplayMemberExtendedField $display_extended_field)
	{
		// TODO Change for a new function verification if the field is activate
		$extend_fields_cache = ExtendFieldsCache::load()->get_extend_fields();

		if (count($extend_fields_cache) > 0)
		{
			$user_id = $display_extended_field->get_user_id();
			if($user_id !== null)
			{
				$this->display_for_visitor($display_extended_field);
			}
			else
			{
				$this->display_for_member($display_extended_field);
			}
		}
	}

	private function display_for_visitor(DisplayMemberExtendedField $display_extended_field)
	{
		$extend_fields_cache = ExtendFieldsCache::load()->get_extend_fields();	
		foreach ($extend_fields_cache as $id => $extended_field)
		{
			if ($extended_field['display'] == 1)
			{		
				$display_extended_field->set_name($extended_field['name']);
				$display_extended_field->set_field_name($extended_field['field_name']);
				$display_extended_field->set_content($extended_field['contents']);
				$display_extended_field->set_field_type($extended_field['field']);
				$display_extended_field->set_possible_values()$extended_field['possible_values'];
				$display_extended_field->set_default_values($extended_field['default_values']);
				$display_extended_field->set_required($extended_field['required']);
				$display_extended_field->set_display($extended_field['display']);
				$display_extended_field->set_regex($extended_field['regex']);
				$display_extended_field->set_authorization($extended_field['auth']);
			
				$this->display_field($display_extended_field);
			}
		}
		
	}
	
	private function display_for_member(DisplayMemberExtendedField $display_extended_field)
	{
		$user_id = $display_extended_field->get_user_id();
		$result = PersistenceContext::get_sql()->query_while("SELECT exc.name, exc.contents, exc.field, exc.required, exc.field_name, exc.possible_values, exc.default_values, ex.*
		FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " exc
		LEFT JOIN " . DB_TABLE_MEMBER_EXTEND . " ex ON ex.user_id = '" . $user_id . "'
		WHERE exc.display = 1
		ORDER BY exc.class", __LINE__, __FILE__);
		while ($extended_field = PersistenceContext::get_sql()->fetch_assoc($result))
		{
			if ($extended_field['display'] == 1)
			{
				$display_extended_field->set_name($extended_field['name']);
				$display_extended_field->set_field_name($extended_field['field_name']);
				$display_extended_field->set_content($extended_field['contents']);
				$display_extended_field->set_field_type($extended_field['field']);
				$display_extended_field->set_possible_values()$extended_field['possible_values'];
				$display_extended_field->set_default_values($extended_field['default_values']);
				$display_extended_field->set_required($extended_field['required']);
				$display_extended_field->set_display($extended_field['display']);
				$display_extended_field->set_regex($extended_field['regex']);
				$display_extended_field->set_authorization($extended_field['auth']);
				
				$this->display_field($display_extended_field);
			}
		}
		PersistenceContext::get_sql()->query_close($result);
	}
	
	private function display_field(DisplayMemberExtendedField $display_extended_field)
	{
		$type_field = $display_extended_field->get_field_type();
		if (is_numeric($type_field))
		{
			switch ($type_field)
			{
				case 1:
					return $this->display_short_field($display_extended_field);
				break;
				case 2:
					return $this->display_long_field($display_extended_field);
				break;
				case 3:
					return $this->display_simple_select($display_extended_field);
				break;
				case 4:
					return $this->display_multiple_select($display_extended_field);
				break;
				case 5:
					return $this->display_simple_choice($display_extended_field);
				break;
				case 6:
					return $this->display_multiple_choice($display_extended_field);
				break;
				default:
					// TODO Error
			}
		}
	}
	
	private function display_short_field(DisplayMemberExtendedField $display_extended_field)
	{
		$template = $display_extended_field->get_template();
		
		/*
		$fieldset->add_field(new FormFieldTextEditor($display_extended_field->get_field_name(), $display_extended_field->get_name(), $display_extended_field->get_default_values(), array(
			'class' => 'text', 'required' => (bool)$display_extended_field->get_required(), 'maxlength' => 25, 'size' => 25, 'description' => $display_extended_field->get_description()),
			array(new FormFieldConstraintRegex($display_extended_field->get_regex()))
		));
		*/
	}
	
	private function display_long_field(DisplayMemberExtendedField $display_extended_field)
	{
		$template = $display_extended_field->get_template();
		
		/*
		$fieldset->add_field(new FormFieldTextEditor($display_extended_field->get_field_name(), $display_extended_field->get_name(), $display_extended_field->get_default_values(), array(
			'class' => 'text', 'required' => (bool)$display_extended_field->get_required(), 'rows' => 10, 'cols' => 47, 'description' => $display_extended_field->get_description()),
			array(new FormFieldConstraintRegex($display_extended_field->get_regex()))
		));
		*/
	}
	
	private function display_simple_select(DisplayMemberExtendedField $display_extended_field)
	{
		$template = $display_extended_field->get_template();
		
		/*
		$field = array();
		$array_values = explode('|', $display_extended_field->get_possible_values());
		$i = 0;
		foreach ($array_values as $values)
		{
			$field = new FormFieldSelectChoiceOption($values, $display_extended_field->get_field_name() . '_' . $i);
			$i++;
		}
		
		$fieldset->add_field(new FormFieldSelectChoice($display_extended_field->get_field_name(), $display_extended_field->get_name(), $display_extended_field->get_default_values(),
			array($field)
		));
		*/
	}
	
	private function display_multiple_select(DisplayMemberExtendedField $display_extended_field)
	{
		$template = $display_extended_field->get_template();	
		
	}
	
	private function display_simple_choice(DisplayMemberExtendedField $display_extended_field)
	{
		$template = $display_extended_field->get_template();
		
		/*
		$field = array();
		$array_values = explode('|', $display_extended_field->get_possible_values());
		$i = 0;
		foreach ($array_values as $values)
		{
			$field = new FormFieldRadioChoice($values, $display_extended_field->get_field_name() . '_' . $i);
			$i++;
		}
		
		$fieldset->add_field(new FormFieldRadioChoiceOption($display_extended_field->get_field_name(), $display_extended_field->get_name(), $display_extended_field->get_default_values(),
			array($field)
		));
		*/
		
	}
	
	private function display_multiple_choice(DisplayMemberExtendedField $display_extended_field)
	{
		$template = $display_extended_field->get_template();
		
	}
}
?>