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
abstract class FormField
{
	protected $title = '';
	protected $sub_title = '';
	protected $name = '';
	protected $value = '';
	protected $id = '';
	protected $css_class = '';
	protected $required = false;
	protected $required_alert = '';
	protected $on_blur = '';
	protected $errors = array();
	
	public abstract function display();
	
	/**
	 * @param string $fieldId Name of the field.
	 * @param array $fieldOptions Option for the field.
	 */
	public function fill_attributes($fieldId, &$fieldOptions)
	{
		$this->name = $fieldId;
		$this->id = $fieldId;
		
		foreach($fieldOptions as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'title' :
					$this->title = $value;
					unset($fieldOptions['title']);
				break;
				case 'subtitle' :
					$this->sub_title = $value;
					unset($fieldOptions['subtitle']);
				break;
				case 'value' :
					$this->value = $value;
					unset($fieldOptions['value']);
				break;
				case 'id' :
					$this->id = $value;
					unset($fieldOptions['id']);
				break;
				case 'class' :
					$this->css_class = $value;
					unset($fieldOptions['class']);
				break;
				case 'required' :
					$this->required = $value;
					unset($fieldOptions['required']);
				break;
				case 'required_alert' :
					$this->required_alert = $value;
					unset($fieldOptions['required_alert']);
				break;		
				case 'onblur' :
					$this->maxlength = $value;
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
	protected function throw_error($errstr, $errno)
	{
		$this->errors[] = array('errstr' => $errstr, 'errno' => $errno);
	}
	
	/**
	 * @desc Merge errors.
	 * @param array $array_errors
	 */	
	protected function add_errors(&$array_errors)
	{
		$this->errors = array_merge($this->errors, $array_errors);
	}
	
	/**
	 * @desc  Get all errors occured in the field construct process.
	 * @return array All errors
	 */	
	public function get_errors() { return $this->errors; }
	
	/**
	 * @return string The fied identifier.
	 */
	public function get_id() { return $this->id;}
	
	/**
	 * @return string Text displayed if field is empty.
	 */
	public function get_required_alert() { return $this->required_alert;}
}

?>