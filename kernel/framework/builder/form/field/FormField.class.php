<?php
/**
 * This class manage radio input fields.
 *
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 22
 * @since       PHPBoost 2.0 - 2009 04 28
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface FormField extends FormElement
{
	function get_id(): string;

	function get_label(): string;

	function set_id(string $id): void;

	function set_form_id(string $prefix): void;

	function set_fieldset_id(string $fieldset_id): void;

	function get_value(): mixed;

	function set_value(string $value);

	function retrieve_value(): void;

	function get_html_id(): string;

	function is_disabled(): bool;

	function is_required(): bool;

	function disable(): void;

	function enable(): void;

    function is_hidden(): bool;

    public function set_hidden(bool $hidden): void;

	/**
	 * Validates the field by cheching if all the constraints are satisfied.
	 * @return bool true if the form is valid
	 */
	function validate(): mixed;

	function get_validation_error_message(): string;

	/**
	 * Set the validation error message.
	 * @param string $error_message The message to set
	 */
	function set_validation_error_message(string $error_message): void;

	/**
	 * Adds a constraint to the field constraints.
	 */
	function add_constraint(FormFieldConstraint $constraint): void;

	/**
	 * Add javascript code on the onblur field parameter that makes validation
	 */
	function add_form_constraint(FormConstraint $constraint): void;

	/**
	 * Return true if the field has one or more constraints, false otherwise.
	 */
	function has_constraints(): bool;

	/**
	 * Returns the javascript onsubmit code.
	 * @return string The javascript code that makes the validation when the form is submitted
	 */
	function get_js_validations(): array|string;
}
?>
