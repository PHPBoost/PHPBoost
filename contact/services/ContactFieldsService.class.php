<?php
/*##################################################
 *                               ContactFieldsService.class.php
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
 * @desc This class is a Factory and return instance class
 * @package {@package}
 */
class ContactFieldsService
{
	/**
	 * @desc This function displayed field for form
	 * @param object $field ContactField
	 */
	public static function display_field(ContactField $field)
	{
		$class = $field->get_instance();
		return $class->display_field($field);
	}
	
	/**
	 * @desc This function returned value form fields
	 * @param object $form HTMLForm
	 * @param object $field ContactField
	 */
	public static function get_value(HTMLForm $form, ContactField $field)
	{
		$class = $field->get_instance();
		return $class->get_value($form, $field);
	}
}
?>
