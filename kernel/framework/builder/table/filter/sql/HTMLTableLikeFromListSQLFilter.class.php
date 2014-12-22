<?php
/*##################################################
 *                         HTMLTableLikeFromListSQLFilter.class.php
 *                            -------------------
 *   begin                : February 27, 2010
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
class HTMLTableLikeFromListSQLFilter extends HTMLTableEqualsFromListFilter implements SQLFragmentBuilder
{
	private static $param_id_index = 0;
	
	private $db_field;

	public function __construct($db_field, $name, $label, array $allowed_values)
	{
		$this->db_field = $db_field;
		parent::__construct($name, $label, $allowed_values);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_sql()
	{
		$choice_option = $this->get_value();
		if ($choice_option instanceof FormFieldSelectChoiceOption)
		{
			$parameter_name = $this->get_sql_value_parameter_prefix() . '_' . $this->db_field;
			$query = $this->db_field . 'LIKE :' . $parameter_name;
			$parameters = array($parameter_name => $this->get_like_value());
			return new SQLFragment($query, $parameters);
		}
		return new SQLFragment();
	}
	
	protected function get_like_value()
	{
		return $this->get_value()->get_raw_value();
	}
    
    protected function get_sql_value_parameter_prefix()
    {
        return __CLASS__ . '_' . self::$param_id_index++;
    }
}

?>