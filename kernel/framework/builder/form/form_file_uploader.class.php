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

import('builder/form/form_field');

/**
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class manage file input fields.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>size : The size for the field</li>
 * </ul>
 * @package builder
 * @subpackage form
 */
class FormFileUploader extends FormField
{
	function FormFileUploader($fieldId, $field_options)
	{
		parent::FormField($fieldId, $field_options);
		
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'size' :
					$this->field_size = $value;
				break;
				default :
					$this->throw_error(sprintf('Unsupported option %s with field ' . __CLASS__, $attribute), E_USER_NOTICE);
			}
		}
	}
	
	/**
	 * @return string The html code for the file input.
	 */
	function display()
	{
		$Template = new Template('framework/builder/forms/field.tpl');
			
		$field = '<input type="file" ';
		$field .= !empty($this->field_size) ? 'size="' . $this->field_size . '" ' : '';
		$field .= !empty($this->field_name) ? 'name="' . $this->field_name . '" ' : '';
		$field .= !empty($this->field_id) ? 'id="' . $this->field_id . '" ' : '';
		$field .= !empty($this->field_css_class) ? 'class="' . $this->field_css_class . '" ' : '';
		$field .= '/>
		<input name="max_file_size" value="2000000" type="hidden">';
		
		$Template->assign_vars(array(
			'ID' => $this->field_id,
			'FIELD' => $field,
			'L_FIELD_TITLE' => $this->field_title,
			'L_EXPLAIN' => $this->field_sub_title,
			'L_REQUIRE' => $this->field_required ? '* ' : ''
		));	
		
		return $Template->parse(TEMPLATE_STRING_MODE);
	}

	var $field_size = '';
}

?>