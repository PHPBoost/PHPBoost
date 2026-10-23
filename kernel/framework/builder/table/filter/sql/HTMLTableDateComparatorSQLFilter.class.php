<?php
/**
 * @package     Builder
 * @subpackage  Table\filter\sql
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 5.0 - 2017 04 13
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class HTMLTableDateComparatorSQLFilter extends AbstractHTMLTableFilter implements SQLFragmentBuilder
{
	private static int $param_id_index = 0;
	private mixed $db_field = null;

	public function __construct(mixed $db_field, string $name, string $label)
	{
		$this->db_field = $db_field;
		$form_field_class = $this->get_form_field_class();
		$field = new $form_field_class($name, $label);
		parent::__construct($name, $field);
	}

    public function get_sql(): SQLFragment
	{
		$parameter_name = $this->get_sql_value_parameter_prefix() . '_' . $this->db_field;
		$query = $this->db_field . ' ' . $this->get_sql_comparator_symbol() . ' :' . $parameter_name;
		$parameters = [$parameter_name => $this->get_value()->get_timestamp()];
		return new SQLFragment($query, $parameters);
	}

	public function is_value_allowed(mixed $value): bool
	{
		if (preg_match("/\d{4}\-\d{2}-\d{2}/", $value) || preg_match("/\d{2}\/\d{2}\/\d{4}/", $value))
		{
			$this->set_value(new Date($this->get_timestamp($value), Timezone::SERVER_TIMEZONE));
			return true;
		}
		return false;
	}

	protected function get_sql_value_parameter_prefix(): string
	{
		return self::class . '_' . self::$param_id_index++;
	}

	abstract protected function get_sql_comparator_symbol(): mixed;

	abstract protected function get_form_field_class(): mixed;

	protected function get_timestamp(mixed $value): string
	{
		return strtotime($value);
	}
}

?>
