<?php
/*##################################################
 *                      FormFieldSelectGroupOption.class.php
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
 * @desc This class manage select field options.
 * @package {@package}
 */
class FormFieldSelectChoiceGroupOption extends AbstractFormFieldEnumOption
{
	private $options = array();

	/**
	 * @param string $label string The label
	 * @param FormFieldSelectChoiceOption[] $options The group's options
	 */
	public function __construct($label, array $options)
	{
		parent::__construct($label, '');
		$this->set_options($options);
	}

	public function set_field(FormField $field)
	{
		parent::set_field($field);
		foreach ($this->options as $option)
		{
			$option->set_field($field);
		}
	}

	public function display()
	{
		$tpl_src = '<optgroup label="${escape(LABEL)}"> # START options # # INCLUDE options.OPTION # # END options # </optgroup>';

		$tpl = new StringTemplate($tpl_src);
		$tpl->put_all(array(
			'LABEL' => $this->get_label()
		));

		foreach ($this->options as $option)
		{
			$tpl->assign_block_vars('options', array(), array(
				'OPTION' => $option->display()
			));
		}

		return $tpl;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_option($raw_value)
	{
		foreach ($this->options as $option)
		{
			if ($option->get_option($raw_value))
			{
				return $option;
			}
		}
		return null;
	}
	
	public function set_options(Array $options)
	{
		$this->options = $options;
	}
	
	public function get_options()
	{
		return $this->options;
	}
}
?>