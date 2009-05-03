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

import('builder/form/form_field');

/**
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class manages the checkbox fields.
 * It provides you some additionnal field options:
 * <ul>
 * 	<li>optiontitle : The option title</li>
 * 	<li>checked : Specify it whether the option has to be checked.</li>
 * </ul>
 * @package builder
 * @subpackage form
 */
class FormCheckboxOption extends FormField
{
	function FormCheckboxOption($field_options)
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
				default :
					$this->throw_error(sprintf('Unsupported option %s in field option type ' . __CLASS__, $attribute), E_USER_NOTICE);
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