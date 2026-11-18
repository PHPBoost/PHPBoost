<?php
/**
 * This class manages a range of numbers (slider).
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 4.1 - 2015 06 01
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldRangeEditor extends FormFieldNumberEditor
{
	protected string $type = 'range';
	private bool $vertical = false;

	/**
	 * Constructs a FormFieldRange.
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param mixed $value Default value
	 * @param array $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct(string $id, string $label, mixed $value, array $field_options = [], array $constraints = [])
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-range');
	}

	public function display(): Template
	{
		$template = $this->get_template_to_use();

		$field = new FileTemplate('framework/builder/form/fieldelements/FormFieldRangeEditor.tpl');

		$field->put_all([
			'C_MIN' => $this->min !== null,
			'MIN' => $this->min,
			'C_MAX' => $this->max != 0,
			'MAX' => $this->max,
			'C_STEP' => $this->step > 0,
			'STEP' => $this->step,
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'TYPE' => $this->type,
			'VALUE' => $this->get_value(),
			'CLASS' => $this->get_css_class(),
			'C_DISABLED' => $this->is_disabled(),
			'C_READONLY' => $this->is_readonly(),
			'C_PATTERN' => $this->has_pattern(),
			'PATTERN' => $this->pattern,
			'C_VERTICAL' => $this->is_vertical()
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
				case 'vertical':
					$this->vertical = (bool)$value;
					unset($field_options['vertical']);
					break;
			}
		}
		parent::compute_options($field_options);
	}

	public function is_vertical(): bool
	{
		return $this->vertical;
	}

	public function set_vertical(bool $vertical): void
	{
		$this->vertical = $vertical;
	}
}
?>
