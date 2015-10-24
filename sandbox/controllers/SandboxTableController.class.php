<?php
/*##################################################
 *                          SandboxTableController.class.php
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

class SandboxTableController extends ModuleController
{
	private $view;
	private $lang;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$table = $this->build_table();
		$this->view->put('table', $table->display());
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'sandbox');
		$this->view = new StringTemplate('# INCLUDE table #');
	}
	
	private function build_table()
	{
		$table = new SQLHTMLTableModel(DB_TABLE_MEMBER, __CLASS__, array(
			new HTMLTableColumn('pseudo', 'display_name'),
			new HTMLTableColumn('email'),
			new HTMLTableColumn('inscrit le', 'registration_date'),
			new HTMLTableColumn('messages'),
			new HTMLTableColumn('derniere connexion'),
			new HTMLTableColumn('messagerie')
		), new HTMLTableSortingRule('user_id', HTMLTableSortingRule::ASC));

		$table->set_caption('Liste des membres');
		
		$options = array('horn' => 'Horn', 'coucou' => 'Coucou', 'teston' => 'teston');
		$table->add_filter(new HTMLTableEqualsFromListSQLFilter('display_name', 'filter1', 'login Equals', $options));
        $table->add_filter(new HTMLTableBeginsWithTextSQLFilter('display_name', 'filter2', 'login Begins with (regex)', '`^(?!%).+$`'));
        $table->add_filter(new HTMLTableBeginsWithTextSQLFilter('display_name', 'filter3', 'login Begins with (no regex)'));
        $table->add_filter(new HTMLTableEndsWithTextSQLFilter('display_name', 'filter4', 'login Ends with (regex)', '`^(?!%).+$`'));
        $table->add_filter(new HTMLTableEndsWithTextSQLFilter('display_name', 'filter5', 'login Ends with (no regex)'));
        $table->add_filter(new HTMLTableLikeTextSQLFilter('display_name', 'filter6', 'login Like (regex)', '`^toto`'));
        $table->add_filter(new HTMLTableLikeTextSQLFilter('display_name', 'filter7', 'login Like (no regex)'));
        $table->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter8', 'id >'));
        $table->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter9', 'id > (lower=3)', 3));
        $table->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter10', 'id > (upper=3)', HTMLTableNumberComparatorSQLFilter::NOT_BOUNDED, 3));
        $table->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter11', 'id > (lower=1, upper=3)', 1, 3));
        $table->add_filter(new HTMLTableLessThanSQLFilter('user_id', 'filter12', 'id <'));
        $table->add_filter(new HTMLTableGreaterThanOrEqualsToSQLFilter('user_id', 'filter13', 'id >='));
        $table->add_filter(new HTMLTableLessThanOrEqualsToSQLFilter('user_id', 'filter14', 'id <='));
        $table->add_filter(new HTMLTableEqualsToSQLFilter('user_id', 'filter15', 'id ='));
		
		$html_table = new HTMLTable($table);

        $results = array();
		$result = $table->get_sql_results();
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
		$html_table->set_rows($table->get_number_of_matching_rows(), $results);

		return $html_table;
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['title.table_builder'], $this->lang['module_title']);
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['title.table_builder'], SandboxUrlBuilder::table()->rel());
		
		return $response;
	}
}
?>
