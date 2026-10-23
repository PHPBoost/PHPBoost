<?php
/**
 * @package     Builder
 * @subpackage  Table\filter\sql
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 3.0 - 2010 03 02
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class HTMLTableNumberComparatorSQLFilter extends AbstractHTMLTableFilter implements SQLFragmentBuilder
{
	const NOT_BOUNDED = null;

	private static int $param_id_index = 0;

	private mixed $db_field = null;
	private mixed $lower_bound = null;
	private mixed $upper_bound = null;

	public function __construct(mixed $db_field, string $name, string $label, mixed $lower_bound = self::NOT_BOUNDED, mixed $upper_bound = self::NOT_BOUNDED)
	{
		$this->db_field = $db_field;
		$this->lower_bound = $lower_bound;
		$this->upper_bound = $upper_bound;
		$field = new FormFieldTextEditor($name, $label, '', ['size' => 5]);
		parent::__construct($name, $field);
	}

	public function get_sql(): SQLFragment
	{
		$parameter_name = $this->get_sql_value_parameter_prefix() . '_' . $this->db_field;
		$query = $this->db_field . ' ' . $this->get_sql_comparator_symbol() . ' :' . $parameter_name;
		$parameters = [$parameter_name => $this->get_value()];
		return new SQLFragment($query, $parameters);
	}

	public function is_value_allowed(mixed $value): bool
	{
		if (is_numeric($value))
		{
			$number = $this->get_number($value);
			if ($this->check_interval($number))
			{

				$this->set_value($value);
				return true;
			}
		}
		return false;
	}

    protected function get_sql_value_parameter_prefix(): string
    {
        return self::class . '_' . self::$param_id_index++;
    }

    abstract protected function get_sql_comparator_symbol(): mixed;

	protected function get_number(mixed $value): mixed
	{
		if (is_int($value))
		{
			return intval($value);
		}
		else
		{
			return floatval($value);
		}
	}

	protected function check_interval(int $number): bool
	{
        if ($this->lower_bound != self::NOT_BOUNDED && $number < $this->lower_bound)
        {
            return false;
        }
        if ($this->upper_bound != self::NOT_BOUNDED && $number > $this->upper_bound)
        {
            return false;
        }
        return true;
	}
}

?>
