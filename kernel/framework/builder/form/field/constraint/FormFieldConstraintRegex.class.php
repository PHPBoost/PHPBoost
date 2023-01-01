<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 25
 * @since       PHPBoost 3.0 - 2009 12 19
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldConstraintRegex extends AbstractFormFieldConstraint
{
	private $error_message;
	private $php_regex;
	private $js_regex;
	private $js_options;

	public function __construct($php_regex, $js_regex = '', $error_message = '')
	{
		if (empty($js_regex))
		{
			$js_regex = $php_regex;
		}
		$this->parse_js_regex($js_regex);
		$this->php_regex = $php_regex;

		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('warning.regex', 'warning-lang');
		}
		$this->set_validation_error_message($error_message);
		$this->error_message = $error_message;

	}

	private function parse_js_regex($regex)
	{
		$delimiter = $regex[0];
		$end_delimiter_position = TextHelper::strrpos($regex, $delimiter);
		$js_regex = TextHelper::substr($regex, 1, $end_delimiter_position - 1);

		$js_options_chars = str_split(TextHelper::substr($regex, $end_delimiter_position + 1));
		$js_options = '';
		foreach ($js_options_chars as $option)
		{
			if (in_array($option, array('i', 'm', 'g')))
				$js_options .= $option;
		}

		$this->js_regex = str_replace('\.', '\\\.', $js_regex);
		$this->js_regex = '\'' . str_replace('\'', '\\\'', $this->js_regex) . '\'';
		$this->js_options = '\'' . str_replace('\'', '\\\'', $js_options) . '\'';
	}

	public function validate(FormField $field)
	{
		$value = $field->get_value();
		$is_required = $field->is_required();

		if ($value instanceof Date) {
			$value = $value->format(Date::FORMAT_ISO_DAY_MONTH_YEAR);
		}

		if (!empty($value) || $is_required)
		{
			return preg_match($this->php_regex, $value) > 0;
		}
		return true;
	}

	public function get_js_validation(FormField $field)
	{
		return 'regexFormFieldValidator(' . TextHelper::to_js_string($field->get_id()) .
			', ' . $this->js_regex . ', ' . $this->js_options . ', ' . TextHelper::to_js_string($this->error_message) . ')';
	}
}

?>
