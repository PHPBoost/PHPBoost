<?php
/*##################################################
 *                             FormFieldMultipleSelectChoice.class.php
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
 * @desc This class manage select fields.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>multiple : Type of select field, mutiple allow you to check several options.</li>
 * </ul>
 * @package {@package}
 */
class FormFieldMultipleSelectChoice extends AbstractFormField
{
	private $selected_options;
	private $options = array();
	private $size = 4;
	
	/**
	 * @desc Constructs a FormFieldMultipleSelectChoice.
	 * @param string $id Field id
	 * @param string $label Field label
	 * @param mixed $value Default value (either a FormFieldEnumOption object or a string corresponding to the FormFieldEnumOption's raw value)
	 * @param FormFieldEnumOption[] $options Enumeration of the possible values
	 * @param string[] $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
	 * @param FormFieldConstraint List of the constraints
	 */
	public function __construct($id, $label, array $selected_options, array $available_options, array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $selected_options, $field_options, $constraints);

		$this->set_css_form_field_class('form-field-multi-select');

		foreach ($available_options as $option)
		{
			$this->add_option($option);
		}
		
		$this->set_selected_options($selected_options);
	}
	
	public function set_selected_options(array $selected_options)
	{
		$value = array();
		foreach ($selected_options as $option)
		{
			if (is_string($option))
			{
				$value[] = $this->get_option($option);
			}
			else if ($option instanceof FormFieldSelectChoiceOption)
			{
				$value[] = $option;
			}
			else
			{
				throw new FormBuilderException('option ' . $option . ' isn\'t recognized');
			}
		}
		$this->selected_options = $value;
	}
	
	public function retrieve_value()
	{
		$request = AppContext::get_request();

		if ($request->has_parameter($this->get_html_id()))
		{
			$selected_options = $request->get_array($this->get_html_id());
			
			$value = array();
			foreach ($this->get_options() as $option)
			{
				if (in_array($option->get_raw_value(), $selected_options))
				{ 
					$value[] = $option;
				}
			}
			$this->set_value($value);
		}
		else
		{
			$this->set_value(array());
		}
	}
	
	/**
	 * @return string The html code for the select.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$this->assign_common_template_variables($template);

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $this->get_html_code()->render(),
		));

		return $template;
	}

	private function get_html_code()
	{
		$tpl = new FileTemplate('framework/builder/form/FormFieldMultipleSelectChoice.tpl');

		$lang = LangLoader::get('main');
		$tpl->put_all(array(
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'FORM_ID' => $this->get_form_id(),
			'SIZE' => $this->size,
			'CSS_CLASS' => $this->get_css_class(),
			'C_DISABLED' => $this->is_disabled(),
			'C_REQUIRED' => $this->is_required(),
			'L_SELECT_ALL' => $lang['select_all'],
			'L_UNSELECT_ALL' => $lang['select_none'],
			'L_SELECT_EXPLAIN' => $lang['explain_select_multiple']
		));
		
		
		foreach ($this->get_options() as $multiple_select_option)
		{
			if ($multiple_select_option instanceof FormFieldSelectChoiceGroupOption)
			{
				foreach ($multiple_select_option->get_options() as $option)
				{
					$select = $this->is_selected($option);
					if ($select)
					{
						$option->set_active();
					}
				}
			}
			else
			{
				$select = $this->is_selected($multiple_select_option);
				if ($select)
				{
					$multiple_select_option->set_active();
				}
			}
			
			$tpl->assign_block_vars('options', array(
				'OPTION' => $multiple_select_option->display()
			));
		}
		return $tpl;
	}
	
	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'size':
					$this->size = $value;
					unset($field_options['size']);
					break;
			}
		}
		parent::compute_options($field_options);
	}
	
	private function is_selected($option)
	{
		return in_array($option, $this->selected_options);
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
	
	private function add_option(FormFieldEnumOption $option)
	{
		$option->set_field($this);
		$this->options[] = $option;
	}
	
	private function get_options()
	{
		return $this->options;
	}
	
	private function get_option($identifier)
	{
		foreach ($this->options as $option)
		{
			if ($option->get_raw_value() == $identifier || $option->get_label() == $identifier)
			{
				return $option;
			}
		}
		return null;
	}

	protected function get_js_specialization_code()
	{
		return 'field.getValue = function()
		{
			return jQuery("#'.$this->get_html_id().' :selected").length;
		}';
	}
}
?>