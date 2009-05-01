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

import('builder/forms/form_fields');

/**
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class manage select fields.
 * @package builder
 */
class FormSelect extends FormFields
{
	function FormSelect($field_name, $field_options)
	{
		parent::FormFields($field_name, $field_options);
		$this->has_option = true;
		
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'optiontitle' :
					$this->field_option_title = $value;
				break;
				case 'selected' :
					$this->field_selected = $value;
				break;
				case 'multiple' :
					$this->field_multiple = $value;
				break;
			}
		}
	}
	
	function add_option(&$option)
	{
		$this->field_options .= '<option ';
		$this->field_options .= !empty($option->field_value) ? 'value="' . $option->field_value . '" ' : '';
		$this->field_options .= !empty($option->field_selected) ? 'selected="selected" ' : '';
		$this->field_options .= '> ' . $option->field_option_title . '</option>' . "\n";
	}
	
	/**
	 * @return string The html code for the select.
	 */
	function display()
	{
		$Template = new Template('framework/builder/forms/fields.tpl');
		
		if ($this->field_multiple)
			$field = '<select name="' . $this->field_name . '[]" multiple="multiple">' . $this->field_options . '</select>';
		else
			$field = '<select name="' . $this->field_name . '">' . $this->field_options . '</select>';
			
		$Template->assign_vars(array(
			'ID' => $this->field_id,
			'FIELD' => $field,
			'L_FIELD_NAME' => $this->field_title,
			'L_EXPLAIN' => $this->field_sub_title,
			'L_REQUIRE' => $this->field_required ? '* ' : ''
		));	
		
		return $Template->parse(TEMPLATE_STRING_MODE);
	}

	var $field_options = '';
	var $field_selected = '';
	var $field_option_title = '';
	var $field_multiple = '';
}

?>