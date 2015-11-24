<?php
/*##################################################
 *                         AdminErrorsController404List.class.php
 *                            -------------------
 *   begin                : December 13 2009
 *   copyright            : (C) 2009 Loic Rouchon
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

class AdminErrorsController404List extends AdminController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $view;
	private $lang;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_table();
		
		return new AdminErrorsDisplayResponse($this->view, $this->lang['404_list']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-errors-common');
		$this->view = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM # # INCLUDE table #');
	}

	private function build_table()
	{
		$table_model = new SQLHTMLTableModel(PREFIX . 'errors_404', 'table', array(
			new HTMLTableColumn($this->lang['404_error_requested_url']),
			new HTMLTableColumn($this->lang['404_error_from_url']),
			new HTMLTableColumn($this->lang['404_error_times'], 'times', 'col-small'),
			new HTMLTableColumn(LangLoader::get_message('delete', 'common'), '', 'col-small')
		), new HTMLTableSortingRule('times', HTMLTableSortingRule::DESC));
		
		$table = new HTMLTable($table_model, 'table-fixed error-list404');
		
		$table_model->set_caption($this->lang['404_list']);
		
		$results = array();
		$result = $table_model->get_sql_results();
		foreach ($result as $row)
		{
			$delete_link = new LinkHTMLElement(AdminErrorsUrlBuilder::delete_404_error($row['id']), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'fa fa-delete');

			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell(new LinkHTMLElement($row['requested_url'], $row['requested_url'], array('title' => $this->lang['404_error_requested_url']))),
				new HTMLTableRowCell(new LinkHTMLElement($row['from_url'], $row['from_url'], array('title' => $this->lang['404_error_from_url']))),
				new HTMLTableRowCell($row['times']),
				new HTMLTableRowCell($delete_link->display())
			));
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		if ($table_model->get_number_of_matching_rows())
		{
			$this->build_form();

			$this->view->put('FORM', $this->form->display());

			$this->view->put('table', $table->display());
		}
		else
			$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('no_item_now', 'common'), MessageHelper::SUCCESS, 0, true));
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__, AdminErrorsUrlBuilder::clear_404_errors()->rel(), false);
		
		$fieldset = new FormFieldsetHTML('clear_errors', $this->lang['clear_list']);
		$form->add_fieldset($fieldset);

		$this->submit_button = new FormButtonSubmit($this->lang['clear_list'], 'clear', '', 'submit', $this->lang['logged_errors_clear_confirmation']);
		$form->add_button($this->submit_button);
		
		$this->form = $form;
	}
}
?>
