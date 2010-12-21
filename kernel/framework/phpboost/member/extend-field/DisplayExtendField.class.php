<?php

class DisplayExtendField
{

	public function display_for_register($Template)
	{
		$extend_fields_cache = ExtendedFieldsCache::load()->get_extended_fields;	
		foreach ($extend_fields_cache as $id => $extend_field)
		{
			if ($extend_field['display'] == 1 && AppContext::get_user()->check_auth($extend_field['auth'], ExtendedField::AUTHORIZATION))
			{
				if ($extend_field['required'])
				{	
					$Template->assign_block_vars('extend_fields_js_list', array(
						'L_REQUIRED' => sprintf(LangLoader::get_message('required_field', 'main'), ucfirst($extend_field['name'])),
						'ID' => $extend_field['field_name']
					));
				}
				
				$Template->assign_block_vars('extend_fields_list', array(
					'NAME' => $extend_field['required'] ? '* ' . ucfirst($extend_field['name']) : ucfirst($extend_field['name']),
					'ID' => $extend_field['field_name'],
					'DESC' => !empty($extend_field['contents']) ? ucfirst($extend_field['contents']) : '',
					'FIELD' => $this->get_field($extend_field['field'], $extend_field['field_name'], $extend_field['possible_values'], $extend_field['default_values'])
				));
			}
		}
		
	}
	
	public function display_for_member($Template, $user_id)
	{
		$result = PersistenceContext::get_sql()->query_while("SELECT exc.name, exc.contents, exc.field, exc.required, exc.field_name, exc.possible_values, exc.default_values, ex.*
		FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " exc
		LEFT JOIN " . DB_TABLE_MEMBER_EXTEND . " ex ON ex.user_id = '" . $user_id . "'
		WHERE exc.display = 1
		ORDER BY exc.class", __LINE__, __FILE__);
		while ($extend_field = PersistenceContext::get_sql()->fetch_assoc($result))
		{
			if ($extend_field['display'] == 1 && AppContext::get_user()->check_auth($extend_field['auth'], ExtendedField::AUTHORIZATION))
			{
				$field_value = !empty($extend_field[$extend_field['field_name']]) ? $extend_field[$extend_field['field_name']] : $extend_field['default_values'];

				if ($extend_field['required'])
				{
					$Template->assign_block_vars('extend_fields_js_list', array(
						'L_REQUIRED' => sprintf(LangLoader::get_message('required_field', 'main'), ucfirst($extend_field['name'])),
						'ID' => $extend_field['field_name']
					));
				}
				
				$Template->assign_block_vars('extend_fields_list', array(
					'NAME' => $extend_field['required'] ? '* ' . ucfirst($extend_field['name']) : ucfirst($extend_field['name']),
					'ID' => $extend_field['field_name'],
					'DESC' => !empty($extend_field['contents']) ? ucfirst($extend_field['contents']) : '',
					'FIELD' => $this->get_field($extend_field['field'], $extend_field['field_name'], $extend_field['possible_values'], $field_value)
				));
			}
		}
		PersistenceContext::get_sql()->query_close($result);
	}
	
	private function get_field($field, $field_name, $possible_values, $default_values)
	{
		if (is_numeric($field) && $field > 0 && $field <= 6 )
		{
			switch ($field)
			{
				case 1:
					return $this->get_short_field($field_name, $default_values);
				break;
				case 2:
					return $this->get_long_field($field_name, $default_values);
				break;
				case 3:
					return $this->get_simple_select($field_name, $possible_values, $default_values);
				break;
				case 4:
					return $this->get_multiple_select($field_name, $possible_values, $default_values);
				break;
				case 5:
					return $this->get_simple_choice($field_name, $possible_values, $default_values);
				break;
				case 6:
					return $this->get_multiple_choice($field_name, $possible_values, $default_values);
				break;
			}
		}
	}
	
	private function get_short_field($field_name, $default_values)
	{
		return '<input type="text" size="30" name="' . $field_name . '" id="' . $field_name . '" class="text" value="' . $default_values . '" />';
	}
	
	private function get_long_field($field_name, $default_values)
	{
		return '<textarea class="post" rows="4" cols="27" name="' . $field_name . '" id="' . $field_name . '">' . FormatingHelper::unparse($default_values) . '</textarea>';
	}
	
	private function get_simple_select($field_name, $possible_values, $default_values)
	{
		$field = '<select name="' . $field_name . '" id="' . $field_name . '">';
		$array_values = explode('|', $possible_values);
		$i = 0;
		foreach ($array_values as $values)
		{
			$selected = ($values ==  $default_values) ? 'selected="selected"' : '';
			$field .= '<option name="' . $field_name . '_' . $i . '" value="' . $values . '" ' . $selected . '/> ' . ucfirst($values) . '</option>';
			$i++;
		}
		$field .= '</select>';
		
		return $field;
	}
	
	private function get_multiple_select($field_name, $possible_values, $default_values)
	{
		$field = '<select name="' . $field_name . '" multiple="multiple" id="' . $field_name . '">';
		$array_values = explode('|', $possible_values);
		$array_default_values = explode('|', $default_values);
		$i = 0;
		foreach ($array_values as $values)
		{
			$selected = in_array($values, $array_default_values) ? 'selected="selected"' : '';
			$field .= '<option name="' . $field_name . '_' . $i . '" value="' . $values . '" ' . $selected . '/> ' . ucfirst($values) . '</option>';
			$i++;
		}
		$field .= '</select>';
		
		return $field;
	}
	
	private function get_simple_choice($field_name, $possible_values, $default_values)
	{
		$field = '';
		$array_values = explode('|', $possible_values);
		foreach ($array_values as $values)
		{
			$checked = ($values ==  $default_values) ? 'checked="checked"' : '';
			$field .= '<input type="radio" name="' . $field_name . '" id="' . $field_name . '" value="' . $values . '" ' . $checked . ' /> ' . ucfirst($values) . '<br />';
		}
		
		return $field;
	}
	
	private function get_multiple_choice($field_name, $possible_values, $default_values)
	{	
		$field = '';
		$array_values = explode('|', $possible_values);
		$array_default_values = explode('|', $default_values);
		$i = 0;
		foreach ($array_values as $values)
		{
			$checked = in_array($values, $array_default_values) ? 'checked="checked"' : '';
			$field .= '<input type="checkbox" name="' . $field_name . '_' . $i . '" value="' . $values . '" ' . $checked . '/> ' . ucfirst($values) . '<br />';
			$i++;
		}
		
		return $field;
	}
}
?>
