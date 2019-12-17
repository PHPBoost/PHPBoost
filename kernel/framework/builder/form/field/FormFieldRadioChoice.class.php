<?php
/**
 * This class manage radio input fields.
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 05 26
 * @since       PHPBoost 2.0 - 2009 04 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class FormFieldRadioChoice extends AbstractFormFieldChoice
{
	/**
	 * Constructs a FormFieldRadioChoice.
	 * @param string $id Field id
	 * @param string $label Field label
	 * @param mixed $value Default value (either a FormFieldRadioChoiceOption object or a string corresponding to the FormFieldRadioChoiceOption raw value)
	 * @param FormFieldRadioChoiceOption[] $options Enumeration of the possible values
	 * @param string[] $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
	 * @param FormFieldConstraint List of the constraints
	 */
	public function __construct($id, $label, $value, $options, array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $options, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-radio-button');
	}

	/**
	 * @return string The html code for the radio input.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$this->assign_common_template_variables($template);

		$has_value = false;
		foreach ($this->get_options() as $option)
		{
			$template->assign_block_vars('fieldelements', array(
				'ELEMENT' => $option->display()->render(),
			));
			if ($option->is_active())
				$has_value = true;
		}

		$template->put_all(array(
			'C_HIDE_FOR_ATTRIBUTE' => true,
			'C_REQUIRED_AND_HAS_VALUE' => $this->is_required() && $has_value
		));

		return $template;
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}

	protected function get_js_specialization_code()
	{
		return 'field.getValue = function()
		{
			return (jQuery("input[name='. $this->get_html_id() .']:checked").length > 0);
		}
		' . ($this->is_required() ? '
		jQuery("#'. $this->get_html_id() .'_field input[type=radio]").on("click", function() {
			HTMLForms.get("' . $this->get_form_id() . '").getField("'. $this->get_id() . '").enableValidationMessage();
			HTMLForms.get("' . $this->get_form_id() . '").getField("'. $this->get_id() . '").liveValidate();
		});' : '');
	}
}
?>
