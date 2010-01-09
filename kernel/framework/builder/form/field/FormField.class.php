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

interface FormField
{
	/**
	 * @desc Returns the id.
	 * @return string
	 */
	public function get_id();
	
	/**
	 * @desc Sets the id
	 * @param string $id The id.
	 */
	public function set_id($id);

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
	public function retrieve_value();
	
	/**
	 * @desc Returns the effective HTML id.
	 * @return string
	 */
	public function get_html_id();

	/**
	 * @return Template
	 */
	function display();

	/**
	 * @desc Validates the field by cheching if all the constraints are satisfied.
	 * @return bool true if the form is valid
	 */
	function validate();

	/**
	 * @desc Adds a constraint to the field constraints.
	 * @param FormFieldConstraint $constraint The constraint to add
	 */
	function add_constraint(FormFieldConstraint $constraint);

	/**
	 * @desc Returns the javascript onsubmit code.
	 * @return string The javascript code that makes the validation when the form is submitted
	 */
	function get_onsubmit_validations();
}

?>