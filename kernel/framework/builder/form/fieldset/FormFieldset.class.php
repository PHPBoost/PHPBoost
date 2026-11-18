<?php
/**
 * @package     Builder
 * @subpackage  Form\fieldset
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 21
 * @since       PHPBoost 3.0 - 2010 02 15
*/

interface FormFieldset extends FormElement
{
    function get_id(): string;
    /**
     * Adds a list in the container
     * @param FormField $field The field to add
     */
    function add_field(FormField $field): void;

    /**
     * Adds a form element to the fieldset
     * @param FormElement $element The element to add
     */
    function add_element(FormElement $element): void;

    /**
     * Sets the id prefix for fields
     * @param string $prefix The new id prefix for fields.
     */
    function set_form_id(string $prefix): void;

    function get_html_id(): string;

    function validate(): mixed;

    function disable(): void;

    function enable(): void;

    function is_disabled(): bool;

    function get_onsubmit_validations(): mixed;

    function get_validation_error_messages(): array;

    function has_field(string $field_id): bool;

    function get_field(string $field_id): FormField;

    function get_fields(): array;

    function set_template(Template $template): void;
}

?>
