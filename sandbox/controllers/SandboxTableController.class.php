<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 01
 * @since       PHPBoost 3.0 - 2009 12 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxTableController extends DefaultModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('sandbox/SandboxTableController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$current_page = $this->build_table();

		$this->view->put('SANDBOX_SUBMENU', SandboxSubMenu::get_submenu());

		return $this->generate_response($current_page);
	}

	private function build_table()
	{
		$table_model = new SQLHTMLTableModel(DB_TABLE_MEMBER, '', array(
			new HTMLTableColumn($this->lang['sandbox.table.header.login'], 'display_name','col-large'),
			new HTMLTableColumn($this->lang['sandbox.table.header.email']),
			new HTMLTableColumn($this->lang['sandbox.table.header.registred'], 'registration_date'),
			new HTMLTableColumn($this->lang['sandbox.table.header.messages']),
			new HTMLTableColumn($this->lang['sandbox.table.header.connected']),
			new HTMLTableColumn($this->lang['sandbox.table.header.messaging'])
		), new HTMLTableSortingRule('user_id', HTMLTableSortingRule::ASC));

		$table_model->set_layout_title($this->lang['sandbox.table.member.list']);

		$options = array('jod' => 'John Doe', 'jad' => 'Jane Doe', 'jid' => 'Jim Doe');
		$table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('display_name', 'filter1', $this->lang['sandbox.table.login.equals'], $options));
		$table_model->add_filter(new HTMLTableBeginsWithTextSQLFilter('display_name', 'filter2', $this->lang['sandbox.table.login.beguin.regex'], '`^(?!%).+$`u'));
		$table_model->add_filter(new HTMLTableBeginsWithTextSQLFilter('display_name', 'filter3', $this->lang['sandbox.table.login.beguin']));
		$table_model->add_filter(new HTMLTableEndsWithTextSQLFilter('display_name', 'filter4', $this->lang['sandbox.table.login.end.regex'], '`^(?!%).+$`u'));
		$table_model->add_filter(new HTMLTableEndsWithTextSQLFilter('display_name', 'filter5', $this->lang['sandbox.table.login.end']));
		$table_model->add_filter(new HTMLTableLikeTextSQLFilter('display_name', 'filter6', $this->lang['sandbox.table.login.like.regex'], '`^toto`u'));
		$table_model->add_filter(new HTMLTableLikeTextSQLFilter('display_name', 'filter7', $this->lang['sandbox.table.login.like']));
		$table_model->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter8', $this->lang['sandbox.table.id.more']));
		$table_model->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter9', $this->lang['sandbox.table.id.more.lower'], 3));
		$table_model->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter10', $this->lang['sandbox.table.id.more.upper'], HTMLTableNumberComparatorSQLFilter::NOT_BOUNDED, 3));
		$table_model->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter11', $this->lang['sandbox.table.id.more.lower.upper'], 1, 3));
		$table_model->add_filter(new HTMLTableLessThanSQLFilter('user_id', 'filter12', $this->lang['sandbox.table.id.less']));
		$table_model->add_filter(new HTMLTableGreaterThanOrEqualsToSQLFilter('user_id', 'filter13', $this->lang['sandbox.table.id.more.equal']));
		$table_model->add_filter(new HTMLTableLessThanOrEqualsToSQLFilter('user_id', 'filter14', $this->lang['sandbox.table.id.less.equal']));
		$table_model->add_filter(new HTMLTableEqualsToSQLFilter('user_id', 'filter15', $this->lang['sandbox.table.id.equal']));

		$table = new HTMLTable($table_model);
		$table->set_filters_fieldset_class_HTML();
		$table->hide_multiple_delete();

		$results = array();
		$result = $table_model->get_sql_results();
		foreach ($result as $row)
		{
			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell($row['display_name']),
				new HTMLTableRowCell(($row['show_email'] == 1) ? '<a href="mailto:' . $row['email'] . '" class="button alt-button smaller">Mail</a>' : '&nbsp;'),
				new HTMLTableRowCell(Date::to_format($row['registration_date'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell(!empty($row['posted_msg']) ? $row['posted_msg'] : '0'),
				new HTMLTableRowCell(!empty($row['last_connection_date']) ? Date::to_format($row['last_connection_date'], Date::FORMAT_DAY_MONTH_YEAR) : LangLoader::get_message('common.never', 'common-lang')),
				new HTMLTableRowCell('<a href="' . Url::to_rel('/user/pm.php?pm=' . $row['user_id']) . '" class="button alt-button smaller offload">MP</a>')
			));
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('TABLE', $table->display());

		return $table->get_page_number();
	}

	private function check_authorizations()
	{
		if (!SandboxAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response($page = 1)
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['sandbox.table'], $this->lang['sandbox.module.title'], $page);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['sandbox.table'], SandboxUrlBuilder::table()->rel());

		return $response;
	}
}
?>
