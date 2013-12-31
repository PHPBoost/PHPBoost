<?php
/*##################################################
 *                               ContactFieldsFactory.class.php
 *                            -------------------
 *   begin                : July 31, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 * @desc This class is a Factory and return instance class
 * @package {@package}
 */
class ContactFieldsFactory
{
	/**
	 * @desc This function displayed field for form
	 * @param object $field ContactField
	 */
	public static function display_field(ContactField $field)
	{
		$name_class = self::name_class($field);
		
		$instance_class = new $name_class();
		return $instance_class->display_field($field);
	}
	
	/**
	 * @desc This function returned value form fields
	 * @param object $form HTMLForm
	 * @param object $field ContactField
	 */
	public static function return_value(HTMLForm $form, ContactField $field)
	{
		$name_class = self::name_class($field);
		
		$instance_class = new $name_class();
		return $instance_class->return_value($form, $field);
	}
	
	/**
	 * @desc This function return Array disable fields in configuration
	 * @param string $field_type field type.
	 */
	public static function get_disable_fields_configuration($field_type)
	{
		$field = new ContactField();
		$field->set_field_type($field_type);
		
		$name_class = self::name_class($field);
		
		$instance_class = new $name_class();
		return $instance_class->get_disable_fields_configuration();
	}
	
	/**
	 * @desc This function determines the class depending on the type of field
	 * @param object $field ContactField
	 */
	public static function name_class(ContactField $field)
	{
		$field_type = $field->get_field_type();
		if (!empty($field_type))
		{
			return (string)$field_type;
		}
		return '';
	}
}
?>
