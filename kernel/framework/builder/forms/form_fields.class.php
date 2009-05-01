<?php
/*##################################################
 *                             form_fields.class.php
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
 * @desc Abstract class which manage Fields.
 * You can specify several option with the argument $fieldOptions :
 * <ul>
 * 	<li>title : The field title</li>
 * 	<li>subtitle : The field subtitle</li>
 * 	<li>value : The default value for the field</li>
 * 	<li>id : The field identifier</li>
 * 	<li>class : The css class used for the field</li>
 * 	<li>required : Specify if the field is required.</li>
 * 	<li>onblur : Action performed when cursor is clicked outside the field area. (javascript)</li>
 * </ul>
 * @package builder
 */
class FormFields
{
	/**
	 * @desc Abstract class
	 * @param string $fieldId Name of the field.
	 * @param array $fieldOptions Option for the field.
	 */
	function FormFields($fieldId, $fieldOptions)
	{
		$this->field_name = $fieldId;
		$this->field_id = $fieldId;
		
		foreach($fieldOptions as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'title' :
					$this->field_title = $value;
				break;
				case 'subtitle' :
					$this->field_sub_title = $value;
				break;
				case 'value' :
					$this->field_value = $value;
				break;
				case 'id' :
					$this->field_id = $value;
				break;
				case 'class' :
					$this->field_css_class = $value;
				break;
				case 'required' :
					$this->field_required = $value;
				break;
				case 'onblur' :
					$this->field_maxlength = $value;
				break;
			}
		}
	}
	
	/**
	 * @desc Check if the field can accept option.
	 * @return boolean true if field accept options, false otherwise.
	 */
	 function has_option()
	{
		return $this->has_option;
	}
	
	var $field_title = '';
	var $field_sub_title = '';
	var $field_name = '';
	var $field_value = '';
	var $field_id = '';
	var $field_css_class = '';
	var $field_required = '';
	var $field_on_blur = '';
	var $has_option = '';
}

?>