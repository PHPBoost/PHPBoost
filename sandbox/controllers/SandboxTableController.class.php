<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 10 30
 * @since   	PHPBoost 3.0 - 2009 12 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxTableController extends ModuleController
{
	private $view;
	private $lang;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$current_page = $this->build_table();

		return $this->generate_response($current_page);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'sandbox');
		$this->view = new FileTemplate('sandbox/SandboxTableController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_table()
	{
		$table_model = new SQLHTMLTableModel(DB_TABLE_MEMBER, 'table', array(
			new HTMLTableColumn('pseudo', 'display_name'),
			new HTMLTableColumn('email'),
			new HTMLTableColumn('inscrit le', 'registration_date'),
			new HTMLTableColumn('messages'),
			new HTMLTableColumn('derniere connexion'),
			new HTMLTableColumn('messagerie')
		), new HTMLTableSortingRule('user_id', HTMLTableSortingRule::ASC));


		$table_model->set_caption('Liste des membres');

		$options = array('horn' => 'Horn', 'coucou' => 'Coucou', 'teston' => 'teston');
		$table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('display_name', 'filter1', 'login Equals', $options));
		$table_model->add_filter(new HTMLTableBeginsWithTextSQLFilter('display_name', 'filter2', 'login Begins with (regex)', '`^(?!%).+$`u'));
		$table_model->add_filter(new HTMLTableBeginsWithTextSQLFilter('display_name', 'filter3', 'login Begins with (no regex)'));
		$table_model->add_filter(new HTMLTableEndsWithTextSQLFilter('display_name', 'filter4', 'login Ends with (regex)', '`^(?!%).+$`u'));
		$table_model->add_filter(new HTMLTableEndsWithTextSQLFilter('display_name', 'filter5', 'login Ends with (no regex)'));
		$table_model->add_filter(new HTMLTableLikeTextSQLFilter('display_name', 'filter6', 'login Like (regex)', '`^toto`u'));
		$table_model->add_filter(new HTMLTableLikeTextSQLFilter('display_name', 'filter7', 'login Like (no regex)'));
		$table_model->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter8', 'id >'));
		$table_model->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter9', 'id > (lower=3)', 3));
		$table_model->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter10', 'id > (upper=3)', HTMLTableNumberComparatorSQLFilter::NOT_BOUNDED, 3));
		$table_model->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter11', 'id > (lower=1, upper=3)', 1, 3));
		$table_model->add_filter(new HTMLTableLessThanSQLFilter('user_id', 'filter12', 'id <'));
		$table_model->add_filter(new HTMLTableGreaterThanOrEqualsToSQLFilter('user_id', 'filter13', 'id >='));
		$table_model->add_filter(new HTMLTableLessThanOrEqualsToSQLFilter('user_id', 'filter14', 'id <='));
		$table_model->add_filter(new HTMLTableEqualsToSQLFilter('user_id', 'filter15', 'id ='));

		$table = new HTMLTable($table_model);

		$results = array();
		$result = $table_model->get_sql_results();
		foreach ($result as $row)
		{
			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell($row['display_name']),
				new HTMLTableRowCell(($row['show_email'] == 1) ? '<a href="mailto:' . $row['email'] . '" class="basic-button smaller">Mail</a>' : '&nbsp;'),
				new HTMLTableRowCell(Date::to_format($row['registration_date'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell(!empty($row['posted_msg']) ? $row['posted_msg'] : '0'),
				new HTMLTableRowCell(!empty($row['last_connection_date']) ? Date::to_format($row['last_connection_date'], Date::FORMAT_DAY_MONTH_YEAR) : LangLoader::get_message('never', 'main')),
				new HTMLTableRowCell('<a href="' . Url::to_rel('/user/pm.php?pm=' . $row['user_id']) . '" class="basic-button smaller">MP</a>')
			));
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('table', $table->display());

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
		$graphical_environment->set_page_title($this->lang['title.table.builder'], $this->lang['module.title'], $page);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['title.table.builder'], SandboxUrlBuilder::table()->rel());

		return $response;
	}
}
?>
