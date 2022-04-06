<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 06
 * @since       PHPBoost 6.0 - 2020 06 17
*/

class PollItemsManagementController extends DefaultItemsManagementController
{
	protected function get_additional_html_table_columns()
	{
		return array(new HTMLTableColumn($this->lang['poll.manage.status'], 'close_poll'));
	}

	protected function get_additional_html_table_row_cells(&$item)
	{
		return array(new HTMLTableRowCell(!$item->is_closed() ? $this->lang['poll.manage.in.progress'] : $this->lang['common.status.finished']));
	}

	protected function get_additional_html_table_filters()
	{
		$map = array($this->lang['poll.manage.in.progress'], $this->lang['poll.manage.completed']);
		return array(new HTMLTableEqualsFromListSQLFilter('close_poll', 'filter6', $this->lang['poll.manage.status'], $map));
	}
}
?>
