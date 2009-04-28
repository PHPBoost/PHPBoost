<?php
/*##################################################
 *                             field_select.class.php
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

import('helpers/forms/form_fields');

/**
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class manage select fields.
 * @package helpers
 */
class FormSelect extends FormFields
{
	function FormSelect($fieldName, $fieldOptions)
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
				case 'selected' :
					$this->fieldSelected = $value;
				break;
				case 'multiple' :
					$this->fieldMultiple = $value;
				break;
			}
		}
	}
	
	function addOption(&$option)
	{
		$this->fieldOptions .= '<option ';
		$this->fieldOptions .= !empty($option->fieldValue) ? 'value="' . $option->fieldValue . '" ' : '';
		$this->fieldOptions .= !empty($option->fieldSelected) ? 'selected="selected" ' : '';
		$this->fieldOptions .= '> ' . $option->fieldOptionTitle . '</option>' . "\n";
	}
	
	/**
	 * @return string The html code for the select.
	 */
	function display()
	{
		$Template = new Template('framework/helper/forms/fields.tpl');
		
		if ($this->fieldMultiple)
			$field = '<select name="' . $this->fieldName . '[]" multiple="multiple">' . $this->fieldOptions . '</select>';
		else
			$field = '<select name="' . $this->fieldName . '">' . $this->fieldOptions . '</select>';
			
		$Template->assign_vars(array(
			'ID' => $this->fieldId,
			'FIELD' => $field,
			'L_FIELD_NAME' => $this->fieldTitle,
			'L_EXPLAIN' => $this->fieldSubTitle,
			'L_REQUIRE' => $this->fieldRequired ? '* ' : ''
		));	
		
		return $Template->parse(TEMPLATE_STRING_MODE);
	}

	var $fieldOptions = '';
	var $fieldSelected = '';
	var $fieldOptionTitle = '';
	var $fieldMultiple = '';
}

?>