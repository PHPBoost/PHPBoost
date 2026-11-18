<?php
/**
 * @package     Builder
 * @subpackage  Table\filter\sql
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 3.0 - 2010 02 27
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class HTMLTableLikeFromListSQLFilter extends HTMLTableEqualsFromListFilter implements SQLFragmentBuilder
{
	private static int $param_id_index = 0;
	private mixed $db_field = null;

	public function __construct(mixed $db_field, string $name, string $label, array $allowed_values)
	{
		$this->db_field = $db_field;
		parent::__construct($name, $label, $allowed_values);
	}

	public function get_sql(): SQLFragment
	{
		$choice_option = $this->get_value();
		if ($choice_option instanceof FormFieldSelectChoiceOption)
		{
			$parameter_name = $this->get_sql_value_parameter_prefix() . '_' . $this->db_field;
			$query = $this->db_field . ' LIKE :' . $parameter_name;
			$parameters = [$parameter_name => $this->get_like_value()];
			return new SQLFragment($query, $parameters);
		}
		return new SQLFragment();
	}

	protected function get_like_value(): mixed
	{
		return $this->get_value()->get_raw_value();
	}

    protected function get_sql_value_parameter_prefix(): string
    {
        return self::class . '_' . self::$param_id_index++;
    }
}

?>
