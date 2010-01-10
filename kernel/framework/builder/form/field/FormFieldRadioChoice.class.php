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
 * @package builder
 * @subpackage form
 */
class FormFieldRadioChoice extends AbstractFormField
{
	/**
	 * @var FormFieldRadioChoiceOption[]
	 */
	private $options = array();

	/**
	 * @desc Constructs a FormFieldRadio.
	 * @param string $id Field id
	 * @param string $label Field label
	 * @param FormFieldRadioChoiceOption Default value
	 * @param FormFieldRadioChoiceOption[] $options Enumeration of the possible values
	 * @param string[] $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
	 * @param FormFieldConstraint List of the constraints
	 */
	public function __construct($id, $label, $value, $options, array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
		foreach ($options as $option)
		{
			$this->add_option($option);
		}
	}

	/**
	 * @desc Adds an option for the radio field.
	 * @param FormFieldRadioChoiceOption option The new option.
	 */
	private function add_option(FormFieldRadioChoiceOption $option)
	{
		$option->set_field($this);
		$this->options[] = $option;
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/builder/form/field/AbstractFormField#retrieve_value()
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
	
	private function get_option($raw_option)
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

	/**
	 * @return string The html code for the radio input.
	 */
	public function display()
	{
		$template = new Template('framework/builder/form/FormField.tpl');

		$this->assign_common_template_variables($template);

		foreach ($this->options as $option)
		{
			$template->assign_block_vars('fieldelements', array(
				'ELEMENT' => $option->display(),
			));
		}

		return $template;
	}
}
?>