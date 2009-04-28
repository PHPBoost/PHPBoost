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
 * @author Régis
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
 * @package helpers
 */
class FormFields
{
	/**
	 * @desc Abstract class
	 * @param string $fieldName Name of the field.
	 * @param array $fieldOptions Option for the field.
	 */
	function FormFields($fieldName, $fieldOptions)
	{
		$this->fieldName = $fieldName;
		$this->fieldId = $fieldName;
		
		foreach($fieldOptions as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'title' :
					$this->fieldTitle = $value;
				break;
				case 'subtitle' :
					$this->fieldSubTitle = $value;
				break;
				case 'value' :
					$this->fieldValue = $value;
				break;
				case 'id' :
					$this->fieldId = $value;
				break;
				case 'class' :
					$this->fieldCssClass = $value;
				break;
				case 'required' :
					$this->fieldRequired = $value;
				break;
				case 'onblur' :
					$this->fieldMaxlength = $value;
				break;
			}
		}
	}
	
	var $fieldTitle = '';
	var $fieldSubTitle = '';
	var $fieldName = '';
	var $fieldValue = '';
	var $fieldId = '';
	var $fieldCssClass = '';
	var $fieldRequired = '';
	var $fieldOnBlur = '';
}

?>