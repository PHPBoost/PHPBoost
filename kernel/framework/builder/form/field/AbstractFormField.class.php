<?php
/**
 * Abstract class that proposes a default implementation for the FormField interface.
 *
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 27
 * @since       PHPBoost 3.0 - 2010 01 08
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @author      xela <xela@phpboost.com>
*/

abstract class AbstractFormField implements FormField
{
	protected string $id = '';
	private string $form_id = '';
	private string $fieldset_id = '';
	protected string $label = '';
	protected string $description = '';
	protected mixed $value = null;
	protected bool $disabled = false;
	protected bool $select_to_list = false;
	protected bool $multiple_select_to_list = false;
	protected bool $hidden = false;
	protected bool $readonly = false;
	protected string $css_class = '';
	protected string $css_field_class = '';
	protected string $css_form_field_class = '';
	protected bool $required = false;
	protected string $pattern = '';
	protected string $placeholder = '';
	protected string $validation_error_message = '';
	protected array $constraints = [];
	protected array $form_constraints = [];
	protected ?Template $template = null;
	protected array $events = [];

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
	 * @param array $field_options Map associating the parameters values to the parameters names.
	 * @param FormFieldConstraint[] $constraints List of the constraints which must be checked when the form is been validated
	 */
	protected function __construct(string $id, string $label, mixed $value, array $field_options = [], array $constraints = [])
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

	public function get_id(): string
	{
		return $this->id;
	}

	public function set_id(string $id): void
	{
		$this->id = $id;
	}

	public function get_form_id(): string
	{
		return $this->form_id;
	}

	public function set_form_id(string $form_id): void
	{
		$this->form_id = $form_id;
	}

	public function set_fieldset_id(string $fieldset_id): void
	{
		$this->fieldset_id = $fieldset_id;
	}

	public function get_label(): string
	{
		return $this->label;
	}

	public function set_label(string $label): void
	{
		$this->label = $label;
	}

	public function get_description(): string
	{
		return $this->description;
	}

	public function set_description(mixed $description): void
	{
		$this->description = $description ?? '';
	}

	public function get_value(): mixed
	{
		return $this->value;
	}

	public function set_value(mixed $value): void
	{
		$this->value = $value;
	}

	public function is_required(): bool
	{
		return $this->required;
	}

	public function set_required(bool $required): void
	{
		$this->required = $required;
	}

	public function validate(): mixed
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

	public function get_validation_error_message(): string
	{
		return $this->validation_error_message;
	}

	public function set_validation_error_message(string $error_message): void
	{
		$this->validation_error_message = $error_message;
	}

	public function retrieve_value(): void
	{
		$request = AppContext::get_request();
		if ($request->has_parameter($this->get_html_id()))
		{
			$this->set_value($request->get_string($this->get_html_id()));
		}
	}

	public function get_html_id(): string
	{
		return $this->form_id . '_' . $this->get_id();
	}

	public function add_constraint(FormFieldConstraint $constraint): void
	{
		$this->constraints[] = $constraint;
	}

	public function add_form_constraint(FormConstraint $constraint): void
	{
		$this->form_constraints[] = $constraint;
	}

	public function has_constraints(): bool
	{
		return (count($this->constraints) > 0 || count($this->form_constraints) > 0);
	}

	public function get_js_validations(): array
	{
		$validations = [];
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

	public function get_onblur_validation(): string
	{
		return 'formFieldConstraintsOnblurValidation(this, Array(' .
		implode(",", $this->get_js_validations()) . '));';
	}

	public function add_event(mixed $event, mixed $handler): void
	{
		$this->events[$event] = $handler;
	}

	protected function compute_options(array &$field_options): void
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
						$this->add_constraint(new FormFieldConstraintNotEmpty($value));
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

	protected function assign_common_template_variables(Template $template): void
	{
		$has_js_validations = false;
		$js_tpl = new FileTemplate('framework/builder/form/AddFieldJS.tpl');

		foreach ($this->get_related_fields() as $field)
		{
			$js_tpl->assign_block_vars('related_field', [
				'ID' => $field
			]);
			$has_js_validations = true;
		}

		foreach ($this->events as $event => $handler)
		{
			$js_tpl->assign_block_vars('event_handler', [
				'EVENT' => $event,
				'HANDLER' => $handler
			]);
			$has_js_validations = true;
		}

		foreach ($this->get_js_validations() as $constraint)
		{
			$js_tpl->assign_block_vars('constraint', [
				'CONSTRAINT' => $constraint
			]);
			$has_js_validations = true;
		}

		$js_tpl->put_all([
			'C_DISABLED' => $this->is_disabled(),
			'C_HAS_CONSTRAINTS' => $this->has_constraints(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'JS_SPECIALIZATION_CODE' => $this->get_js_specialization_code(),
			'FORM_ID' => $this->form_id,
			'FIELDSET_ID' => $this->fieldset_id
		]);

		$template->put('ADD_FIELD_JS', $js_tpl);

		$description = $this->get_description();
		$template->put_all([
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
		]);
	}

	private function get_related_fields(): array
	{
		$related_fields = [];
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

	protected function get_css_class(): string
	{
		return $this->css_class;
	}

	protected function set_css_class(string $css_class): void
	{
		$this->css_class = $css_class;
	}

	protected function get_css_field_class(): string
	{
		return $this->css_field_class;
	}


	protected function set_css_field_class(string $css_field_class): void
	{
		$this->css_field_class = $css_field_class;
	}

	protected function get_css_form_field_class(): string
	{
		return $this->css_form_field_class;
	}


	protected function set_css_form_field_class(string $css_form_field_class): void
	{
		$this->css_form_field_class = $css_form_field_class;
	}

	public function is_disabled(): bool
	{
		return $this->disabled;
	}

	public function disable(): void
	{
		$this->set_disabled(true);
	}

	public function enable(): void
	{
		$this->set_disabled(false);
	}

	protected function set_disabled(bool $disabled): void
	{
		$this->disabled = $disabled;
	}

	public function is_select_to_list(): bool
	{
		return $this->select_to_list;
	}

	public function transformed_select(): void
	{
		$this->set_select_to_list(true);
	}

	public function not_transformed_select(): void
	{
		$this->set_select_to_list(false);
	}

	protected function set_select_to_list(bool $select_to_list): void
	{
		$this->select_to_list = $select_to_list;
	}

	public function is_multiple_select_to_list(): bool
	{
		return $this->multiple_select_to_list;
	}

	public function transformed_multiple_select(): void
	{
		$this->set_multiple_select_to_list(true);
	}

	public function not_transformed_multiple_select(): void
	{
		$this->set_multiple_select_to_list(false);
	}

	protected function set_multiple_select_to_list(bool $multiple_select_to_list): void
	{
		$this->multiple_select_to_list = $multiple_select_to_list;
	}

	public function is_readonly(): bool
	{
		return $this->readonly;
	}

	public function set_readonly(bool $readonly): void
	{
		$this->readonly = $readonly;
	}

	public function is_hidden(): bool
	{
		return $this->hidden;
	}

	public function set_hidden(mixed $hidden): void
	{
		$this->hidden = $hidden;
	}

	public function has_pattern(): string
	{
		return $this->pattern;
	}

	public function set_pattern(string $pattern): void
	{
		$this->pattern = $pattern;
	}

	public function has_placeholder(): string
	{
		return $this->placeholder;
	}

	public function set_placeholder(string $placeholder): void
	{
		$this->placeholder = $placeholder;
	}

	public function set_template(Template $template): void
	{
		$this->template = $template;
	}

	protected function get_template_to_use(): Template
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

	abstract protected function get_default_template(): Template;

	protected function get_js_specialization_code()
	{
		return '';
	}
}
?>
