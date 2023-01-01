<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 28
 * @since       PHPBoost 3.0 - 2009 12 19
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldConstraintNotEmpty extends AbstractFormFieldConstraint
{
	private $error_message;

	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('warning.has.to.be.filled', 'warning-lang');
		}
		$this->error_message = $error_message;
	}

	public function validate(FormField $field)
	{
		$value = $field->get_value();
		$this->set_validation_error_message(StringVars::replace_vars($this->error_message, array('name' => TextHelper::strtolower($field->get_label()))));

		if ($value instanceof FormFieldEnumOption) {
			return $value->get_raw_value() !== null && $value->get_raw_value() != '';
		}

		if ($value instanceof Date) {
			return $value->format(Date::FORMAT_ISO_DAY_MONTH_YEAR) !== null && $value->format(Date::FORMAT_ISO_DAY_MONTH_YEAR) != '';
		}

		return $value == 0 || ($value !== null && $value != '');
	}

	public function get_js_validation(FormField $field)
	{
		return 'notEmptyFormFieldValidator(' . TextHelper::to_js_string($field->get_id()) .
			', ' . TextHelper::to_js_string(StringVars::replace_vars($this->error_message, array('name' => TextHelper::strtolower($field->get_label())))) .')';
	}
}

?>
