<?php
/**
 * @package     Builder
 * @subpackage  Table\filter\sql
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2017 07 31
 * @since       PHPBoost 5.0 - 2017 04 18
*/

class HTMLTableAjaxUserAutoCompleteSQLFilter extends HTMLTableAjaxUserAutoCompleteFilter implements SQLFragmentBuilder
{
	private static $param_id_index = 0;

	private $db_field;

	public function __construct($db_field, $name, $label, $match_regex = null)
	{
		$this->db_field = $db_field;
		parent::__construct($name, $label, $match_regex);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_sql()
	{
		$parameter_name = $this->get_sql_value_parameter_prefix() . '_' . $this->db_field;
		$query = $this->db_field . ' LIKE :' . $parameter_name;
		$parameters = array($parameter_name => $this->get_value());
		return new SQLFragment($query, $parameters);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_value_allowed($value)
    {
        if (empty($value))
        {
            return false;
        }
        return parent::is_value_allowed($value);
    }

    protected function get_sql_value_parameter_prefix()
    {
        return __CLASS__ . '_' . self::$param_id_index++;
    }
}

?>
