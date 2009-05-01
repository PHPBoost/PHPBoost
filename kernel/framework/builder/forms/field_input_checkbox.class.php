<?php
/*##################################################
 *                             field_input_checkbox.class.php
 *                            -------------------
 *   begin                : April 28, 2009
 *   copyright            : (C) 2009 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

import('builder/forms/form_fields');

/**
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class manage checkbox input fields.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>optiontitle : The option title</li>
 * 	<li>checked : Specify if the option has to be checked.</li>
 * </ul>
 * @package builder
 */
class FormInputCheckbox extends FormFields
{
	function FormInputCheckbox($fieldId, $field_options)
	{
		parent::FormFields($fieldId, $field_options);
		$this->has_option = true;
		
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'optiontitle' :
					$this->field_option_title = $value;
				break;
				case 'checked' :
					$this->field_checked = $value;
				break;
			}
		}
	}
	
		/**
	 * @desc Add an option for the radio field.
	 * @param object $option The new option. 
	 */
	function add_option(&$option)
	{
		$this->field_options .= '<label><input type="checkbox" ';
		$this->field_options .= !empty($option->field_name) ? 'name="' . $option->field_name . '" ' : '';
		$this->field_options .= !empty($option->field_value) ? 'value="' . $option->field_value . '" ' : '';
		$this->field_options .= !empty($option->field_checked) ? 'checked="checked" ' : '';
		$this->field_options .= '/> ' . $option->field_option_title . '</label><br />' . "\n";
	}
	
	/**
	 * @return string The html code for the checkbox input.
	 */
	function display()
	{
		$Template = new Template('framework/builder/forms/fields.tpl');
			
		$Template->assign_vars(array(
			'ID' => $this->field_id,
			'FIELD' => $this->field_options,
			'L_FIELD_NAME' => $this->field_title,
			'L_EXPLAIN' => $this->field_sub_title,
			'L_REQUIRE' => $this->field_required ? '* ' : ''
		));	
		
		return $Template->parse(TEMPLATE_STRING_MODE);
	}

	var $field_options = '';
	var $field_checked = '';
	var $field_option_title = '';
}

?>