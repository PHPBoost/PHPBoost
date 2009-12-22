<?php
/*##################################################
 *                          SandboxHTMLTable.class.php
 *                            -------------------
 *   begin                : December 21, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class SandboxHTMLTable extends HTMLTable
{
	public function __construct()
	{
		$columns = array(
		new HTMLTableColumn('pseudo', 'pseudo'),
		new HTMLTableColumn('email'),
		new HTMLTableColumn('inscrit le', 'register_date'),
		new HTMLTableColumn('messages'),
		new HTMLTableColumn('dernière connexion'),
		new HTMLTableColumn('messagerie'),
		);
		$model = new HTMLTableModel($columns, 10);
		$model->set_id('42');
		$model->set_caption('Liste des membres');
		parent::__construct($model);
	}

	protected function get_number_of_elements()
	{
		return AppContext::get_sql_common_query()->count(DB_TABLE_MEMBER, 'WHERE user_aprob=1');
	}

	protected function default_sort_rule()
	{
		return new HTMLTableSortRule('user_id', HTMLTableSortRule::ASC);
	}

	protected function fill_data($limit, $offset, HTMLTableSortRule $sorting_rule, array $filters)
	{
		$query = $this->build_query($limit, $offset, $sorting_rule, $filters);
		Debug::dump($query);
		//				exit;
		$result = AppContext::get_sql_querier()->select($query);
		foreach ($result as $row)
		{
			$login = new HTMLTableRowCell($row['login'], array('row1'));
			$user_mail = new HTMLTableRowCell(($row['user_show_mail'] == 1) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/email.png" alt="' . $row['user_mail'] . '" /></a>' : '&nbsp;');
			$user_mail->set_css_style('width:50px;text-align:center;');
			$timestamp = new HTMLTableRowCell(gmdate_format('date_format_long', $row['timestamp']));
			$user_msg = new HTMLTableRowCell(!empty($row['user_msg']) ? $row['user_msg'] : '0');
			$last_connect = new HTMLTableRowCell(gmdate_format('date_format_long', !empty($row['last_connect']) ? $row['last_connect'] : $row['timestamp']));
			$pm_url = new Url('/member/pm.php?pm=' . $row['user_id']);
			$pm = new HTMLTableRowCell('<a href="' . $pm_url->absolute() . '"><img src="../templates/base/images/french/pm.png" alt="Message(s) privé(s)"></a>');
			$pm->set_css_style('width:50px;text-align:center;');

			$this->generate_row(new HTMLTableRow(array($login, $user_mail, $timestamp, $user_msg, $last_connect, $pm)));
		}
	}

	private function build_query($limit, $offset, HTMLTableSortRule $sorting_rule, array $filters)
	{
		$query = 'SELECT user_id, login, user_mail, user_show_mail, timestamp, user_msg, last_connect ' .
		'FROM ' . DB_TABLE_MEMBER . ' WHERE user_aprob = 1';
		$query .= $this->get_order_clause($sorting_rule);
		return $query . ' LIMIT ' . $limit . ' OFFSET ' . $offset;
	}

	private function get_order_clause(HTMLTableSortRule $rule)
	{
		$order_clause = ' ORDER BY ';
		$order_clause .= $this->get_sort_parameter_column($rule) . ' ';
		if ($rule->get_order_way() == HTMLTableSortRule::ASC)
		{
			$order_clause .= 'ASC';
		}
		else
		{
			$order_clause .= 'DESC';
		}
		return $order_clause;
	}

	private function get_sort_parameter_column(HTMLTableSortRule $rule)
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
}
?>
