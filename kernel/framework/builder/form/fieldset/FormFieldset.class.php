<?php
/*##################################################
 *                          FormFieldset.class.php
 *                            -------------------
 *   begin                : February 15, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @package {@package}
 * @desc
 * @author Régis Viarre <crowkait@phpboost.com>
 */
interface FormFieldset extends FormElement
{
	function get_id();
    /**
     * @desc Adds a list in the container
     * @param FormField $field The field to add
     */
    function add_field(FormField $field);
    
    /**
     * @desc Adds a form element to the fieldset
     * @param FormElement $element The element to add
     */
    function add_element(FormElement $element);

    /**
     * @desc Sets the id prefix for fields
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