<?php
/*##################################################
 *                           BrHTMLElement.class.php
 *                            -------------------
 *   begin                : January 19, 2015
 *   copyright            : (C) 2015 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * 
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @package {@package}
 */
class SQLHTMLTableModel extends HTMLTableModel
{
	protected $table;
	protected $parameters;

	public function __construct($table, $id, array $columns, HTMLTableSortingRule $default_sorting_rule, $rows_per_page = self::DEFAULT_PAGINATION)
	{
		$this->table = $table;
		parent::__construct($id, $columns, $default_sorting_rule, $rows_per_page);
	}

	public function get_number_of_matching_rows()
	{
		return PersistenceContext::get_querier()->count($this->table, $this->get_filtered_clause($this->html_table->parameters->get_filters()) . $this->get_permanent_filtered_clause($this->get_permanent_filters()), $this->parameters);
	}

	public function get_sql_results($sql_join = false, $select = array('*'))
	{
		$limit = $this->html_table->get_nb_rows_per_page();
		$offset = ($this->html_table->parameters->get_page_number() - 1) * $limit;
		$sorting_rule = $this->html_table->parameters->get_sorting_rule();
		$filters = $this->html_table->parameters->get_filters();
		$permanent_filters = $this->get_permanent_filters();

		$query = 'SELECT ' . implode(', ', $select) . ' ';
		$query .= $this->get_sql_from($sql_join);
		$query .= $this->get_filtered_clause($filters);
		$query .= $this->get_permanent_filtered_clause($permanent_filters);
		$query .= $this->get_order_clause($sorting_rule);
		$query .= $limit !== HTMLTableModel::NO_PAGINATION ? ' LIMIT ' . $limit . ' OFFSET ' . $offset : '';

		return PersistenceContext::get_querier()->select($query, $this->parameters);
	}

	public function get_sql_from($sql_join)
	{
		if (!empty($sql_join))
		{
			return 'FROM ' . $this->table . ' ' . $sql_join;
		}
		return 'FROM ' . $this->table;
	}

	private function get_filtered_clause(array $filters)
	{
		$this->parameters = array();
		$clause = ' WHERE 1';
		if (!empty($filters))
		{
			$sql_filters = array();
			foreach ($filters as $filter)
			{
				$query_fragment = $filter->get_sql();
				$query_fragment->add_parameters_to_map($this->parameters);
				if ($query_fragment->get_query())
					$sql_filters[] = $query_fragment->get_query();
			}
			$clause .= !empty($sql_filters) ? ' AND ' . implode(' AND ', $sql_filters) : '';
		}
		return $clause;
	}

	private function get_permanent_filtered_clause(array $permanent_filters)
	{
		if (!empty($permanent_filters))
		{
			return ' AND ' . implode(' AND ', $permanent_filters);
		}
	}

	private function get_order_clause(HTMLTableSortingRule $rule)
	{
		$order_clause = ' ORDER BY ';
		$order_clause .= $this->get_sort_parameter_column($rule) . ' ';
		if ($rule->get_order_way() == HTMLTableSortingRule::ASC)
		{
			$order_clause .= SQLQuerier::ORDER_BY_ASC;
		}
		else
		{
			$order_clause .= SQLQuerier::ORDER_BY_DESC;
		}
		return $order_clause;
	}

	private function get_sort_parameter_column(HTMLTableSortingRule $rule)
	{
		if ($this->is_sort_parameter_allowed($rule->get_sort_parameter()) || $rule->is_default_sorting())
		{
			return $rule->get_sort_parameter();
		}
	}
}
?>