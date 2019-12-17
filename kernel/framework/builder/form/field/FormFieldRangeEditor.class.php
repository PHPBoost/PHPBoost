<?php
/**
 * This class manages a range of numbers (slider).
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 06 03
 * @since       PHPBoost 4.1 - 2015 06 01
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class FormFieldRangeEditor extends FormFieldNumberEditor
{
	protected $type = 'range';
	/**
	 * @var boolean
	 */
	private $vertical = false;

	/**
	 * Constructs a FormFieldRange.
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param string $value Default value
	 * @param string[] $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct($id, $label, $value, array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-range');
	}

	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$field = new FileTemplate('framework/builder/form/fieldelements/FormFieldRangeEditor.tpl');

		$field->put_all(array(
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
			'C_DISABLED' => $this->is_disabled(),
			'C_PATTERN' => $this->has_pattern(),
			'PATTERN' => $this->pattern,
			'C_VERTICAL' => $this->is_vertical()
		));

		$this->assign_common_template_variables($template);

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $field->render()
		));

		return $template;
	}

	protected function compute_options(array &$field_options)
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

	/**
	 * Tells whether the slider is vertical
	 * @return true if it is, false otherwise
	 */
	public function is_vertical()
	{
		return $this->vertical;
	}

	/**
	 * Changes the fact that the field is vertical or not.
	 * @param bool $vertical true if it's vertical, false otherwise
	 */
	public function set_vertical($vertical)
	{
		$this->vertical = $vertical;
	}
}
?>
