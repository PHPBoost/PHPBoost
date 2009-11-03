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
class FormCheckboxOption extends FormFieldComposite
{
	private $checked = false;
	
	const CHECKED = true;

	/**
	 * @param $label string The label for the checkbox option
	 * @param $value string The value for the checkbox option
	 * @param $checked boolean set to FORM__CHECKBOX_CHECKED to check the option
	 */
	public function __construct($label, $value = '', $checked = false)
	{
		$this->label = $label;
		$this->value = $value;
		$this->checked = $checked;
	}
	
	/**
	 * @return string The html code for the radio input.
	 */
	public function display()
	{
		$option = '<label><input type="checkbox" ';
		$option .= 'name="' . $this->name . '" ';
		$option .= !empty($this->value) ? 'value="' . $this->value . '" ' : '';
		$option .= (boolean)$this->checked ? 'checked="checked" ' : '';
		$option .= '/> ' . $this->label . '</label><br />' . "\n";
		
		return $option;
	}
}

?>