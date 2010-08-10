<?php
/*##################################################
 *                               ExtendFieldsMember.class.php
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


class ExtendFields
{
	private $table_extend_fields;
	private $table_extend_fields_members;
	
	private $array_regex;
	private $db_connection;
	
	private $errors;
	
	public function __construct($table_extend_fields, $table_extend_fields_members)
	{
		//TODO nom des constantes a changer
		$this->table_extend_fields = !empty($table_extend_fields) ? $table_extend_fields : DB_TABLE_MEMBER_EXTEND_CAT;
		$this->table_extend_fields_members = !empty($table_extend_fields_members) ? $table_extend_fields_members : DB_TABLE_MEMBER_EXTEND;
		
		$this->array_regex = get_array_regex();
		
		$this->db_connection = PersistenceContext::get_sql();
	}
	
	public function add($user_id)
	{
		$req_update = '';
		$req_field = '';
		$req_insert = '';
		$extend_field_cache = ExtendFieldsCache::load()->get_extend_fields();
		foreach ($extend_field_cache as $row)
		{
			$field = retrieve(POST, $row['field_name'], '', TSTRING_UNCHANGE);
			
			// Champs requis ! On redirige si il est vide.
			if ($row['required'] && $row['field'] != 6 && empty($field))
			{
				$this->errors = 'incomplete';
			}
			else
			{
				//Validation par expressions régulières.
				if (is_numeric($row['regex']) && $row['regex'] >= 1 && $row['regex'] <= 5)
				{
					$row['regex'] = $this->array_regex[$row['regex']];
				}
				
				$valid_field = true;
				if (!empty($row['regex']) && $row['field'] <= 2)
				{
					if (@preg_match($row['regex'], $field))
						$valid_field = true;
					else
						$valid_field = false;
				}
			
				if ($row['field'] == 2)
					$field = FormatingHelper::strparse($field);
				elseif ($row['field'] == 4)
				{
					$array_field = is_array($field) ? $field : array();
					$field = '';
					foreach ($array_field as $value)
						$field .= TextHelper::strprotect($value) . '|';
				}
				elseif ($row['field'] == 6)
				{
					$field = '';
					$i = 0;
					$array_possible_values = $this->get_explode_possible_values_and_return_array();
					foreach ($array_possible_values as $value)
					{
						$field .= !empty($_POST[$row['field_name'] . '_' . $i]) ? addslashes($value) . '|' : '';
						$i++;
					}
					if ($row['required'] && empty($field))
						$this->errors = 'incomplete';
				}
				else
					$field = TextHelper::strprotect($field);
					
				if (!empty($field))
				{
					if ($valid_field) //Validation par expression régulière si présente.
					{
						$req_update .= $row['field_name'] . ' = \'' . trim($field, '|') . '\', ';
						$req_field .= $row['field_name'] . ', ';
						$req_insert .= '\'' . trim($field, '|') . '\', ';
					}
				}
			}
		}
		$check_member = $Sql->query("SELECT COUNT(*) FROM " . $this->table_extend_fields_members . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		if ($check_member && !empty($req_update))
				$Sql->query_inject("UPDATE " . $this->table_extend_fields_members . " SET " . trim($req_update, ', ') . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		else if (!empty($req_insert))
				$Sql->query_inject("INSERT INTO " . $this->table_extend_fields_members . " (user_id, " . trim($req_field, ', ') . ") VALUES ('" . $user_id . "', " . trim($req_insert, ', ') . ")", __LINE__, __FILE__);
	}

	public function update()
	{
		$this->add($user_id);
	}
	
	public function errors()
	{
		return !empty($this->errors) ? $this->errors : '';
	}
	
	private function get_explode_possible_values_and_return_array()
	{
		return explode('|', $row['possible_values']);
	}
	
	public static function add_field_name($field_name)
	{
		$this->db_connection->query_inject("ALTER TABLE " . DB_TABLE_MEMBER_EXTEND . " ADD " . $field_name, __LINE__, __FILE__);
	}
	
	public static function change_field_name($precedent_field_id, $new_field)
	{
		$previous_name = $Sql->query("SELECT field_name FROM " . $this->table_extend_fields . " WHERE id = '" . $precedent_field_id . "'", __LINE__, __FILE__);
		
		if ($previous_name != $new_field)
			$this->db_connection->query_inject("ALTER TABLE " . $this->table_extend_fields_members . " CHANGE " . $previous_name . " " . $new_field, __LINE__, __FILE__);

	}

}
?>