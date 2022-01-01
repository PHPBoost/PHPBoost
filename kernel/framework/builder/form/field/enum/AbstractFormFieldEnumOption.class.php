<?php
/**
 * @package     Builder
 * @subpackage  Form\field\enum
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 10 13
 * @since       PHPBoost 3.0 - 2010 01 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractFormFieldEnumOption implements FormFieldEnumOption
{
	private $label = '';

	private $raw_value = '';
	private $active;
	private $disable = false;
	private $data_option_img = '';
	private $data_option_icon = '';
	private $data_option_class = '';

	/**
	 * @var FormField
	 */
	private $field;

	public function __construct($label, $raw_value, $field_choice_options = array())
	{
		$this->set_label($label);
		$this->set_raw_value($raw_value);
		$this->compute_options($field_choice_options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_label()
	{
		return $this->label;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_label($label)
	{
		$this->label = $label;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_raw_value()
	{
		return $this->raw_value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_raw_value($value)
	{
		$this->raw_value = $value;
	}

	/**
	 * @return FormField
	 */
	public function get_field()
	{
		return $this->field;
	}

	public function set_field(FormField $field)
	{
		$this->field = $field;
	}

	public function set_active($value = true)
	{
		$this->active = $value;
	}

	public function is_active()
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

	public function set_disable($value = false)
	{
		$this->disable = $value;
	}

	protected function is_disable()
	{
		return $this->disable;
	}

	public function set_data_option_img($value)
	{
		$this->data_option_img = $value;
	}

	protected function get_data_option_img()
	{
		return $this->data_option_img;
	}

	public function set_data_option_icon($value)
	{
		$this->data_option_icon = $value;
	}

	protected function get_data_option_icon()
	{
		return $this->data_option_icon;
	}

	public function set_data_option_class($value)
	{
		$this->data_option_class = $value;
	}

	protected function get_data_option_class()
	{
		return $this->data_option_class;
	}

	protected function get_field_id()
	{
		return $this->get_field()->get_html_id();
	}

	protected function get_option_id()
	{
		return $this->get_field()->get_html_id() . $this->get_field()->get_option_id($this->raw_value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_option($raw_value)
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

	protected function compute_options(array &$field_choice_options)
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
