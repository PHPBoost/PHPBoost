<?php
/**
 * This class represents a field which contains several options that can be selected simultaneously.
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2017 03 10
 * @since       PHPBoost 3.0 - 2010 11 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class FormFieldMultipleCheckbox extends AbstractFormField
{
	private $available_options;

	/**
	 * Constructs a FormFieldCheckbox.
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

		$rows = array();
		foreach ($this->available_options as $option)
		{
			$rows[] = array(
				'NAME' => $option->get_label(),
				'HTML_ID' => $this->get_option_id($option),
				'C_CHECKED' => $this->is_selected($option)
			);
		}

		$tpl = new FileTemplate('framework/builder/form/fieldelements/FormFieldMultipleCheckbox.tpl');
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
		jQuery("#'. $this->get_html_id() .'_field input[type=checkbox]").on("click", function() {
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
