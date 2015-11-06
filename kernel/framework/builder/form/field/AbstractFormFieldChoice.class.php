<?php
/*##################################################
 *                       FormFieldRadioChoice.class.php
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
 * @desc This class manage radio input fields.
 * @package {@package}
 */
abstract class AbstractFormFieldChoice extends AbstractFormField
{
	/**
	 * @var FormFieldEnumOption[]
	 */
	private $options = array();

	/**
	 * @desc Constructs a FormFieldChoice.
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
	 * @desc Adds an option for the field.
	 * @param FormFieldEnumOption option The new option.
	 */
	protected function add_option(FormFieldEnumOption $option)
	{
		$option->set_field($this);
		$this->options[] = $option;
	}

	/**
	 * @desc Sets the options of the field.
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