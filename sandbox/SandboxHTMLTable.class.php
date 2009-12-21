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
		new HTMLTableColumn('pseudo'),
		new HTMLTableColumn('email'),
		new HTMLTableColumn('inscrit le'),
		new HTMLTableColumn('messages'),
		new HTMLTableColumn('dernière connexion'),
		new HTMLTableColumn('messagerie privée'),
		);
		$model = new HTMLTableModel($columns);
		parent::__construct($model);
	}

	protected function fill_data(array $sort_parameters = array())
	{
		$query = "SELECT user_id, login, user_mail, user_show_mail, timestamp, user_msg, last_connect
			FROM " . DB_TABLE_MEMBER . " WHERE user_aprob = 1";
		foreach (AppContext::get_sql_querier()->select($query) as $row)
		{
			$login = new HTMLTableRowCell($row['login'], array('row1'));
			$user_mail = new HTMLTableRowCell(($row['user_show_mail'] == 1) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/email.png" alt="' . $row['user_mail'] . '" /></a>' : '&nbsp;');
			$timestamp = new HTMLTableRowCell(gmdate_format('date_format_short', $row['timestamp']));
			$user_msg = new HTMLTableRowCell(!empty($row['user_msg']) ? $row['user_msg'] : '0');
			$last_connect = new HTMLTableRowCell(gmdate_format('date_format_short', !empty($row['last_connect']) ? $row['last_connect'] : $row['timestamp']));
			$pm_url = new Url('/member/pm.php?pm=' . $row['user_id']);
			$pm = new HTMLTableRowCell('<a href="' . $pm_url->absolute() . '"><img src="../templates/base/images/french/pm.png" alt="Message(s) privé(s)"></a>');
			
			$this->generate_row(new HTMLTableRow(array($login, $user_mail, $timestamp, $user_msg, $last_connect, $pm)));
		}
	}
}
?>
