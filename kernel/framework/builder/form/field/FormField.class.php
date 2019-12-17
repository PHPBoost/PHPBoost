<?php
/**
 * This class manage radio input fields.
 *
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 2.0 - 2009 04 28
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

interface FormField extends FormElement
{
	/**
	 * Returns the id.
	 * @return string
	 */
	function get_id();

	/**
	 * Returns the label
	 * @return string
	 */
	function get_label();

	/**
	 * Sets the id
	 * @param string $id The id.
	 */
	function set_id($id);

	/**
	 * Sets the id prefix
	 * @param string $prefix The new id prefix.
	 */
	function set_form_id($prefix);

	/**
	 * Sets the if of the fieldset which contains the field
	 * @param $fieldset_id id of the fieldset
	 */
	function set_fieldset_id($fieldset_id);

	/**
	 * @return mixed
	 */
	function get_value();

	/**
	 * Sets the value
	 * @param string $value The value
	 */
	function set_value($value);

	/**
	 * Tries to retrieve the value in the HTTP request's parameters.
	 */
	function retrieve_value();

	/**
	 * Returns the effective HTML id.
	 * @return string
	 */
	function get_html_id();

	/**
	 * Tells whether the field is disabled
	 * @return bool
	 */
	function is_disabled();

	/**
	 * Disabled the field
	 */
	function disable();

	/**
	 * Enables the field
	 */
	function enable();

	/**
	 * Validates the field by cheching if all the constraints are satisfied.
	 * @return bool true if the form is valid
	 */
	function validate();

	/**
	 * Returns validation error message.
	 */
	function get_validation_error_message();

	/**
	 * Set the validation error message.
	 * @param string $error_message The message to set
	 */
	function set_validation_error_message($error_message);

	/**
	 * Adds a constraint to the field constraints.
	 * @param FormFieldConstraint $constraint The constraint to add
	 */
	function add_constraint(FormFieldConstraint $constraint);

	/**
	 * Add javascript code on the onblur field parameter that makes validation
	 */
	function add_form_constraint(FormConstraint $constraint);

	/**
	 * Return true if the field has one or more constraints, false otherwise.
	 * @return boolean
	 */
	function has_constraints();

	/**
	 * Returns the javascript onsubmit code.
	 * @return string The javascript code that makes the validation when the form is submitted
	 */
	function get_js_validations();
}
?>
