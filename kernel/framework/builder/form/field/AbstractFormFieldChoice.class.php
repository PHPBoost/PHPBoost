<?php
/**
 * This class manage radio input fields.
 *
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 2.0 - 2009 04 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

abstract class AbstractFormFieldChoice extends AbstractFormField
{
	/**
	 * @var FormFieldEnumOption[]
	 */
	private $options = array();

	/**
	 * Constructs a FormFieldChoice.
	 * @param string $id Field id
	 * @param string $label Field label
	 * @param FormFieldEnumOption Default value
	 * @param FormFieldEnumOption[] $options Enumeration of the possible values
	 * @param string[] $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
	 * @param FormFieldConstraint List of the constraints
	 */
	public function __construct($id, $label, $value, array $options, array $field_options = array(), array $constraints = array())
	{
		foreach ($options as $option)
		{
			$this->add_option($option);
		}
		parent::__construct($id, $label, $value, $field_options, $constraints);
		$this->set_value($value);
	}

	/**
	 * @return FormFieldEnumOption[]
	 */
	protected function get_options()
	{
		return $this->options;
	}

	/**
	 * Adds an option for the field.
	 * @param FormFieldEnumOption option The new option.
	 */
	protected function add_option(FormFieldEnumOption $option)
	{
		$option->set_field($this);
		$this->options[] = $option;
	}

	/**
	 * Sets the options of the field.
	 * @param Array options The list of options.
	 */
	public function set_options(Array $options)
	{
		$this->clear_options();
		foreach ($options as $option)
		{
			if ($option instanceof FormFieldEnumOption)
			{
				$this->add_option($option);
			}
		}
	}

	protected function clear_options()
	{
		$this->options = array();
	}

	/**
	 * {@inheritdoc}
	 */
	public function retrieve_value()
	{
		$request = AppContext::get_request();
		if ($request->has_parameter($this->get_html_id()))
		{
			$raw_value = $request->get_value($this->get_html_id());
			$option = $this->get_option($raw_value);
			if ($option !== null)
			{
				$this->set_value($option);
			}
		}
	}

	protected function get_option($raw_option)
	{
		foreach ($this->options as $option)
		{
			if ($option->get_raw_value() == $raw_option)
			{
				return $option;
			}
		}
		return null;
	}

	public function get_option_id($raw_option)
	{
		foreach ($this->options as $id => $option)
		{
			if ($option->get_raw_value() == $raw_option)
			{
				return $id;
			}
		}
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_value($value)
	{
		if (is_object($value))
		{
			parent::set_value($value);
		}
		else
		{
			parent::set_value($this->get_option($value));
		}
	}
}
?>
