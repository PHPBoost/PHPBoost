<?php
/*##################################################
 *                               ExtendFieldMemberService.class.php
 *                            -------------------
 *   begin                : August 10, 2010
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


class ExtendFieldMemberService
{
	private $db_connection;
	
	private $errors;
	
	public function __construct()
	{	
		$this->db_connection = PersistenceContext::get_sql();
	}
	
	public function add($user_id)
	{
		$extend_fields_cache = ExtendFieldsCache::load()->get_extend_fields();
		
		$extend_field_exist = count($extend_fields_cache);
		// Des champs membres sont existant
		if ($extend_field_exist > 0)
		{
			$req_update = '';
			$req_field = '';
			$req_insert = '';
			foreach ($extend_fields_cache as $id => $extend_field)
			{
				$field = retrieve(POST, $extend_field['field_name'], '', TSTRING_UNCHANGE);
				
				if ($extend_field['required'] && $extend_field['field'] != 6 && empty($field))
				{
					$this->errors = 'incomplete';
				}
				else
				{
					$field = $this->rewrite_field($extend_field['field'], $field, $extend_field['field_name'], $extend_field['possible_values']);
					if (!empty($field))
					{
						$regex = ExtendFieldUtil::get_regex($extend_field['regex']);
						
						if ($extend_field['regex'] > 0 && @preg_match($regex, $field))
						{
							$req_update .= $extend_field['field_name'] . ' = \'' . trim($field, '|') . '\', ';
							$req_field .= $extend_field['field_name'] . ', ';
							$req_insert .= '\'' . trim($field, '|') . '\', ';
						}
						elseif($extend_field['regex'] == 0)
						{
							$req_update .= $extend_field['field_name'] . ' = \'' . trim($field, '|') . '\', ';
							$req_field .= $extend_field['field_name'] . ', ';
							$req_insert .= '\'' . trim($field, '|') . '\', ';	
						}
					}
				}
			}
			if(empty($this->errors))
			{
				$check_member = $this->db_connection->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
				if ($check_member && !empty($req_update))
				{
					$this->db_connection->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND . " SET " . trim($req_update, ', ') . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
				}
				elseif (!empty($req_insert))
				{
					$this->db_connection->query_inject("INSERT INTO " . DB_TABLE_MEMBER_EXTEND . " (user_id, " . trim($req_field, ', ') . ") VALUES ('" . $user_id . "', " . trim($req_insert, ', ') . ")", __LINE__, __FILE__);
				}
			}
		}
	}

	public function update($user_id)
	{
		$extend_fields_cache = ExtendFieldsCache::load()->get_extend_fields();
		
		$extend_field_exist = count($extend_fields_cache);
		// Des champs membres sont existant
		if ($extend_field_exist > 0)
		{
			$req_update = '';
			$req_field = '';
			$req_insert = '';
			foreach ($extend_fields_cache as $id => $extend_field)
			{
				$field = retrieve(POST, $extend_field['field_name'], '', TSTRING_UNCHANGE);
				
				if ($extend_field['required'] && $extend_field['field'] != 6 && empty($field))
				{
					$this->errors = 'incomplete';
				}
				else
				{
					$field = $this->rewrite_field($extend_field['field'], $field, $extend_field['field_name'], $extend_field['possible_values']);
					
					if (!empty($field))
					{
						$regex = ExtendFieldUtil::get_regex($extend_field['regex']);
						
						if ($extend_field['regex'] > 0 && @preg_match($regex, $field))
						{
							$req_update .= $extend_field['field_name'] . ' = \'' . trim($field, '|') . '\', ';
							$req_field .= $extend_field['field_name'] . ', ';
							$req_insert .= '\'' . trim($field, '|') . '\', ';
						}
						elseif($extend_field['regex'] == 0)
						{
							$req_update .= $extend_field['field_name'] . ' = \'' . trim($field, '|') . '\', ';
							$req_field .= $extend_field['field_name'] . ', ';
							$req_insert .= '\'' . trim($field, '|') . '\', ';	
						}
					}
				}
			}
			if(empty($this->errors))
			{
				//Le membre à déjà enregistré une fois la config des champs
				$check_member = $this->db_connection->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
				if ($check_member && !empty($req_update))
				{
					$this->db_connection->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND . " SET " . trim($req_update, ', ') . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
				}
				elseif (!empty($req_insert))
				{
					$this->db_connection->query_inject("INSERT INTO " . DB_TABLE_MEMBER_EXTEND . " (user_id, " . trim($req_field, ', ') . ") VALUES ('" . $user_id . "', " . trim($req_insert, ', ') . ")", __LINE__, __FILE__);
				}
			}
		}
	}
	
	public function errors()
	{
		return !empty($this->errors) ? $this->errors : '';
	}
	
	private function rewrite_field($field_type, $field, $field_name, $possible_values)
	{
		if (is_numeric($field_type))
		{
			switch ($field_type) {
				case 2:
					return $this->format_field_long_text($field);
					break;
				case 4:
					return $this->format_field_multiple_select($field);
					break;
				case 6:
					return $this->format_field_multiple_choice($field, $field_name, $possible_values);
					break;
				default:
					return TextHelper::strprotect($field);
			}
		}
	}
	
	private function format_field_long_text($field)
	{
		return FormatingHelper::strparse($field);
	}
	
	private function format_field_multiple_select($field)
	{
		$array_field = is_array($field) ? $field : array();
		$field = '';
		foreach ($array_field as $value)
			$field .= TextHelper::strprotect($value) . '|';
			
		return $field;
	}
	
	private function format_field_multiple_choice($field, $field_name, $possible_values)
	{
		$field = '';
		$i = 0;
		$array_possible_values = $this->get_explode_possible_values($possible_values);
		foreach ($array_possible_values as $value)
		{
			$field .= !empty($_POST[$field_name . '_' . $i]) ? addslashes($_POST[$field_name . '_' . $i]) . '|' : '';
			$i++;
		}
		if (!empty($field))
			return $field;
		else
			$this->errors = 'incomplete';
	}
	
	private function get_explode_possible_values($possible_values)
	{
		return explode('|', $possible_values);
	}


}
?>