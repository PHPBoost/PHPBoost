<?php
/*##################################################
 *                             FormField.class.php
 *                            -------------------
 *   begin                : April 28, 2009
 *   copyright            : (C) 2009 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc Abstract class which manage Fields.
 * You can specify several option with the argument $field_options :
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
 * @abstract
 */
abstract class FormField implements ValidableFormComponent
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
	private $constraints = array();

	public abstract function display();

	/**
	 * @param string $field_id Name of the field.
	 * @param array $field_options Option for the field.
	 */
	protected function __construct($field_id, $value, array &$field_options, array $constraints)
	{
		$this->name = $field_id;
		$this->id = $field_id;
		$this->value = $value;
		$this->constraints = $constraints;
		$this->compute_options($field_options);
	}

	public function validate()
	{
		$this->retrieve_value();
		$validation_result = true;
		foreach ($this->constraints as $constraint)
		{
			$validation_result = $validation_result && $constraint->validate($this);
		}
		return $validation_result;
	}

	public function retrieve_value()
	{
		if (isset($_REQUEST[$this->id]))
		{
			$this->value = $_REQUEST[$this->id];
		}
		else
		{
			$this->value = null;
		}
	}

	## Getters and Setters ##
	/**
	 * @return string The fied identifier.
	 */
	public function get_id() { return $this->id;}
	public function get_value() { return $this->value; }
	public function set_value($var) { $this->value = $var; }

	private function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'title' :
					$this->title = $value;
					unset($field_options['title']);
					break;
				case 'subtitle' :
					$this->sub_title = $value;
					unset($field_options['subtitle']);
					break;
				case 'id' :
					$this->id = $value;
					unset($field_options['id']);
					break;
				case 'class' :
					$this->css_class = $value;
					unset($field_options['class']);
					break;
				case 'required' :
					$this->required = $value;
					if ($this->required)
					{
						$this->constraints[] = new NotEmptyFormFieldConstraint($this->id);
					}
					unset($field_options['required']);
					break;
				case 'onblur' :
					$this->maxlength = $value;
					unset($field_options['onblur']);
					break;
			}
		}
	}
}

?>