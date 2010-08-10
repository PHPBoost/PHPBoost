<?php
/*##################################################
 *                               ExtendFields.class.php
 *                            -------------------
 *   begin                : August 11, 2010
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
	
	private $name;
	private $field_name;
	private $contents;
	private $field;
	private $possible_values;
	private $default_values;
	private $required;
	private $regex;
		
	private $type_fields;
	
	private $db_connection;
	
	private $errors;
	
	public function __construct($table_extend_fields, $table_extend_fields_members)
	{
		//TODO nom des constantes a changer
		$this->table_extend_fields = !empty($table_extend_fields) ? $table_extend_fields : DB_TABLE_MEMBER_EXTEND_CAT;
		$this->table_extend_fields_members = !empty($table_extend_fields_members) ? $table_extend_fields_members : DB_TABLE_MEMBER_EXTEND;
		
		$this->name = retrieve(POST, 'name', '');
		$this->field_name = ExtendFieldsService::rewrite_field($name);
		$this->contents = retrieve(POST, 'contents', '', TSTRING);
		$this->field = retrieve(POST, 'field', 0);
		$this->possible_values = retrieve(POST, 'possible_values', '');
		$this->default_values = retrieve(POST, 'default_values', '');
		$this->required = retrieve(POST, 'required', 0);
		$this->regex = empty(retrieve(POST, 'regex_type', 0)) ? retrieve(POST, 'regex1', 0) : retrieve(POST, 'regex2', '');
			
		$this->type_fields = ExtendFieldsService::get_array_type_fields();
		
		$this->db_connection = PersistenceContext::get_sql();
	}
	
	public function add()
	{
		if (!empty($this->name) && !empty($this->field))
		{			
			$check_name = $this->db_connection->query("SELECT COUNT(*) FROM " . $this->table_extend_fields . " WHERE field_name = '" . $this->field_name . "'", __LINE__, __FILE__);
			if (empty($check_name)) 
			{
				$class = $this->db_connection->query("SELECT MAX(class) + 1 FROM " . DB_TABLE_MEMBER_EXTEND_CAT . "", __LINE__, __FILE__);
					
				$this->db_connection->query_inject("INSERT INTO " . DB_TABLE_MEMBER_EXTEND_CAT . " 	(name, class, field_name, contents, field, possible_values, default_values, required, display, regex) VALUES ('" . $this->name . "', '" . $class . "', '" . $this->field_name . "', '" . $this->contents . "', '" . $this->field . "', '" . $this->possible_values . "', '" . $this->default_values . "', '" . $this->required . "', 1, '" . $this->regex . "')", __LINE__, __FILE__);		
				
				ExtendFields::add_field_name($this->field_name);
				
				ExtendFieldsCache::invalidate();
			}
			else
			{
				//Le champs existe déjà
				$this->errors = 'exist_field';
			}
		}
		else
		{	
			// Tous les champs n'ont pas été remplie
			$this->errors = 'incomplete';
		}
	}
	
	public function delete($id)
	{
		$field_name = $this->db_connection->query("SELECT field_name FROM " . $this->table_extend_fields . " WHERE id = '" . $id . "'", __LINE__, __FILE__);
		if (!empty($field_name))
		{
			$this->db_connection->query_inject("DELETE FROM " . $this->table_extend_fields . " WHERE id = '" . $id . "'", __LINE__, __FILE__);
			$this->db_connection->query_inject("ALTER TABLE " . $this->table_extend_fields_members . " DROP " . $field_name, __LINE__, __FILE__);
			
			ExtendFieldsCache::invalidate();
		}
		else
		{
			$this->errors = 'not_existent_field';
		}	
	}
	
	public function update($id)
	{
		if (!empty($this->name) && !empty($this->field))
		{			
			$check_name = $this->db_connection->query("SELECT COUNT(*) FROM " . $this->table_extend_fields . " WHERE field_name = '" . $this->field_name . "' AND id <> '" . $id . "'", __LINE__, __FILE__);
			if (empty($check_name)) 
			{
				$Sql->query_inject("UPDATE " . $this->table_extend_fields . " SET 
					name = '" . $this->name . "', 
					field_name = '" . $this->field_name . "', 
					contents = '" . $this->contents . "', 
					field = '" . $this->type_fields[$field] . "', 
					possible_values = '" . $this->possible_values . "', 
					default_values = '" . $this->default_values . "', 
					required = '" . $this->required . "', 
					regex = '" . $this->regex . "' 
					WHERE id = '" . $id . "'"
				, __LINE__, __FILE__);
				
				ExtendFields::change_field_name($id, $this->field_name);
				
				ExtendFieldsCache::invalidate();
			}
			else
			{
				//Le champs existe déjà
				$this->errors = 'exist_field';
			}
		}
		else
		{	
			// Tous les champs n'ont pas été remplie
			$this->errors = 'incomplete';
		}
	}
	
	public function errors()
	{
		return !empty($this->errors) ? $this->errors : '';
	}
}
?>