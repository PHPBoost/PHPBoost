<?php
/**
 * @package     Builder
 * @subpackage  Form\constraint
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 23
 * @since       PHPBoost 5.2 - 2019 07 26
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormConstraintFieldsNotIncluded implements FormConstraint
{
	private $js_message;
	/**
	 * @var FormField
	 */
	private $first_field;
	/**
	 * @var FormField
	 */
	private $second_field;

	public function __construct(FormField $first_field, FormField $second_field, $js_message = '')
	{
		if (!empty($js_message))
		{
			$this->js_message = $js_message;
		}
		else
		{
			$this->js_message = LangLoader::get_message('warning.first.field.must.not.be.contained.in.second.field', 'warning-lang');
		}

		$this->first_field = $first_field;
		$this->second_field = $second_field;

		$this->second_field->add_form_constraint($this);
	}

	public function validate()
	{
		$searched_string = $this->first_field->get_value();
		$string = $this->second_field->get_value();
		if ($searched_string !== null && $string !== null)
		{
			if (empty($searched_string) || strpos($string, $searched_string) === false)
				return true;
		}
		return false;
	}

	public function get_js_validation()
	{
		return 'inclusionFormFieldValidator(' . TextHelper::to_js_string($this->first_field->get_id()) .
			', ' . TextHelper::to_js_string($this->second_field->get_id()) . ', ' . TextHelper::to_js_string($this->get_validation_error_message()) . ')';
	}

	public function get_validation_error_message()
	{
		return StringVars::replace_vars($this->js_message,
			array('field1' => $this->first_field->get_label(), 'field2' => $this->second_field->get_label()));
	}

	public function get_related_fields()
	{
		return array($this->first_field, $this->second_field);
	}
}

?>
