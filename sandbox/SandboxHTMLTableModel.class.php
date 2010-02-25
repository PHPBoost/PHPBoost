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

class SandboxHTMLTableModel extends DefaultHTMLTableModel
{
	private $query;
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
		parent::__construct($columns, $default_sorting_rule, 3);
		$this->set_caption('Liste des membres');
	}

	//		$options = array(
	//			new FormFieldSelectOption('tous', ''),
	//			new FormFieldSelectOption('Horn', 'horn'),
	//			new FormFieldSelectOption('CouCou', 'coucou')
	//		);
	//		$model->add_filter(new HTMLTableSelectFilterHTMLForm('Pseudo', 'login', $options));

	public function get_number_of_matching_rows(array $filters)
	{
		$this->parameters = array();
		return AppContext::get_sql_common_query()->count(DB_TABLE_MEMBER,
        	'WHERE user_aprob=1' . $this->get_filtered_clause($filters) , $this->parameters);
	}

	public function get_rows($limit, $offset, HTMLTableSortingRule $sorting_rule, array $filters)
	{
		$results = array();
		$this->build_query($limit, $offset, $sorting_rule, $filters);
		//		echo $this->query .'<hr />';
		$result = AppContext::get_sql_querier()->select($this->query, $this->parameters);
		foreach ($result as $row)
		{
			$login = new HTMLTableRowCell($row['login'], array('row1'));
			$user_mail = new HTMLTableRowCell(($row['user_show_mail'] == 1) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/email.png" alt="' . $row['user_mail'] . '" /></a>' : '&nbsp;');
			$user_mail->add_css_style('width:50px');
			$user_mail->center();
			$timestamp = new HTMLTableRowCell(gmdate_format('date_format_long', $row['timestamp']));
			$user_msg = new HTMLTableRowCell(!empty($row['user_msg']) ? $row['user_msg'] : '0');
			$user_msg->center();
			$last_connect = new HTMLTableRowCell(gmdate_format('date_format_long', !empty($row['last_connect']) ? $row['last_connect'] : $row['timestamp']));
			$pm_url = new Url('/member/pm.php?pm=' . $row['user_id']);
			$pm = new HTMLTableRowCell('<a href="' . $pm_url->absolute() . '"><img src="../templates/base/images/french/pm.png" alt="Message(s) privé(s)" /></a>');
			$pm->center();
			$pm->add_css_style('width:50px');

			$results[] = new HTMLTableRow(array($login, $user_mail, $timestamp, $user_msg, $last_connect, $pm));
		}
		return $results;
	}

	private function build_query($limit, $offset, HTMLTableSortingRule $sorting_rule, array $filters)
	{
		$this->parameters = array();
		$this->query = 'SELECT user_id, login, user_mail, user_show_mail, timestamp, user_msg, last_connect ' .
		'FROM ' . DB_TABLE_MEMBER . ' WHERE user_aprob = 1';
		$this->query .= $this->get_filtered_clause($filters);
		$this->query .= $this->get_order_clause($sorting_rule);
		$this->query .= ' LIMIT ' . $limit . ' OFFSET ' . $offset;
	}

	private function get_filtered_clause(array $filters)
	{
		$clause = '';
		if (!empty($filters))
		{
			$sql_filters = array();
			foreach ($filters as $filter)
			{
				$value = $filter->get_value();
				$parameter = $filter->get_filter_parameter();
				$sql_column = $this->get_filter_parameter_column($filter);
				if ($filter->get_mode() == HTMLTableFilter::EQUALS)
				{
					$sql_filters[] = $sql_column . '=:' . $parameter;
					$this->parameters[$parameter] = $value;
				}
				else
				{
					$sql_filters[] = $sql_column . ' LIKE :' . $parameter;
					$this->parameters[$parameter] = $value . '%';
				}
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
			$order_clause .= 'ASC';
		}
		else
		{
			$order_clause .= 'DESC';
		}
		return $order_clause;
	}

	private function get_sort_parameter_column(HTMLTableSortingRule $rule)
	{
		switch ($rule->get_sort_parameter())
		{
			case 'pseudo':
				return 'login';
			case 'register_date':
				return 'timestamp';
			case 'user_id':
			default:
				return 'user_id';
				break;
		}
	}

	private function get_filter_parameter_column(HTMLTableFilter $filter)
	{
		switch ($filter->get_filter_parameter())
		{
			case 'login':
			default:
				return 'login';
				break;
		}
	}
}
?>
