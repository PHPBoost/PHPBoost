<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 02
 * @since       PHPBoost 3.0 - 2009 12 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminErrorsController404List extends DefaultAdminController
{
	private $elements_number = 0;
	private $ids = array();
	private $table;

	public function execute(HTTPRequestCustom $request)
	{
		$current_page = $this->build_table();

		$this->execute_multiple_delete_if_needed($request);

		return new AdminErrorsDisplayResponse($this->view, $this->lang['admin.404.errors.list'], $current_page);
	}

	private function build_table()
	{
		$table_model = new SQLHTMLTableModel(PREFIX . 'errors_404', 'error-list404', array(
			new HTMLTableColumn($this->lang['admin.404.requested.url']),
			new HTMLTableColumn($this->lang['admin.404.from.url']),
			new HTMLTableColumn($this->lang['common.number'], 'times', array('css_class' => 'col-medium')),
			new HTMLTableColumn($this->lang['common.delete'], '', array('css_class' => 'col-small'))
		), new HTMLTableSortingRule('times', HTMLTableSortingRule::DESC));

		$this->table = new HTMLTable($table_model, $this->lang, 'error-list404');

		$table_model->set_caption($this->lang['admin.404.errors.list']);
		$table_model->set_footer_css_class('footer-error-list404');

		$results = array();
		$result = $table_model->get_sql_results();
		foreach ($result as $row)
		{
			$this->elements_number++;
			$this->ids[$this->elements_number] = $row['id'];

			$delete_link = new DeleteLinkHTMLElement(AdminErrorsUrlBuilder::delete_404_error($row['id']));

			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell(new LinkHTMLElement($row['requested_url'], $row['requested_url'])),
				new HTMLTableRowCell(new LinkHTMLElement($row['from_url'], $row['from_url'])),
				new HTMLTableRowCell($row['times']),
				new HTMLTableRowCell($delete_link->display())
			));
		}
		$this->table->set_rows($table_model->get_number_of_matching_rows(), $results);

		if ($table_model->get_number_of_matching_rows())
		{
			$this->build_form();

			$this->view = new StringTemplate('# INCLUDE FORM ## INCLUDE TABLE #');

			$this->view->put('FORM', $this->form->display());

			$this->view->put('TABLE', $this->table->display());
		}
		else
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['common.no.item.now'], MessageHelper::SUCCESS, 0, true));

		return $this->table->get_page_number();
	}

	private function execute_multiple_delete_if_needed(HTTPRequestCustom $request)
	{
		if ($request->get_string('delete-selected-elements', false))
		{
			for ($i = 1 ; $i <= $this->elements_number ; $i++)
			{
				if ($request->get_value('delete-checkbox-' . $i, 'off') == 'on')
				{
					if (isset($this->ids[$i]))
						AdminError404Service::delete_404_error($this->ids[$i]);
				}
			}
			AppContext::get_response()->redirect(AdminErrorsUrlBuilder::list_404_errors(), $this->lang['warning.process.success']);
		}
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__, AdminErrorsUrlBuilder::clear_404_errors()->rel(), false);

		$fieldset = new FormFieldsetHTML('clear_errors', '');
		$form->add_fieldset($fieldset);

		$this->submit_button = new FormButtonSubmit($this->lang['admin.clear.list'], 'clear', '', 'submit', $this->lang['admin.warning.clear.errors']);
		$form->add_button($this->submit_button);

		$this->form = $form;
	}
}
?>
