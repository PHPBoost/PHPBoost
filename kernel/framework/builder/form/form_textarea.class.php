<?php
/*##################################################
 *                             field_input_text.class.php
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
 * @desc This class manage multi-line text fields.
 * @package builder
 * @subpackage form
 */
class FormTextarea extends FormField
{
	function FormTextarea($fieldId, $fieldOptions)
	{
		parent::FormField($fieldId, $fieldOptions);
		foreach($fieldOptions as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'rows' :
					$this->field_rows = $value;
				break;
				case 'cols' :
					$this->field_cols = $value;
				break;
				case 'editor' :
					$this->field_editor = $value;
				break;
				case 'forbiddentags' :
					$this->field_forbidden_tags = $value;
				break;
				default :
					$this->throw_error(sprintf('Unsupported option %s with field ' . __CLASS__, $attribute), E_USER_NOTICE);
			}
		}
	}
	
	/**
	 * @return string The html code for the textarea.
	 */
	function display()
	{
		$Template = new Template('framework/builder/forms/field_extended.tpl');
			
		$field = '<textarea type="text" ';
		$field .= !empty($this->field_rows) ? 'rows="' . $this->field_rows . '" ' : '';
		$field .= !empty($this->field_cols) ? 'cols="' . $this->field_cols . '" ' : '';
		$field .= !empty($this->field_name) ? 'name="' . $this->field_name . '" ' : '';
		$field .= !empty($this->field_id) ? 'id="' . $this->field_id . '" ' : '';
		$field .= !empty($this->field_css_class) ? 'class="' . $this->field_css_class . '"> ' : '>';
		$field .= !empty($this->field_value) ? $this->field_value : '';
		$field .= '</textarea>';
		
		$Template->assign_vars(array(
			'ID' => $this->field_id,
			'FIELD' => $field,
			'KERNEL_EDITOR' => $this->field_editor ? display_editor($this->field_id, $this->field_forbidden_tags) : '',
			'L_FIELD_TITLE' => $this->field_title,
			'L_EXPLAIN' => $this->field_sub_title,
			'L_REQUIRE' => $this->field_required ? '* ' : ''
		));	
		
		return $Template->parse(TEMPLATE_STRING_MODE);
	}

	var $field_rows = ''; //Rows for the textarea.
	var $field_cols = ''; //Cols for the textarea.
	var $field_editor = true; //Allow to hide the editor.
	var $field_forbidden_tags = array(); //Forbiddend tags in the content.
}

?>