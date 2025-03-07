<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 25
 * @since       PHPBoost 6.0 - 2016 06 01
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldConstraintPossibleValuesMax extends AbstractFormFieldConstraint
{
	private $error_message;

	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('warning.must.contain.max.input', 'warning-lang');
		}
		$this->error_message = $error_message;
	}

	public function validate(FormField $field)
	{
		$value = $field->get_value();
		$this->set_validation_error_message(StringVars::replace_vars($this->error_message, array('name' => TextHelper::strtolower($field->get_label()), 'max_input' => $field->get_max_input())));

		return is_array($value) && !empty($value) && count($value) <= $field->get_max_input();
	}

	public function get_js_validation(FormField $field)
	{
		return 'MaxPossibleValuesFormFieldValidator(' . TextHelper::to_js_string($field->get_html_id()) .
			', ' . $field->get_max_input() . ', ' . TextHelper::to_js_string(StringVars::replace_vars($this->error_message, array('name' => TextHelper::strtolower($field->get_label()), 'max_input' => $field->get_max_input()))) .')';
	}
}

?>
