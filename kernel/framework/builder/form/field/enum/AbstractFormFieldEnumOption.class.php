<?php
/*##################################################
 *                    AbstractFormFieldEnumOption.class.php
 *                            -------------------
 *   begin                : January 10, 2010
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
 * 
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 * @package {@package}
 */
abstract class AbstractFormFieldEnumOption implements FormFieldEnumOption
{
	private $label = '';

	private $raw_value = '';
	private $active;
	private $disable = false;
	
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
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'disable':
					$this->set_disable($value);
					unset($field_choice_options['disable']);
					break;
				default :
					throw new FormBuilderException('The class ' . get_class($this) . ' hasn\'t the ' . $attribute . ' attribute');
			}
		}
	}
}

?>