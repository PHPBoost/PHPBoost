<?php
/**
 * @package     Builder
 * @subpackage  Table
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 23
 * @since       PHPBoost 4.1 - 2015 01 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SQLHTMLTableModel extends HTMLTableModel
{
	protected $table;
	protected $parameters;
	protected $sql_join;

	public function __construct($table, $id, array $columns, HTMLTableSortingRule $default_sorting_rule, $rows_per_page = self::DEFAULT_PAGINATION)
	{
		$this->table = $table;
		parent::__construct($id, $columns, $default_sorting_rule, $rows_per_page);
	}

	public function get_number_of_matching_rows()
	{
		return PersistenceContext::get_querier()->count($this->table, $this->sql_join . $this->get_filtered_clause($this->html_table->parameters->get_filters()) . $this->get_permanent_filtered_clause($this->get_permanent_filters()), $this->parameters);
	}

	public function get_sql_results($sql_join = '', $select = array('*'))
	{
		$limit = $this->html_table->get_nb_rows_per_page();
		$offset = ($this->html_table->parameters->get_page_number() - 1) * $limit;
		$sorting_rule = $this->html_table->parameters->get_sorting_rule();
		$filters = $this->html_table->parameters->get_filters();
		$permanent_filters = $this->get_permanent_filters();
		$this->sql_join = $sql_join;

		$query = 'SELECT ' . implode(', ', $select) . ' ';
		$query .= $this->get_sql_from();
		$query .= $this->get_filtered_clause($filters);
		$query .= $this->get_permanent_filtered_clause($permanent_filters);
		$query .= $this->get_order_clause($sorting_rule);
		$query .= $limit !== HTMLTableModel::NO_PAGINATION ? ' LIMIT ' . $limit . ' OFFSET ' . $offset : '';

		return PersistenceContext::get_querier()->select($query, $this->parameters);
	}

	public function get_sql_from()
	{
		if ($this->sql_join)
		{
			return 'FROM ' . $this->table . ' ' . $this->sql_join;
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
