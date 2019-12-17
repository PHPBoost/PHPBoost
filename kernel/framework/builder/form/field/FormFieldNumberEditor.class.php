<?php
/**
 * This class manage number fields.
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 06 03
 * @since       PHPBoost 4.1 - 2015 05 20
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class FormFieldNumberEditor extends AbstractFormField
{
	protected $type = 'number';
	protected $min = null;
	protected $max = 0;
	protected $step = 0;
	protected $pattern = '[0-9]*';

	/**
	 * Constructs a FormFieldNumberEditor.
	 * It has these options in addition to the AbstractFormField ones:
	 * <ul>
	 * 	<li>size: The min value of the HTML field</li>
	 * 	<li>size: The max value of the HTML field</li>
	 * 	<li>size: The step of the value of the HTML field</li>
	 * </ul>
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param string $value Default value
	 * @param string[] $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct($id, $label, $value, array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-number');
	}

	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$field = new FileTemplate('framework/builder/form/fieldelements/FormFieldNumberEditor.tpl');

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
			'C_PLACEHOLDER' => $this->has_placeholder(),
			'PLACEHOLDER' => $this->placeholder
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
				case 'min':
					$this->min = $value;
					unset($field_options['min']);
					break;
				case 'max':
					$this->max = $value;
					unset($field_options['max']);
					break;
				case 'step':
					$this->step = $value;
					unset($field_options['step']);
					break;
			}
		}
		parent::compute_options($field_options);
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
}
?>
