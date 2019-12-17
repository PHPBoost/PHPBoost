<?php
/**
 * @package     Builder
 * @subpackage  Table\filter\sql
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 07 31
 * @since       PHPBoost 5.0 - 2017 04 18
*/

class HTMLTableUnapprovedSQLFilter extends HTMLTableCheckboxFilter implements SQLFragmentBuilder
{
	private static $param_id_index = 0;

	private $db_field;

	public function __construct($db_field, $name, $label, $checked = FormFieldCheckbox::UNCHECKED)
	{
		$this->db_field = $db_field;
		parent::__construct($name, $label, $checked);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_sql()
	{
		$parameter_name = $this->get_sql_value_parameter_prefix() . '_' . $this->db_field;
		$query = $this->db_field . ' = ' . $parameter_name;
		$parameters = array($parameter_name => 0);
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
