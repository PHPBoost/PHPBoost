<?php
/**
 * @package     Builder
 * @subpackage  Table\filter\sql
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 24
 * @since       PHPBoost 5.0 - 2021 06 23
*/

class HTMLTableapprovedSQLFilter extends HTMLTableCheckboxFilter implements SQLFragmentBuilder
{
	private static $param_id_index = 0;
	private $db_field;
	private $parameter_value;
	

	public function __construct($db_field, $name, $label, $checked = FormFieldCheckbox::CHECKED)
	{
		$this->db_field = $db_field;
		$this->parameter_value = $parameter_value = 0;
		parent::__construct($name, $label, $checked);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_sql()
	{
		$parameter_name = $this->get_sql_value_parameter_prefix() . '_' . $this->db_field;
		$query = $this->db_field . ' = :' . $parameter_name;
		$parameters = array($parameter_name => $this->parameter_value);
		
		return new SQLFragment($query, $parameters);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_value_allowed($value)
    {
        if (!is_numeric($value))
            return false;
        else
        {
			$this->parameter_value = empty($value) ? 1 : 0;
			$this->set_value($value);
			
			return true;
        }
    }

    protected function get_sql_value_parameter_prefix()
    {
        return __CLASS__ . '_' . self::$param_id_index++;
    }
}

?>
