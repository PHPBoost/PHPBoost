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
 * @subpackage form
 */
class FormField
{
	/**
	 * @desc constructor
	 * @param string $fieldId Name of the field.
	 * @param array $fieldOptions Option for the field.
	 */
	function FormField($fieldId, &$fieldOptions)
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
					unset($fieldOptions['title']);
				break;
				case 'subtitle' :
					$this->field_sub_title = $value;
					unset($fieldOptions['subtitle']);
				break;
				case 'value' :
					$this->field_value = $value;
					unset($fieldOptions['value']);
				break;
				case 'id' :
					$this->field_id = $value;
					unset($fieldOptions['id']);
				break;
				case 'class' :
					$this->field_css_class = $value;
					unset($fieldOptions['class']);
				break;
				case 'required' :
					$this->field_required = $value;
					unset($fieldOptions['required']);
				break;
				case 'required_alert' :
					$this->field_required_alert = $value;
					unset($fieldOptions['required_alert']);
				break;
				case 'onblur' :
					$this->field_maxlength = $value;
					unset($fieldOptions['onblur']);
				break;
			}
		}
	}
	
	/**
	 * @desc Store all erros in the field construct process.
	 * @param string $errstr  Error message description
	 * @param int $errno Error type, use php constants.
	 */	
	function throw_error($errstr, $errno)
	{
		$this->field_errors[] = array('errstr' => $errstr, 'errno' => $errno);
	}
	
	/**
	 * @desc Merge errors.
	 * @param array $array_errors
	 */	
	function add_errors(&$array_errors)
	{
		$this->field_errors = array_merge($this->field_errors, $array_errors);
	}
	
	/**
	 * @desc  Get all errors occured in the field construct process.
	 * @return array All errors
	 */	
	function get_errors() { return $this->field_errors; }
	
	/**
	 * @return string The fied identifier.
	 */
	function get_id() { return $this->field_id;}
	
	/**
	 * @return string Text displayed if field is empty.
	 */
	function get_required_alert() { return $this->field_required_alert;}
	
	
	var $field_title = '';
	var $field_sub_title = '';
	var $field_name = '';
	var $field_value = '';
	var $field_id = '';
	var $field_css_class = '';
	var $field_required = false;
	var $field_required_alert = '';
	var $field_on_blur = '';
	var $field_errors = array();
}

?>