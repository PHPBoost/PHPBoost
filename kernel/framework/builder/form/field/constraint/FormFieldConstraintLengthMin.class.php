<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 22
 * @since       PHPBoost 3.0 - 2009 12 19
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldConstraintLengthMin extends AbstractFormFieldConstraint
{
	private string $error_message = '';
	private int $lower_bound = 0;

	public function __construct(int $lower_bound, string $js_message = '')
	{
		if (empty($js_message))
		{
			$js_message = LangLoader::get_message('warning.length.min', 'warning-lang');
		}
		$this->error_message = StringVars::replace_vars($js_message, ['lower_bound' => $lower_bound]);
		$this->set_validation_error_message($this->error_message);
		$this->lower_bound = $lower_bound;
	}

	public function validate(FormField $field): bool
	{
		$value = TextHelper::strlen($field->get_value());
		$is_required = $field->is_required();
		if (!empty($value) || $is_required)
		{
			return ($value >= $this->lower_bound);
		}
		return true;
	}

	public function get_js_validation(FormField $field): string
	{
		return 'lengthMinFormFieldValidator(' . TextHelper::to_js_string($field->get_id()) . ', ' . $this->lower_bound . ', ' . TextHelper::to_js_string($this->error_message) . ')';
	}
}

?>
