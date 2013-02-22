<?php
/*##################################################
 *                           FormField.class.php
 *                            -------------------
 *   begin                : April 28, 2009
 *   copyright            : (C) 2009 Viarre Régis
 *   email                : crowkait@phpboost.com
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
 * 
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 * @package {@package}
 */
interface FormField extends FormElement
{
	/**
	 * @desc Returns the id.
	 * @return string
	 */
	function get_id();

	/**
	 * @desc Returns the label
	 * @return string
	 */
	function get_label();

	/**
	 * @desc Sets the id
	 * @param string $id The id.
	 */
	function set_id($id);

	/**
	 * @desc Sets the id prefix
	 * @param string $prefix The new id prefix.
	 */
	function set_form_id($prefix);
	
	/**
	 * @desc Sets the if of the fieldset which contains the field
	 * @param $fieldset_id id of the fieldset
	 */
	function set_fieldset_id($fieldset_id);

	/**
	 * @return mixed
	 */
	function get_value();

	/**
	 * @desc Sets the value
	 * @param string $value The value
	 */
	function set_value($value);

	/**
	 * @desc Tries to retrieve the value in the HTTP request's parameters.
	 */
	function retrieve_value();

	/**
	 * @desc Returns the effective HTML id.
	 * @return string
	 */
	function get_html_id();
	
	/**
	 * @desc Tells whether the field is disabled
	 * @return bool
	 */
	function is_disabled();
	
	/**
	 * @desc Disabled the field
	 */
	function disable();

	/**
	 * @desc Enables the field
	 */
	function enable();

	/**
	 * @desc Validates the field by cheching if all the constraints are satisfied.
	 * @return bool true if the form is valid
	 */
	function validate();

	/**
	 * @desc Returns validation error message.
	 */
	function get_validation_error_message();

	/**
	 * @desc Set the validation error message.
	 * @param string $error_message The message to set
	 */
	function set_validation_error_message($error_message);

	/**
	 * @desc Adds a constraint to the field constraints.
	 * @param FormFieldConstraint $constraint The constraint to add
	 */
	function add_constraint(FormFieldConstraint $constraint);

	/**
	 * @desc Add javascript code on the onblur field parameter that makes validation
	 */
	function add_form_constraint(FormConstraint $constraint);

	/**
	 * @desc Return true if the field has one or more constraints, false otherwise.
	 * @return boolean
	 */
	function has_constraints();

	/**
	 * @desc Returns the javascript onsubmit code.
	 * @return string The javascript code that makes the validation when the form is submitted
	 */
	function get_js_validations();
}
?>