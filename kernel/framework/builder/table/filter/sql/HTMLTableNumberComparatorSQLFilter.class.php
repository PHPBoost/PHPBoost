<?php
/*##################################################
 *                        HTMLTableNumberComparatorSQLFilter.class.php
 *                            -------------------
 *   begin                : March 2, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc
 * @package {@package}
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