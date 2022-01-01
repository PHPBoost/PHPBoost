<?php
/**
 * @package     Builder
 * @subpackage  Table\filter\sql
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 03 02
*/

abstract class HTMLTableNumberComparatorSQLFilter extends AbstractHTMLTableFilter implements SQLFragmentBuilder
{
	const NOT_BOUNDED = null;

	private static $param_id_index = 0;

	private $db_field;
	private $lower_bound;
	private $upper_bound;

	public function __construct($db_field, $name, $label, $lower_bound = self::NOT_BOUNDED, $upper_bound = self::NOT_BOUNDED)
	{
		$this->db_field = $db_field;
		$this->lower_bound = $lower_bound;
		$this->upper_bound = $upper_bound;
		$field = new FormFieldTextEditor($name, $label, '', array('size' => 5));
		parent::__construct($name, $field);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_sql()
	{
		$parameter_name = $this->get_sql_value_parameter_prefix() . '_' . $this->db_field;
		$query = $this->db_field . ' ' . $this->get_sql_comparator_symbol() . ' :' . $parameter_name;
		$parameters = array($parameter_name => $this->get_value());
		return new SQLFragment($query, $parameters);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_value_allowed($value)
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

    protected function get_sql_value_parameter_prefix()
    {
        return __CLASS__ . '_' . self::$param_id_index++;
    }

    abstract protected function get_sql_comparator_symbol();

	protected function get_number($value)
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

	protected function check_interval($number)
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
