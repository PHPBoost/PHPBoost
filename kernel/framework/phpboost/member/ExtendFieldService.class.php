<?php
/*##################################################
 *                               ExtendFieldService.class.php
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


class ExtendFieldService
{
	private $db_connection;
	
	private $name;
	private $contents;
	private $required;
	private $field;
	private $possible_values;
	private $default_values;
	private $regex;
	
	private $class;
	private $field_name;

	private $errors;
	
	public function __construct()
	{
		$this->db_connection = PersistenceContext::get_sql();
		
		$this->name = retrieve(POST, 'name', '');
		$this->contents = retrieve(POST, 'contents', '', TSTRING);
		$this->required = retrieve(POST, 'required', 0);
		$this->field = retrieve(POST, 'field', 0);
		$this->possible_values = retrieve(POST, 'possible_values', '');
		$this->default_values = retrieve(POST, 'default_values', '');
		$regex_type = retrieve(POST, 'regex_type', 0);
		$this->regex = empty($regex_type) ? retrieve(POST, 'regex1', 0) : retrieve(POST, 'regex2', '');
		
		$this->class = $this->db_connection->query("SELECT MAX(class) + 1 FROM " . DB_TABLE_MEMBER_EXTEND_CAT . "", __LINE__, __FILE__);
		$this->field_name = ExtendFieldUtil::rewrite_field($this->name);
	}
	
	public function add()
	{
		if (!empty($this->name) && !empty($this->field))
		{			
			$check_name = $this->db_connection->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE field_name = '" . $this->field_name . "'", __LINE__, __FILE__);
			if (empty($check_name)) 
			{		
				$this->db_connection->query_inject("INSERT INTO " . DB_TABLE_MEMBER_EXTEND_CAT . " 	(name, class, field_name, contents, field, possible_values, default_values, required, display, regex) VALUES ('" . $this->name . "', '" . $this->class . "', '" . $this->field_name . "', '" . $this->contents . "', '" . $this->field . "', '" . $this->possible_values . "', '" . $this->default_values . "', '" . $this->required . "', 1, '" . $this->regex . "')", __LINE__, __FILE__);		
				
				ExtendFieldUtil::add_field_name($this->field_name);
				
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
		$field_name = $this->db_connection->query("SELECT field_name FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE id = '" . $id . "'", __LINE__, __FILE__);
		if (!empty($field_name))
		{
			$this->db_connection->query_inject("DELETE FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE id = '" . $id . "'", __LINE__, __FILE__);
			$this->db_connection->query_inject("ALTER TABLE " . DB_TABLE_MEMBER_EXTEND . " DROP " . $field_name, __LINE__, __FILE__);
			
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
			$check_name = $this->db_connection->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE field_name = '" . $this->field_name . "' AND id <> '" . $id . "'", __LINE__, __FILE__);
			if (empty($check_name)) 
			{
				$this->db_connection->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND_CAT . " SET 
					name = '" . $this->name . "', 
					field_name = '" . $this->field_name . "', 
					contents = '" . $this->contents . "', 
					field = '" . $this->field . "', 
					possible_values = '" . $this->possible_values . "', 
					default_values = '" . $this->default_values . "', 
					required = '" . $this->required . "', 
					regex = '" . $this->regex . "' 
					WHERE id = '" . $id . "'"
				, __LINE__, __FILE__);
				
				ExtendFieldUtil::change_field_name($id, $this->field_name, $this->field);
				
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