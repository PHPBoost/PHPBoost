<?php
/**
 * @package     Builder
 * @subpackage  Form\fieldset
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2010 02 15
*/

interface FormFieldset extends FormElement
{
    function get_id();
    /**
     * Adds a list in the container
     * @param FormField $field The field to add
     */
    function add_field(FormField $field);

    /**
     * Adds a form element to the fieldset
     * @param FormElement $element The element to add
     */
    function add_element(FormElement $element);

    /**
     * Sets the id prefix for fields
     * @param string $prefix The new id prefix for fields.
     */
    function set_form_id($prefix);

    function get_html_id();

    function validate();

    function disable();

    function enable();

    function is_disabled();

    function get_onsubmit_validations();

    function get_validation_error_messages();


    /**
     * @return bool
     */
    function has_field($field_id);

    /**
     * @return FormField
     */
    function get_field($field_id);

    function get_fields();

    function set_template(Template $template);
}

?>
