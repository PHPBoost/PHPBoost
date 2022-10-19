<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 25
 * @since       PHPBoost 6.0 - 2016 06 01
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldConstraintPossibleValuesUnique extends AbstractFormFieldConstraint
{
	private $error_message;

	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('warning.unique.input.value', 'warning-lang');
		}
		$this->error_message = $error_message;
	}

	public function validate(FormField $field)
	{
		$value = $field->get_value();
		$this->set_validation_error_message(StringVars::replace_vars($this->error_message, array('name' => TextHelper::strtolower($field->get_label()))));

    if (is_array($value) && !empty($value))
    {
		$input_values = array_keys($value);
		$input_values_unique = array_unique($input_values);

		return count($input_values) == count($input_values_unique);
	}
	else
			return false;
	}

	public function get_js_validation(FormField $field)
	{
		return 'UniquePossibleValuesFormFieldValidator(' . TextHelper::to_js_string($field->get_html_id()) .
			', ' . TextHelper::to_js_string(StringVars::replace_vars($this->error_message, array('name' => TextHelper::strtolower($field->get_label())))) .')';
	}
}

?>
