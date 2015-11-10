<?php
/*##################################################
 *                      FormFieldMultipleCheckbox.class.php
 *                            -------------------
 *   begin                : November 20, 2010
 *   copyright            : (C) 2010 Sautel Benoit
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
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 * @desc This class represents a field which contains several options that can be selected simultaneously.
 * @package {@package}
 */
class FormFieldMultipleCheckbox extends AbstractFormField
{
	private $available_options;
	
	/**
	 * @desc Constructs a FormFieldCheckbox.
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param FormFieldMultipleCheckboxOption[] $selected_options The selected options (can also be an array of string where strings are identifiers of selected options)
	 * @param FormFieldMultipleCheckboxOption[] $available_options All the options managed by the field
	 * @param string[] $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct($id, $label, array $selected_options, array $available_options, array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, null, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-multiple-checkbox');
		$this->available_options = $available_options;
		$this->set_selected_options($selected_options);
	}
	
	private function set_selected_options(array $selected_options)
	{
		$value = array();
		foreach ($selected_options as $option)
		{
			if (is_string($option))
			{
				$value[] = $this->get_option($option);
			}
			else if ($option instanceof FormFieldMultipleCheckboxOption)
			{
				$value[] = $option;
			}
			else
			{
				throw new FormBuilderException('option ' . $option . ' isn\'t recognized');
			}
		}
		$this->set_value($value);
	}
	
	private function get_option($identifier)
	{
		foreach ($this->available_options as $option)
		{
			if ($option->get_id() == $identifier || $option->get_label() == $identifier)
			{
				return $option;
			}
		}
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$this->assign_common_template_variables($template);

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $this->generate_html_code()->render()
		));
		
		$template->put('C_HIDE_FOR_ATTRIBUTE', true);

		return $template;
	}

	/**
	 * {@inheritdoc}
	 */
	public function retrieve_value()
	{
		$request = AppContext::get_request();
		$value = array();
		foreach ($this->available_options as $option)
		{
			$option_id = $this->get_option_id($option);
			if ($request->has_parameter($option_id))
			{
				if ($request->get_value($option_id) == 'on')
				{
					$value[] = $option;
				}
			}
		}
		$this->set_value($value);
	}

	/**
	 * @return Template
	 */
	private function generate_html_code()
	{
		$tpl_src = '# START choice #
		<div class="form-field-checkbox">
			<input type="checkbox" name="${escape(choice.HTML_ID)}" id="${escape(choice.HTML_ID)}" # IF choice.C_CHECKED # checked="checked"# ENDIF # />
			<label for="${escape(choice.HTML_ID)}"></label>
		</div>
		${escape(choice.NAME)}
		<div class="spacer"></div>
		# END choice #';
		
		$rows = array();
		foreach ($this->available_options as $option)
		{
			$rows[] = array(
				'NAME' => $option->get_label(),
				'HTML_ID' => $this->get_option_id($option),
				'C_CHECKED' => $this->is_selected($option)
			);
		}
		
		$tpl = new StringTemplate($tpl_src);
		$tpl->put_all(array('choice' => $rows));

		return $tpl;
	}
	
	private function get_option_id(FormFieldMultipleCheckboxOption $option)
	{
		return $this->get_html_id() . '_' . $option->get_id();
	}
	
	private function is_selected(FormFieldMultipleCheckboxOption $option)
	{
		return in_array($option, $this->get_value()); 
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}

	protected function get_js_specialization_code()
	{
		return 'field.getValue = function()
		{
			return (jQuery("#'. $this->get_html_id() .'_field input[type=checkbox]:checked").length > 0);
		}
		' . ($this->is_required() ? '
		jQuery("#'. $this->get_html_id() .'_field input[type=checkbox]").click(function() {
			HTMLForms.get("' . $this->get_form_id() . '").getField("'. $this->get_id() . '").enableValidationMessage();
			HTMLForms.get("' . $this->get_form_id() . '").getField("'. $this->get_id() . '").liveValidate();
		});' : '');
	}
	
	public function get_available_options()
	{
		return $this->available_options;
	}
}
?>
