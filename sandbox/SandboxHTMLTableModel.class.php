<?php
/*##################################################
 *                          SandboxHTMLTableModel.class.php
 *                            -------------------
 *   begin                : February 25, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
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

class SandboxHTMLTableModel extends AbstractHTMLTableModel
{
	private $parameters;

	public function __construct()
	{
		$columns = array(
			new HTMLTableColumn('pseudo', 'pseudo'),
			new HTMLTableColumn('email'),
			new HTMLTableColumn('inscrit le', 'register_date'),
			new HTMLTableColumn('messages'),
			new HTMLTableColumn('dernière connexion'),
			new HTMLTableColumn('messagerie')
		);
		
		$default_sorting_rule = new HTMLTableSortingRule('user_id', HTMLTableSortingRule::ASC);
		$nb_items_per_page = 3;
		
		parent::__construct($columns, $default_sorting_rule);
		
		$this->set_caption('Liste des membres');
		$this->set_nb_rows_options(array(1, 2, 4, 8, 10, 15));
		$this->set_id('t42');

		$options = array('horn' => 'Horn', 'coucou' => 'Coucou', 'teston' => 'teston');
		$this->add_filter(new HTMLTableEqualsFromListSQLFilter('display_name', 'filter1', 'login Equals', $options));
        $this->add_filter(new HTMLTableBeginsWithTextSQLFilter('display_name', 'filter2', 'login Begins with (regex)', '`^(?!%).+$`'));
        $this->add_filter(new HTMLTableBeginsWithTextSQLFilter('display_name', 'filter3', 'login Begins with (no regex)'));
        $this->add_filter(new HTMLTableEndsWithTextSQLFilter('display_name', 'filter4', 'login Ends with (regex)', '`^(?!%).+$`'));
        $this->add_filter(new HTMLTableEndsWithTextSQLFilter('display_name', 'filter5', 'login Ends with (no regex)'));
        $this->add_filter(new HTMLTableLikeTextSQLFilter('display_name', 'filter6', 'login Like (regex)', '`^toto`'));
        $this->add_filter(new HTMLTableLikeTextSQLFilter('display_name', 'filter7', 'login Like (no regex)'));
        $this->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter8', 'id >'));
        $this->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter9', 'id > (lower=3)', 3));
        $this->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter10', 'id > (upper=3)', HTMLTableNumberComparatorSQLFilter::NOT_BOUNDED, 3));
        $this->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter11', 'id > (lower=1, upper=3)', 1, 3));
        $this->add_filter(new HTMLTableLessThanSQLFilter('user_id', 'filter12', 'id <'));
        $this->add_filter(new HTMLTableGreaterThanOrEqualsToSQLFilter('user_id', 'filter13', 'id >='));
        $this->add_filter(new HTMLTableLessThanOrEqualsToSQLFilter('user_id', 'filter14', 'id <='));
        $this->add_filter(new HTMLTableEqualsToSQLFilter('user_id', 'filter15', 'id ='));
		
	}

	public function get_number_of_matching_rows(array $filters)
	{
		return PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, $this->get_filtered_clause($filters), $this->parameters);
	}

	public function get_rows($limit, $offset, HTMLTableSortingRule $sorting_rule, array $filters)
	{
		$results = array();
		$query = $this->build_query($limit, $offset, $sorting_rule, $filters);
		$result = PersistenceContext::get_querier()->select($query, $this->parameters);
		foreach ($result as $row)
		{
			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell($row['display_name']),
				new HTMLTableRowCell(($row['show_email'] == 1) ? '<a href="mailto:' . $row['email'] . '" class="basic-button smaller">Mail</a>' : '&nbsp;'),
				new HTMLTableRowCell(gmdate_format('date_format_long', $row['registration_date'])),
				new HTMLTableRowCell(!empty($row['posted_msg']) ? $row['posted_msg'] : '0'),
				new HTMLTableRowCell(gmdate_format('date_format_long', !empty($row['last_connection_date']) ? $row['last_connection_date'] : $row['registration_date'])),
				new HTMLTableRowCell('<a href="' . Url::to_rel('/user/pm.php?pm=' . $row['user_id']) . '" class="basic-button smaller">MP</a>')
			));
		}
		return $results;
	}

	private function build_query($limit, $offset, HTMLTableSortingRule $sorting_rule, array $filters)
	{
		Debug::dump($limit);
		Debug::dump($offset);
		$query = 'SELECT user_id, display_name, email, show_email, registration_date, last_connection_date, posted_msg ' .
		'FROM ' . DB_TABLE_MEMBER;
		$query .= $this->get_filtered_clause($filters);
		$query .= $this->get_order_clause($sorting_rule);
		$query .= $limit !== AbstractHTMLTableModel::NO_PAGINATION ? ' LIMIT ' . $limit . ' OFFSET ' . $offset : '';
		return $query;
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
				$sql_filters[] = $query_fragment->get_query();
			}
			$clause .= ' AND ' . implode(' AND ', $sql_filters);
		}
		return $clause;
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
		$values = array('pseudo' => 'display_name', 'register_date' => 'registration_date');
		$default = 'user_id';
		return Arrays::find($rule->get_sort_parameter(), $values, $default);
	}
}
?>