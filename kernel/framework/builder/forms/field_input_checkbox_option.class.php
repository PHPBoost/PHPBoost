<?php
/*##################################################
 *                             field_input_checkbox_option.class.php
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

import('builder/forms/form_field');

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
class FormInputCheckboxOption extends FormField
{
	function FormInputCheckboxOption($field_options)
	{
		parent::FormField('', $field_options);
		
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'optiontitle' :
					$this->option_title = $value;
				break;
				case 'checked' :
					$this->option_checked = $value;
				break;
			}
		}
	}
	
	/**
	 * @return string The html code for the radio input.
	 */
	function display()
	{
		$option = '<label><input type="checkbox" ';
		$option .= !empty($this->field_name) ? 'name="' . $this->field_name . '" ' : '';
		$option .= !empty($this->field_value) ? 'value="' . $this->field_value . '" ' : '';
		$option .= (boolean)$this->option_checked ? 'checked="checked" ' : '';
		$option .= '/> ' . $this->option_title . '</label><br />' . "\n";
		
		return $option;
	}

	var $option_title = '';
	var $option_checked = false;
}

?>