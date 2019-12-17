<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2009 12 20
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldConstraintIntegerMin extends AbstractFormFieldConstraint
{
	private $error_message;
	private $lower_bound;

	public function __construct($lower_bound, $js_message = '')
	{
		if (empty($js_message))
		{
			$js_message = LangLoader::get_message('form.doesnt_match_integer_min', 'status-messages-common');
		}
		$this->error_message = StringVars::replace_vars($js_message, array('lower_bound' => $lower_bound));
		$this->set_validation_error_message($this->error_message);
		$this->lower_bound = $lower_bound;
	}

	public function validate(FormField $field)
	{
		$is_required = $field->is_required();
		$value = $field->get_value();
		if (!is_numeric($value))
		{
			return false;
		}
		$value = (int)$value;
		if (!empty($value) || $is_required)
		{
			return ($value >= $this->lower_bound);
		}
		return true;
	}

	public function get_js_validation(FormField $field)
	{
		return 'integerMinFormFieldValidator(' . TextHelper::to_js_string($field->get_id()) . ',
		' . (int)$this->lower_bound . ', ' . TextHelper::to_js_string($this->error_message) . ')';
	}
}

?>
