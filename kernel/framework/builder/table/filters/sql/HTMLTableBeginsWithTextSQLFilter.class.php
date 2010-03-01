<?php
/*##################################################
 *                         HTMLTableBeginsWithTextSQLFilter.class.php
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
 * @package builder
 * @subpackage table/sql
 */
class HTMLTableBeginsWithTextSQLFilter extends HTMLTableTextFilter implements SQLFragmentBuilder
{
	private $db_field;

	public function __construct($db_field, $name, $label, $match_regex)
	{
		$this->db_field = $db_field;
		parent::__construct($name, $label, $match_regex);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_sql()
	{
		$parameter_name = $this->db_field . '_value';
		$query = $this->db_field . ' LIKE :' . $parameter_name;
		$parameters = array($parameter_name => $this->get_value() . '%');
		return new SQLFragment($query, $parameters);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function set_value($value)
	{
		parent::set_value(str_replace('%', '', $value));
	}
}

?>