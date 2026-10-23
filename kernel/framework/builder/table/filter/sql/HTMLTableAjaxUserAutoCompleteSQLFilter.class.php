<?php
/**
 * @package     Builder
 * @subpackage  Table\filter\sql
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 5.0 - 2017 04 18
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class HTMLTableAjaxUserAutoCompleteSQLFilter extends HTMLTableAjaxUserAutoCompleteFilter implements SQLFragmentBuilder
{
	private static int $param_id_index = 0;
	private mixed $db_field = null;

	public function __construct(mixed $db_field, string $name, string $label, mixed $match_regex = null)
	{
		$this->db_field = $db_field;
		parent::__construct($name, $label, $match_regex);
	}

	public function get_sql(): SQLFragment
	{
		$parameter_name = $this->get_sql_value_parameter_prefix() . '_' . $this->db_field;
		$query = $this->db_field . ' LIKE :' . $parameter_name;
		$parameters = [$parameter_name => $this->get_value()];
		return new SQLFragment($query, $parameters);
	}

	public function is_value_allowed(mixed $value): bool
    {
        if (empty($value))
        {
            return false;
        }
        return parent::is_value_allowed($value);
    }

    protected function get_sql_value_parameter_prefix(): string
    {
        return self::class . '_' . self::$param_id_index++;
    }
}

?>
