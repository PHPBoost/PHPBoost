<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 25
 * @since       PHPBoost 6.0 - 2020 06 17
*/

class PollItemsManagementController extends DefaultItemsManagementController
{
  protected function get_additional_html_table_columns()
  {
    return array(new HTMLTableColumn($this->lang['poll.manage.opened.votes'], 'close_poll'));
  }
  
  protected function get_additional_html_table_row_cells(&$item)
  {
	$picture_yes = '<i class="fa fa-check success" aria-hidden="true"></i><span class="sr-only">' . LangLoader::get_message('common.yes', 'common-lang') . '</span>';
	$picture_no = '<i class="fa fa-times error" aria-hidden="true"></i><span class="sr-only">' . LangLoader::get_message('common.no', 'common-lang') . '</span>';
		
    return array(new HTMLTableRowCell(!$item->is_closed() ? $picture_yes : $picture_no));
  }
  
  protected function get_additional_html_table_filters()
  {
    return array(new HTMLTableapprovedSQLFilter('close_poll', 'filter6', $this->lang['poll.manage.opened.votes']));
  }
}
?>