<?php
/*##################################################
 *                             field_input_file.class.php
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
 * @desc This class manage file input fields.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>size : The size for the field</li>
 * </ul>
 * @package builder
 */
class FormInputFile extends FormFields
{
	function FormInputFile($fieldName, $fieldOptions)
	{
		parent::FormFields($fieldName, $fieldOptions);
		
		foreach($fieldOptions as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'size' :
					$this->fieldSize = $value;
				break;
			}
		}
	}
	
	/**
	 * @return string The html code for the file input.
	 */
	function display()
	{
		$Template = new Template('framework/helper/forms/fields.tpl');
			
		$field = '<input type="file" ';
		$field .= !empty($this->fieldSize) ? 'size="' . $this->fieldSize . '" ' : '';
		$field .= !empty($this->fieldName) ? 'name="' . $this->fieldName . '" ' : '';
		$field .= !empty($this->fieldId) ? 'id="' . $this->fieldId . '" ' : '';
		$field .= !empty($this->fieldCssClass) ? 'class="' . $this->fieldCssClass . '" ' : '';
		$field .= '/>
		<input name="max_file_size" value="2000000" type="hidden">';
		
		$Template->assign_vars(array(
			'ID' => $this->fieldId,
			'FIELD' => $field,
			'L_FIELD_NAME' => $this->fieldTitle,
			'L_EXPLAIN' => $this->fieldSubTitle,
			'L_REQUIRE' => $this->fieldRequired ? '* ' : ''
		));	
		
		return $Template->parse(TEMPLATE_STRING_MODE);
	}

	var $fieldSize = '';
}

?>