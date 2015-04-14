<?php
/*##################################################
 *                         HTMLTableEqualsFromListFilter.class.php
 *                            -------------------
 *   begin                : February 25, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc
 * @package {@package}
 */
abstract class HTMLTableEqualsFromListFilter extends AbstractHTMLTableFilter
{
	private $allowed_values;
	private $options;

	public function __construct($name, $label, array $allowed_values)
	{
		$this->allowed_values = array_keys($allowed_values);
		$default_value = new FormFieldSelectChoiceOption(LangLoader::get_message('all', 'common'), '');
		$this->options = array($default_value);
		foreach ($allowed_values as $option_value => $option_label)
		{
			$this->options[] = new FormFieldSelectChoiceOption($option_label, $option_value);
		}
		$select = new FormFieldSimpleSelectChoice($name, $label, $default_value, array_values($this->options));
		parent::__construct($name, $select);
	}

	public function is_value_allowed($value)
	{
		if (in_array($value, $this->allowed_values))
		{
			$this->set_value($value);
			return true;
		}
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function set_value($value)
	{
		foreach ($this->options as $option)
		{
			if ($option->get_raw_value() === $value)
			{
				parent::set_value($option);
				break;
			}
		}
	}
}

?>