<?php
/**
 * This class manage  a mail address.
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 2.0 - 2009 04 28
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldMailEditor extends FormFieldTextEditor
{
	protected string $type = 'email';
	private bool $multiple = false;

	/**
	 * Constructs a FormFieldMailEditor.
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param mixed $value Default value
	 * @param array $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct(string $id, string $label, mixed $value, array $field_options = [], array $constraints = [])
	{
		if (isset($field_options['multiple']))
		{
			$simple_regex = AppContext::get_mail_service()->get_mail_checking_raw_regex();
			$constraints[] = new FormFieldConstraintRegex('`^' . $simple_regex . '(?:,' . $simple_regex . ')*$`iu');
		}
		else
		{
			$constraints[] = new FormFieldConstraintMailAddress();
			$constraints[] = new FormFieldConstraintForbiddenMailDomains();
		}
		parent::__construct($id, $label, $value, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-email');
	}

	public function display(): Template
	{
		$template = $this->get_template_to_use();

		$field = new FileTemplate('framework/builder/form/fieldelements/FormFieldMailEditor.tpl');

		$field->put_all([
			'SIZE' => $this->size,
			'MAX_LENGTH' => $this->maxlength,
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'TYPE' => $this->type,
			'VALUE' => $this->get_value(),
			'CLASS' => $this->get_css_class(),
			'C_DISABLED' => $this->is_disabled(),
			'C_READONLY' => $this->is_readonly(),
			'C_MULTIPLE' => $this->is_multiple(),
			'C_PLACEHOLDER' => $this->has_placeholder(),
			'PLACEHOLDER' => $this->placeholder
		]);

		$this->assign_common_template_variables($template);

		$template->assign_block_vars('fieldelements', [
			'ELEMENT' => $field->render()
		]);

		return $template;
	}

	protected function compute_options(array &$field_options): void
	{
		foreach ($field_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'multiple':
					$this->multiple = (bool)$value;
					unset($field_options['multiple']);
					break;
			}
		}
		parent::compute_options($field_options);
	}

	public function is_multiple(): bool
	{
		return $this->multiple;
	}

	public function set_multiple(bool $multiple): void
	{
		$this->multiple = $multiple;
	}
}
?>
