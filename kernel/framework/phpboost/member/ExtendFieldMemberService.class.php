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
		$req_update = '';
		$req_field = '';
		$req_insert = '';
		$extend_fields_cache = ExtendFieldsCache::load()->get_extend_fields();
		foreach ($extend_fields_cache as $extend_field)
		{
			$field = retrieve(POST, $extend_field['field_name'], '', TSTRING_UNCHANGE);
			
			// Champs requis ! On redirige si il est vide.
			if ($extend_field['required'] && $extend_field['field'] != 6 && empty($field))
			{
				$this->errors = 'incomplete';
			}
			else
			{
				if (empty($this->errors))
				{
					$field = $this->rewrite_field($extend_field['field'], $field);
				}
				if (!empty($field))
				{
					if (is_numeric($extend_field['regex']) && $extend_field['regex'] > 0 && $extend_field['field'] <= 2)
					{
						$regex = ExtendFieldUtil::get_regex($extend_field['regex'];
						if (@preg_match($regex, $field))
						{
							$req_update .= $extend_field['field_name'] . ' = \'' . trim($field, '|') . '\', ';
							$req_field .= $extend_field['field_name'] . ', ';
							$req_insert .= '\'' . trim($field, '|') . '\', ';
						}
					}
				}
			}
		}
		if(empty($this->errors))
		{
			$check_member = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
			if ($check_member && !empty($req_update))
			{
				$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND . " SET " . trim($req_update, ', ') . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
			}
			elseif (!empty($req_insert))
			{
				$Sql->query_inject("INSERT INTO " . DB_TABLE_MEMBER_EXTEND . " (user_id, " . trim($req_field, ', ') . ") VALUES ('" . $user_id . "', " . trim($req_insert, ', ') . ")", __LINE__, __FILE__);
			}
		}
	}

	public function update($user_id)
	{
		$this->add($user_id);
	}
	
	public function errors()
	{
		return !empty($this->errors) ? $this->errors : '';
	}
	
	private function rewrite_field(bool $field_type, string $field)
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
					return $this->format_field_multiple_choice($field, $extend_field['field_name'], $extend_field['possible_values']);
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
		$array_possible_values = $this->get_explode_possible_values($possible_values));
		foreach ($array_possible_values as $value)
		{
			$field .= !empty($_POST[$field_name . '_' . $i]) ? addslashes($value) . '|' : '';
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