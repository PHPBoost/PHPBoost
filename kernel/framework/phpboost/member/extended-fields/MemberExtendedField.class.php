<?php
/*##################################################
 *                               MemberExtendedField.class.php
 *                            -------------------
 *   begin                : September 2, 2010
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
class MemberExtendedField
{
	private $name;
	private $value;
	private $description;
	private $field_type;
	private $field_name;
	private $field_value;
	private $required;
	private $regex_type;
	private $regex;
	private $default_values;
	private $possible_values;
	private $user_id;
	private $template;
	private $fieldset;
	
	public function set_name($name)
	{
		$this->name = $name;
	}
	
	public function get_name()
	{
		return $this->name;
	}
	
	public function set_value($value)
	{
		$this->value = $value;
	}
	
	public function get_value()
	{
		return $this->value;
	}
	
	public function set_description($description)
	{
		$this->description = $description;
	}
	
	public function get_description()
	{
		return $this->description;
	}
	
	public function set_field_type($field_type)
	{
		$this->field_type = $field_type;
	}
	
	public function get_field_type()
	{
		return $this->field_type;
	}
	
	public function set_field_name($field_name)
	{
		$this->field_name = $field_name;
	}
	
	public function get_field_name()
	{
		return $this->field_name;
	}
	
	public function set_field_value($field_value)
	{
		$this->field_value = $field_value;
	}
	
	public function get_field_value()
	{
		return $this->field_value;
	}
	
	public function set_required($required)
	{
		$this->required = $required;
	}
	
	public function get_required()
	{
		return $this->required;
	}
	
	public function set_regex_type($regex_type)
	{
		$this->regex_type = $regex_type;
	}
	
	public function get_regex_type()
	{
		return $this->regex_type;
	}
	
	public function set_regex($regex)
	{
		$this->regex = $regex;
	}
	
	public function get_regex()
	{
		return $this->regex;
	}
	
	public function set_default_values($default_values)
	{
		$this->default_values = $default_values;
	}
	
	public function get_default_values()
	{
		return $this->default_values;
	}
	
	public function set_possible_values($possible_values)
	{
		$this->possible_values = $possible_values;
	}
	
	public function get_possible_values()
	{
		return $this->possible_values;
	}
	
	public function set_user_id($user_id)
	{
		$this->user_id = $user_id;
	}
	
	public function get_user_id()
	{
		return $this->user_id;
	}
	
	public function set_template($template)
	{
		$this->template = $template;
	}
	
	public function get_template()
	{
		return $this->template;
	}
	
	public function set_fieldset($fieldset)
	{
		$this->fieldset = $fieldset;
	}
	
	public function get_fieldset()
	{
		return $this->fieldset;
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