<?php
/**
 * Abstract class that proposes a default implementation for the FormField interface.
 *
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 04
 * @since       PHPBoost 3.0 - 2010 01 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor xela <xela@phpboost.com>
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
	private $form_id = '';
	/**
	 * @var string
	 */
	private $fieldset_id = '';
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
	 * @var boolean
	 */
	protected $disabled = false;
	/**
	 * @var boolean
	 */
	protected $select_to_list = false;
	/**
	 * @var boolean
	 */
	protected $multiple_select_to_list = false;
	/**
	 * @var boolean
	 */
	protected $hidden = false;
	/**
	 * @var boolean
	 */
	protected $readonly = false;
	/**
	 * @var string
	 */
	protected $css_class = '';
	/**
	 * @var string
	 */
	protected $css_field_class = '';
	/**
	 * @var string
	 */
	protected $css_form_field_class = '';
	/**
	 * @var string
	 */
	protected $required = false;
	/**
	 * @var string
	 */
	protected $pattern = '';
	/**
	 * @var string
	 */
	protected $placeholder = '';
	/**
	 * @var string
	 */
	protected $validation_error_message = '';
	/**
	 * @var FormFieldConstraint[]
	 */
	protected $constraints = array();
	/**
	 * @var FormConstraint[]
	 */
	protected $form_constraints = array();
	/**
	 * @var Template
	 */
	protected $template = null;

	/**
	 * @var string[string]
	 */
	protected $events = array();

	/**
	 * Constructs and set parameters to the field.
	 * The specific parameters of this abstract class (common with many fields) are the following:
	 * <ul>
	 * 	<li>description at which you must associate the description of the field</li>
	 * 	<li>class which corresponds to the HTML class of the field</li>
	 * 	<li>required which tells whether this field must be completed. If it's the case, it will display a * beside the label and will add a non empty constraint to the constraints.</li>
	 * </ul>
	 * None of these parameters is required.
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param mixed $value Field value
	 * @param string[] $field_options Map associating the parameters values to the parameters names.
	 * @param FormFieldConstraint[] $constraints List of the constraints which must be checked when the form is been validated
	 */
	protected function __construct($id, $label, $value, array $field_options = array(), array $constraints = array())
	{
		$this->set_id($id);
		$this->set_label($label);
		$this->set_value($value);
		$this->compute_options($field_options);
		$this->set_css_field_class($this->get_css_class());

		foreach ($constraints as $constraint)
		{
			if (!empty($constraint))
			{
				$this->add_constraint($constraint);
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_id()
	{
		return $this->id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_id($id)
	{
		$this->id = $id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_form_id()
	{
		return $this->form_id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_form_id($form_id)
	{
		$this->form_id = $form_id;
	}

	public function set_fieldset_id($fieldset_id)
	{
		$this->fieldset_id = $fieldset_id;
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
	 * {@inheritdoc}
	 */
	public function get_value()
	{
		return $this->value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_value($value)
	{
		$this->value = $value;
	}

	/**
	 * Tells whether the field is required
	 * @return true if it is, false otherwise
	 */
	public function is_required()
	{
		return $this->required;
	}

	/**
	 * Changes the fact that the field is required or not.
	 * @param bool $required true if it's required, false otherwise
	 */
	public function set_required($required)
	{
		$this->required = $required;
	}

	/**
	 * {@inheritdoc}
	 */
	public function validate()
	{
		if ($this->is_disabled())
		{
			return true;
		}
		$this->retrieve_value();
		foreach ($this->constraints as $constraint)
		{
			if (!$constraint->validate($this))
			{
				$validation_error_message = $constraint->get_validation_error_message();
				if (!empty($validation_error_message))
				{
					$this->validation_error_message = $this->get_label() . ' : ' . $validation_error_message;
				}
				return false;
			}
		}
		foreach ($this->form_constraints as $constraint)
		{
			if (!$constraint->validate($this))
			{
				$validation_error_message = $constraint->get_validation_error_message();
				if (!empty($validation_error_message))
				{
					$this->validation_error_message = $this->get_label() . ' : ' . $validation_error_message;
				}
				return false;
			}
		}
		return true;
	}

	public function get_validation_error_message()
	{
		return $this->validation_error_message;
	}

	public function set_validation_error_message($error_message)
	{
		$this->validation_error_message = $error_message;
	}

	/**
	 * {@inheritdoc}
	 */
	public function retrieve_value()
	{
		$request = AppContext::get_request();
		if ($request->has_parameter($this->get_html_id()))
		{
			$this->set_value($request->get_string($this->get_html_id()));
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_html_id()
	{
		return $this->form_id . '_' . $this->get_id();
	}

	/**
	 * {@inheritdoc}
	 */
	public function add_constraint(FormFieldConstraint $constraint)
	{
		$this->constraints[] = $constraint;
	}

	public function add_form_constraint(FormConstraint $constraint)
	{
		$this->form_constraints[] = $constraint;
	}

	/**
	 * {@inheritdoc}
	 */
	public function has_constraints()
	{
		return (count($this->constraints) > 0 || count($this->form_constraints) > 0);
	}

	public function get_js_validations()
	{
		$validations = array();
		foreach ($this->constraints as $constraint)
		{
			$validation = $constraint->get_js_validation($this);
			if (!empty($validation))
			{
				$validations[] = $validation;
			}
		}
		foreach ($this->form_constraints as $constraint)
		{
			$validation = $constraint->get_js_validation();
			if (!empty($validation))
			{
				$validations[] = $validation;
			}
		}
		return $validations;
	}

	public function get_onblur_validation()
	{
		return 'formFieldConstraintsOnblurValidation(this, Array(' .
		implode(",", $this->get_js_validations()) . '));';
	}

	public function add_event($event, $handler)
	{
		$this->events[$event] = $handler;
	}

	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'description':
					$this->set_description($value);
					unset($field_options['description']);
					break;
				case 'disabled':
					$this->set_disabled($value);
					unset($field_options['disabled']);
					break;
				case 'select_to_list':
					$this->set_select_to_list($value);
					unset($field_options['select_to_list']);
					break;
				case 'multiple_select_to_list':
					$this->set_multiple_select_to_list($value);
					unset($field_options['multiple_select_to_list']);
					break;
				case 'readonly':
					$this->set_readonly($value);
					unset($field_options['readonly']);
					break;
				case 'class':
					$this->set_css_class($value);
					unset($field_options['class']);
					break;
				case 'field_class':
					$this->set_css_field_class($value);
					unset($field_options['field_class']);
					break;
				case 'form_field_class':
					$this->set_css_form_field_class($value);
					unset($field_options['form_field_class']);
					break;
				case 'events':
					$this->events = $value;
					unset($field_options['events']);
					break;
				case 'hidden':
					$this->set_hidden($value);
					unset($field_options['hidden']);
					break;
				case 'pattern':
					$this->set_pattern($value);
					unset($field_options['pattern']);
					break;
				case 'placeholder':
					$this->set_placeholder($value);
					unset($field_options['placeholder']);
					break;
				case 'required':
					if (is_string($value))
					{
						$this->set_required(true);
						$this->add_constraint(new FormFieldConstraintNotEmpty($value, $value));
					}
					elseif (is_bool($value) && $value === true)
					{
						$this->set_required(true);
						!strpos(get_called_class(), 'PossibleValues') ? $this->add_constraint(new FormFieldConstraintNotEmpty()) : '';
					}
					unset($field_options['required']);
					break;
				default :
					throw new FormBuilderException('The class ' . get_class($this) . ' hasn\'t the ' . $attribute . ' attribute');
			}
		}
	}

	protected function assign_common_template_variables(Template $template)
	{
		$has_js_validations = false;
		$js_tpl = new FileTemplate('framework/builder/form/AddFieldJS.tpl');

		foreach ($this->get_related_fields() as $field)
		{
			$js_tpl->assign_block_vars('related_field', array(
				'ID' => $field
			));
			$has_js_validations = true;
		}

		foreach ($this->events as $event => $handler)
		{
			$js_tpl->assign_block_vars('event_handler', array(
				'EVENT' => $event,
				'HANDLER' => $handler
			));
			$has_js_validations = true;
		}

		foreach ($this->get_js_validations() as $constraint)
		{
			$js_tpl->assign_block_vars('constraint', array(
				'CONSTRAINT' => $constraint
			));
			$has_js_validations = true;
		}

		$js_tpl->put_all(array(
			'C_DISABLED' => $this->is_disabled(),
			'C_HAS_CONSTRAINTS' => $this->has_constraints(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'JS_SPECIALIZATION_CODE' => $this->get_js_specialization_code(),
			'FORM_ID' => $this->form_id,
			'FIELDSET_ID' => $this->fieldset_id
		));

		$template->put('ADD_FIELD_JS', $js_tpl);

		$description = $this->get_description();
		$template->put_all(array(
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'NAME' => $this->get_html_id(),
			'LABEL' => $this->get_label(),
			'DESCRIPTION' => $description,
			'C_DESCRIPTION' => !empty($description),
			'C_REQUIRED' => $this->is_required(),
			'C_REQUIRED_AND_HAS_VALUE' => $this->is_required() && (!empty($this->value) || $this->value == '0'),
			'VALUE' => $this->get_value(),
			'C_HAS_CONSTRAINTS' => $this->has_constraints(),
			'CSS_CLASS' => $this->get_css_class(),
			'C_HAS_CSS_CLASS' => $this->get_css_class() != '',
			'FIELD_CLASS' => $this->get_css_field_class(),
			'C_HAS_FIELD_CLASS' => $this->get_css_field_class() != '',
			'FORM_FIELD_CLASS' => $this->get_css_form_field_class(),
			'C_HAS_FORM_FIELD_CLASS' => $this->get_css_form_field_class() != '',
			'FORM_ID' => $this->form_id,
			'FIELDSET_ID' => $this->fieldset_id,
			'C_HAS_LABEL' => !empty($description) || $this->get_label() != '',
			'C_DISABLED' => $this->is_disabled(),
			'C_SELECT_TO_LIST' => $this->is_select_to_list(),
			'C_MULTIPLE_SELECT_TO_LIST' => $this->is_multiple_select_to_list(),
			'C_READONLY' => $this->is_readonly(),
			'C_HIDDEN' => $this->is_hidden(),
			'C_PATTERN' => $this->has_pattern(),
			'PATTERN' => $this->pattern,
			'C_PLACEHOLDER' => $this->has_placeholder(),
			'PLACEHOLDER' => $this->placeholder
		));
	}

	private function get_related_fields()
	{
		$related_fields = array();
		foreach ($this->form_constraints as $constraint)
		{
			foreach ($constraint->get_related_fields() as $field)
			{
				if ($field->get_id() != $this->get_id() && !in_array($field->get_id(), $related_fields))
				{
					$related_fields[] = $field->get_id();
				}
			}
		}
		return $related_fields;
	}

	protected function get_css_class()
	{
		return $this->css_class;
	}

	protected function set_css_class($css_class)
	{
		$this->css_class = $css_class;
	}

	protected function get_css_field_class()
	{
		return $this->css_field_class;
	}


	protected function set_css_field_class($css_field_class)
	{
		$this->css_field_class = $css_field_class;
	}

	protected function get_css_form_field_class()
	{
		return $this->css_form_field_class;
	}


	protected function set_css_form_field_class($css_form_field_class)
	{
		$this->css_form_field_class = $css_form_field_class;
	}

	public function is_disabled()
	{
		return $this->disabled;
	}

	public function disable()
	{
		$this->set_disabled(true);
	}

	public function enable()
	{
		$this->set_disabled(false);
	}

	protected function set_disabled($disabled)
	{
		$this->disabled = $disabled;
	}

	public function is_select_to_list()
	{
		return $this->select_to_list;
	}

	public function transformed_select()
	{
		$this->set_select_to_list(true);
	}

	public function not_transformed_select()
	{
		$this->set_select_to_list(false);
	}

	protected function set_select_to_list($select_to_list)
	{
		$this->select_to_list = $select_to_list;
	}

	public function is_multiple_select_to_list()
	{
		return $this->multiple_select_to_list;
	}

	public function transformed_multiple_select()
	{
		$this->set_multiple_select_to_list(true);
	}

	public function not_transformed_multiple_select()
	{
		$this->set_multiple_select_to_list(false);
	}

	protected function set_multiple_select_to_list($multiple_select_to_list)
	{
		$this->multiple_select_to_list = $multiple_select_to_list;
	}

	public function is_readonly()
	{
		return $this->readonly;
	}

	public function set_readonly($readonly)
	{
		$this->readonly = $readonly;
	}

	public function is_hidden()
	{
		return $this->hidden;
	}

	public function set_hidden($hidden)
	{
		$this->hidden = $hidden;
	}

	public function has_pattern()
	{
		return $this->pattern;
	}

	public function set_pattern($pattern)
	{
		$this->pattern = $pattern;
	}

	public function has_placeholder()
	{
		return $this->placeholder;
	}

	public function set_placeholder($placeholder)
	{
		$this->placeholder = $placeholder;
	}

	public function set_template(Template $template)
	{
		$this->template = $template;
	}

	/**
	 * @return Template
	 */
	protected function get_template_to_use()
	{
		if ($this->template !== null)
		{
			return $this->template;
		}
		else
		{
			return $this->get_default_template();
		}
	}

	/**
	 * @return Template
	 */
	abstract protected function get_default_template();

	protected function get_js_specialization_code()
	{
		return '';
	}
}
?>
