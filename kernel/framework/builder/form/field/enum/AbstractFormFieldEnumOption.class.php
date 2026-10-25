<?php
/**
 * @package     Builder
 * @subpackage  Form\field\enum
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 25
 * @since       PHPBoost 3.0 - 2010 01 10
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractFormFieldEnumOption implements FormFieldEnumOption
{
	private string $label = '';
	private mixed $raw_value = null;
	private ?bool $active = false;
	private bool $disable = false;
	private string $data_option_img = '';
	private string $data_option_icon = '';
	private string $data_option_class = '';
	private FormField $field;

	public function __construct(string $label, mixed $raw_value, array $field_choice_options = [])
	{
		$this->set_label($label);
		$this->set_raw_value($raw_value);
		$this->compute_options($field_choice_options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_label(): string
	{
		return $this->label;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_label(string $label): void
	{
		$this->label = $label;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_raw_value(): string
	{
		return $this->raw_value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_raw_value(string $value): void
	{
		$this->raw_value = $value;
	}

	public function get_field(): FormField
	{
		return $this->field;
	}

	public function set_field(FormField $field): void
	{
		$this->field = $field;
	}

	public function set_active(bool $value = true): void
	{
		$this->active = $value;
	}

	public function is_active(): mixed
	{
		if (isset($this->active))
		{
			return $this->active;
		}
		else
		{
			return $this->get_field()->get_value() === $this;
		}
	}

	public function set_disable(bool $value = false): void
	{
		$this->disable = $value;
	}

	protected function is_disable(): bool
	{
		return $this->disable;
	}

	public function set_data_option_img(string $value): void
	{
		$this->data_option_img = $value;
	}

	protected function get_data_option_img(): string
	{
		return $this->data_option_img;
	}

	public function set_data_option_icon(string $value): void
	{
		$this->data_option_icon = $value;
	}

	protected function get_data_option_icon(): string
	{
		return $this->data_option_icon;
	}

	public function set_data_option_class(string $value) :void
	{
		$this->data_option_class = $value;
	}

	protected function get_data_option_class(): string
	{
		return $this->data_option_class;
	}

	protected function get_field_id(): string
	{
		return $this->get_field()->get_html_id();
	}

	protected function get_option_id(): mixed
	{
		$field = $this->get_field();
		if (!($field instanceof AbstractFormFieldChoice))
		{
			throw new FormBuilderException('The option field must be an instance of AbstractFormFieldChoice');
		}

		return $field->get_html_id() . $field->get_option_id($this->raw_value);
	}

	public function get_option(mixed $raw_value): mixed
	{
		if ($this->get_raw_value() == $raw_value)
		{
			return $this;
		}
		else
		{
			return null;
		}
	}

	protected function compute_options(array &$field_choice_options): void
	{
		foreach($field_choice_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'disable':
					$this->set_disable($value);
					unset($field_choice_options['disable']);
					break;
				case 'selected':
					$this->set_active($value);
					unset($field_choice_options['selected']);
					break;
				case 'data_option_img':
					$this->set_data_option_img($value);
					unset($field_choice_options['data_option_img']);
					break;
				case 'data_option_icon':
					$this->set_data_option_icon($value);
					unset($field_choice_options['data_option_icon']);
					break;
				case 'data_option_class':
					$this->set_data_option_class($value);
					unset($field_choice_options['data_option_class']);
					break;
				default :
					throw new FormBuilderException('The class ' . get_class($this) . ' hasn\'t the ' . $attribute . ' attribute');
			}
		}
	}
}

?>
