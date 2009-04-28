<?php
/*##################################################
 *                             field_input_radio.class.php
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
 * @desc This class manage radio input fields.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>optiontitle : The option title</li>
 * 	<li>checked : Specify if the option has to be checked.</li>
 * </ul>
 * @package builder
 */
class FormInputRadio extends FormFields
{
	function FormInputRadio($fieldName, $fieldOptions)
	{
		parent::FormFields($fieldName, $fieldOptions);		
		
		foreach($fieldOptions as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'optiontitle' :
					$this->fieldOptionTitle = $value;
				break;
				case 'checked' :
					$this->fieldChecked = $value;
				break;
			}
		}
	}
	
	/**
	 * @desc Add an option for the radio field.
	 * @param object $option The new option. 
	 */
	function addOption(&$option)
	{
		$this->fieldOptions .= '<label><input type="radio" ';
		$this->fieldOptions .= !empty($option->fieldName) ? 'name="' . $option->fieldName . '" ' : '';
		$this->fieldOptions .= !empty($option->fieldValue) ? 'value="' . $option->fieldValue . '" ' : '';
		$this->fieldOptions .= !empty($option->fieldChecked) ? 'checked="checked" ' : '';
		$this->fieldOptions .= '/> ' . $option->fieldOptionTitle . '</label><br />' . "\n";
	}
	
	/**
	 * @return string The html code for the radio input.
	 */
	function display()
	{
		$Template = new Template('framework/helper/forms/fields.tpl');
			
		$Template->assign_vars(array(
			'ID' => $this->fieldId,
			'FIELD' => $this->fieldOptions,
			'L_FIELD_NAME' => $this->fieldTitle,
			'L_EXPLAIN' => $this->fieldSubTitle,
			'L_REQUIRE' => $this->fieldRequired ? '* ' : ''
		));	
		
		return $Template->parse(TEMPLATE_STRING_MODE);
	}

	var $fieldOptions = '';
	var $fieldChecked = '';
	var $fieldOptionTitle = '';
}

?>