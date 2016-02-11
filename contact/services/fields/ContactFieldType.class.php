<?php
/*##################################################
 *                               ContactFieldType.class.php
 *                            -------------------
 *   begin                : July 31, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 * @package {@package}
 */
interface ContactFieldType
{
	/**
	 * @desc This function displayed field for form
	 * @param instance of ContactField $field.
	 */
	public function display_field(ContactField $field);

	/**
	 * @desc This function returned value form fields
	 * @param instance of HTMLForm $form and instance of ContactField $field.
	 */
	public function get_value(HTMLForm $form, ContactField $field);
	
	/**
	 * @desc Return instanciat constraint depending integer type regex.
	 * @return integer
	 */
	public function constraint($value);
	
	public function set_disable_fields_configuration(array $names);
	
	/**
	 * @return Array
	 */
	public function get_disable_fields_configuration();
	
	public function set_name($name);
	
	/**
	 * @return String
	 */
	public function get_name();
}
?>
