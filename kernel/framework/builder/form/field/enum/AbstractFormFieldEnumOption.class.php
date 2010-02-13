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

abstract class AbstractFormFieldEnumOption implements FormFieldEnumOption
{
	private $label = '';

	private $raw_value = '';

	/**
	 * @var FormField
	 */
	private $field;

	public function __construct($label, $raw_value)
	{
		$this->set_label($label);
		$this->set_raw_value($raw_value);
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

	protected function is_active()
	{
		return $this->get_field()->get_value() == $this;
	}

	protected function get_field_id()
	{
		return $this->get_field()->get_html_id();
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
}

?>