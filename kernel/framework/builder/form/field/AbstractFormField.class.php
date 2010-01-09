<?php
/*##################################################
 *                       AbstractFormField.class.php
 *                            -------------------
 *   begin                : January 08, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @desc Abstract class that proposes a default implementation for the FormField interface.
 * @package builder
 * @subpackage form
 */
abstract class AbstractFormField implements FormField
{
	/**
	 * @var string
	 */
	protected $id = '';
	/**
	 * @var string
	 */
	protected $label = '';
	/**
	 * @var string
	 */
	protected $description = '';
	/**
	 * @var string
	 */
	protected $value = null;
	/**
	 * @var string
	 */
	protected $css_class = '';
	/**
	 * @var string
	 */
	protected $required = false;
	/**
	 * @var string
	 */
	protected $on_blur = '';
	/**
	 * @var FormFieldConstraint[]
	 */
	protected $constraints = array();

	/**
	 * @param string $field_id Name of the field.
	 * @param array $field_options Option for the field.
	 */
	protected function __construct($id, $label, $value, array $field_options, array $constraints)
	{
		$this->set_id($id);
		$this->set_label($label);
		$this->set_value($value);
		$this->compute_options($field_options);
		
		foreach ($constraints as $constraint)
		{
			$this->add_constraint($constraint);
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/builder/form/field/FormField#get_id()
	 */
	public function get_id()
	{
		return $this->id;
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/builder/form/field/FormField#set_id($id)
	 */
	public function set_id($id)
	{
		$this->id = $id;
	}

	/**
	 * Returns the label
	 * @return string The label
	 */
	public function get_label()
	{
		return $this->label;
	}

	/**
	 * Sets the label
	 * @param string $label The label
	 */
	public function set_label($label)
	{
		$this->label = $label;
	}

	/**
	 * Returns the description
	 * @return string the description
	 */
	public function get_description()
	{
		return $this->description;
	}

	/**
	 * Sets the description
	 * @param string $description The description
	 */
	public function set_description($description)
	{
		$this->description = $description;
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/builder/form/field/FormField#get_value()
	 */
	public function get_value()
	{
		return $this->value;
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/builder/form/field/FormField#set_value($value)
	 */
	public function set_value($value)
	{
		$this->value = $value;
	}
	
	/**
	 * @desc Tells whether the field is required
	 * @return true if it is, false otherwise
	 */
	public function is_required()
	{
		return $this->required;
	}
	
	/**
	 * @desc Changes the fact that the field is required or not.
	 * @param bool $required true if it's required, false otherwise
	 */
	public function set_required($required)
	{
		$this->required = $required;
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/builder/form/field/FormField#validate()
	 */
	public function validate()
	{
		$this->retrieve_value();
		$validation_result = true;
		foreach ($this->constraints as $constraint)
		{
			if (!$constraint->validate($this))
			{
				$validation_result = false;;
			}
		}
		return $validation_result;
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/builder/form/field/FormField#retrieve_value()
	 */
	public function retrieve_value()
	{
		$request = AppContext::get_request();
		if ($request->has_parameter($this->get_html_id()))
		{
			$this->value = $request->get_value($this->get_html_id());
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/builder/form/field/FormField#get_html_id()
	 */
	public function get_html_id()
	{
		return 'form_builder_' . $this->get_id();
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/builder/form/ValidableFormComponent#add_constraint($constraint)
	 */
	public function add_constraint(FormFieldConstraint $constraint)
	{
		$this->constraints[] = $constraint;
	}


	public function get_onsubmit_validations()
	{
		$validations = array();
		foreach ($this->constraints as $constraint)
		{
			$validation = $constraint->get_onsubmit_validation($this);
			if (!empty($validation))
			{
				$validations[] =  $validation;
			}
		}
		return $validations;
	}

	protected function get_onblur_validations()
	{
		$validations = array();
		foreach ($this->constraints as $constraint)
		{
			$validation = $constraint->get_onblur_validation($this);
			if (!empty($validation))
			{
				$validations[] =  $validation;
			}
		}
		return $validations;
	}

	protected function compute_options(array $field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'title':
					$this->set_value($value);
					unset($field_options['title']);
					break;
				case 'subtitle':
					$this->set_description($value);
					unset($field_options['subtitle']);
					break;
				case 'id':
					$this->set_id($value);
					unset($field_options['id']);
					break;
				case 'class':
					$this->css_class = $value;
					unset($field_options['class']);
					break;
				case 'required':
					$this->set_required($value);
					$this->constraints[] = new NotEmptyFormFieldConstraint($value);
					unset($field_options['required']);
					break;
				case 'onblur':
					$this->maxlength = $value;
					unset($field_options['onblur']);
					break;
			}
		}
	}
}

?>