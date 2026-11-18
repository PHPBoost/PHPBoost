<?php
/**
 * @package     Builder
 * @subpackage  Form\constraint
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 21
 * @since       PHPBoost 4.1 - 2015 11 09
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormConstraintFieldsDifferenceSuperior implements FormConstraint
{
	private mixed $js_message = null;
	private ?FormField $first_field = null;
	private ?FormField $second_field = null;

	public function __construct(FormField $first_field, FormField $second_field, string $js_message = '')
	{
		if (!empty($js_message))
		{
			$this->js_message = $js_message;
		}
		else
		{
			$this->js_message = LangLoader::get_message('warning.first.field.must.be.superior.to.second.field', 'warning-lang');
		}

		$this->first_field = $first_field;
		$this->second_field = $second_field;

		$this->second_field->add_form_constraint($this);
	}

	public function validate(): bool
	{
		$first_value = $this->first_field->get_value();
		$second_value = $this->second_field->get_value();
		if ($first_value !== null && $second_value !== null)
		{
			if ($first_value instanceof Date && $second_value instanceof Date)
			{
				if ($second_value->get_timestamp() > $first_value->get_timestamp())
				{
					return true;
				}
			}
			else
			{
				if ($second_value > $first_value)
				{
					return true;
				}
			}
		}
		return false;
	}

	public function get_js_validation(): string
	{
		return "";
	}

	public function get_validation_error_message(): mixed
	{
		return StringVars::replace_vars($this->js_message,
			['field1' => $this->first_field->get_label(), 'field2' => $this->second_field->get_label()]);
	}

	public function get_related_fields(): array
	{
		return [$this->first_field, $this->second_field];
	}
}

?>
