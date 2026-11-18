<?php
/**
 * This class manage select fields.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>multiple : Type of select field, mutiple allow you to check several options.</li>
 * </ul>
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 2.0 - 2009 04 28
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldSimpleSelectChoice extends AbstractFormFieldChoice
{
	private mixed $default_value = null;

	/**
	 * Constructs a FormFieldSimpleSelectChoice.
	 * @param string $id Field id
	 * @param string $label Field label
	 * @param mixed $value Default value (either a FormFieldEnumOption object or a string corresponding to the FormFieldEnumOption's raw value)
	 * @param FormFieldEnumOption[] $options Enumeration of the possible values
	 * @param array $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
	 * @param FormFieldConstraint List of the constraints
	 */
	public function __construct(string $id, string $label, mixed $value, array $options, array $field_options = [], array $constraints = [])
	{
		$this->default_value = $value;
		parent::__construct($id, $label, $value, $options, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-select');
	}

	public function display(): Template
	{
		$template = $this->get_template_to_use();

		$this->assign_common_template_variables($template);

		$template->assign_block_vars('fieldelements', [
			'ELEMENT' => $this->get_html_code()->render(),
		]);

		return $template;
	}

	private function get_html_code(): Template
	{
		$tpl = new FileTemplate('framework/builder/form/fieldelements/FormFieldSimpleSelectChoice.tpl');

		$tpl->put_all([
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'CSS_CLASS' => $this->get_css_class(),
			'C_DISABLED' => $this->is_disabled(),
			'C_SELECT_TO_LIST' => $this->is_select_to_list()
		]);

		foreach ($this->get_options() as $option)
		{
			$tpl->assign_block_vars('options', [], [
				'OPTION' => $option->display()
			]);
		}

		return $tpl;
	}

	protected function get_option(mixed $raw_value): mixed
	{
		foreach ($this->get_options() as $option)
		{
			$result = $option->get_option($raw_value);
			if ($result !== null)
			{
				return $result;
			}
		}
		return null;
	}

	protected function assign_common_template_variables(Template $template): void
	{
		parent::assign_common_template_variables($template);
		$template->put('C_REQUIRED_AND_HAS_VALUE', $this->is_required() && $this->default_value);
	}

	protected function get_default_template(): FileTemplate
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}

	protected function get_js_specialization_code(): string
	{
		return ($this->is_required() ? '
		jQuery("#'. $this->get_html_id() .'_field").change(function() {
			HTMLForms.get("' . $this->get_form_id() . '").getField("'. $this->get_id() . '").enableValidationMessage();
			HTMLForms.get("' . $this->get_form_id() . '").getField("'. $this->get_id() . '").liveValidate();
		});' : '');
	}
}
?>
