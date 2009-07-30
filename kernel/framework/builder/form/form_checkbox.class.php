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

import('builder/form/form_field');
import('builder/form/form_checkbox_option');

/**
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class manages checkbox input fields.
 * @package builder
 * @subpackage form
 */
class FormCheckbox extends FormField
{
	function FormCheckbox()
	{
		$fieldId = func_get_arg(0);
		$field_options = func_get_arg(1);

		parent::FormField($fieldId, $field_options);
		foreach($field_options as $attribute => $value)
			$this->throw_error(sprintf('Unsupported option %s with field ' . __CLASS__, strtolower($attribute)), E_USER_NOTICE);
		
		$nbr_arg = func_num_args() - 1;		
		for ($i = 2; $i <= $nbr_arg; $i++)
		{
			$option = func_get_arg($i);
			$this->add_errors($option->get_errors());
			$this->field_options[] = $option;
		}
	}
	
	/**
	 * @desc Add an option for the radio field.
	 * @param FormRadioChoiceOption option The new option. 
	 */
	function add_option(&$option)
	{
		$this->field_options[] = $option;
	}
	
	/**
	 * @return string The html code for the checkbox input.
	 */
	function display()
	{
		$Template = new Template('framework/builder/forms/field_box.tpl');
			
		$Template->assign_vars(array(
			'ID' => $this->field_id,
			'FIELD' => $this->field_options,
			'L_FIELD_TITLE' => $this->field_title,
			'L_EXPLAIN' => $this->field_sub_title,
			'L_REQUIRE' => $this->field_required ? '* ' : ''
		));	
		
		foreach($this->field_options as $Option)
		{
			$Option->field_name = $this->field_name; //Set the same field name for each option.
			$Template->assign_block_vars('field_options', array(
				'OPTION' => $Option->display(),
			));	
		}
		
		return $Template->parse(TEMPLATE_STRING_MODE);
	}

	var $field_options = array();
}

?>