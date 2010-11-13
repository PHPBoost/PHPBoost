<?php
/*##################################################
 *             ExtendedField.class.php
 *                            -------------------
 *   begin                : August 14, 2010
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
 * @package {@package}
 */
class ExtendedField
{
	const AUTHORIZATION = 1;
	
	private $id;
	private $name;
	private $position;
	private $field_name;
	private $field_type;
	private $content;
	private $possible_values;
	private $default_values;
	private $required;
	private $display;
	private $regex;
	private $authorization;
	
	public function set_id($id)
	{
		$this->id = $id;
	}
	
	public function get_id()
	{
		return !empty($this->id) ? $this->id : 0;
	}
	
	public function set_name($name)
	{
		$this->name = $name;
	}
	
	public function get_name()
	{
		return !empty($this->name) ? $this->name : '';
	}
	
	public function set_position($position)
	{
		$this->position = $position;
	}
	
	public function get_position()
	{
		return !empty($this->position) ? $this->position : 1;
	}
	
	public function set_field_name($field_name)
	{
		$this->field_name = $field_name;
	}
	
	public function get_field_name()
	{
		return !empty($this->field_name) ? $this->field_name : '';
	}
	
	public function set_content($content)
	{
		$this->content = $content;
	}
	
	public function get_content()
	{
		return !empty($this->content) ? $this->content : '';
	}
	
	public function set_field_type($field_type)
	{
		$this->field_type = $field_type;
	}
	
	public function get_field_type()
	{
		return !empty($this->field_type) ? $this->field_type : 1;
	}	
	
	public function set_possible_values($possible_values)
	{
		$this->possible_values = $possible_values;
	}
	
	public function get_possible_values()
	{
		return !empty($this->possible_values) ? $this->possible_values : '';
	}
	
	public function set_default_values($default_values)
	{
		$this->default_values = $default_values;
	}
	
	public function get_default_values()
	{
		return !empty($this->default_values) ? $this->default_values : '';
	}
	
	public function set_is_required($required)
	{
		$this->required = $required;
	}
	
	public function get_required()
	{
		return !empty($this->required) ? $this->required : 0;
	}
	
	public function set_display($values)
	{
		$this->display = $values;
	}
	
	public function get_display()
	{
		return $this->display;
	}
	
	public function set_regex($regex)
	{
		$this->regex = $regex;
	}
	
	public function get_regex()
	{
		return !empty($this->regex) ? $this->regex : '';
	}
	
	public function set_authorization($authorization)
	{
		$this->authorization = $authorization;
	}
	
	public function get_authorization()
	{
		return $this->authorization;
	}
	
	public static function rewrite_field_name($field_name)
	{
		$field = strtolower($field_name);
		$field = Url::encode_rewrite($field);
		$field = str_replace('-', '_', $field);
		return 'f_' . $field;
	}
	
	public static function rewrite_regex($regex_type)
	{
		if (is_numeric($regex_type))
		{
			$array_regex = array(
				1 => '`^[0-9]+$`',
				2 => '`^[a-z]+$`',
				3 => '`^[a-z0-9]+$`',
				4 => '`^[a-z0-9._-]+@(?:[a-z0-9_-]{2,}\.)+[a-z]{2,4}$`i',
				5 => '`^http(s)?://[a-z0-9._/-]+\.[-[:alnum:]]+\.[a-zA-Z]{2,4}(.*)$`i'
			);
			
			return $array_regex[$regex_type];
		}
		else
		{
			return $regex_type;
		}
	}

}
?>