<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 20
 * @since       PHPBoost 3.0 - 2009 12 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

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

	private $elements_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$current_page = $this->build_table();

		$this->execute_multiple_delete_if_needed($request);

		return new AdminErrorsDisplayResponse($this->view, $this->lang['404_list'], $current_page);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-errors-common');
		$this->view = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM # # INCLUDE table #');
	}

	private function build_table()
	{
		$table_model = new SQLHTMLTableModel(PREFIX . 'errors_404', 'error-list404', array(
			new HTMLTableColumn($this->lang['404_error_requested_url']),
			new HTMLTableColumn($this->lang['404_error_from_url']),
			new HTMLTableColumn($this->lang['404_error_times'], 'times', 'col-large'),
			new HTMLTableColumn(LangLoader::get_message('delete', 'common'), '', 'col-small')
		), new HTMLTableSortingRule('times', HTMLTableSortingRule::DESC));

		$table = new HTMLTable($table_model, 'error-list404');

		$table_model->set_caption($this->lang['404_list']);
		$table_model->set_footer_css_class('footer-error-list404');

		$results = array();
		$result = $table_model->get_sql_results();
		foreach ($result as $row)
		{
			$this->elements_number++;
			$this->ids[$this->elements_number] = $row['id'];

			$delete_link = new LinkHTMLElement(AdminErrorsUrlBuilder::delete_404_error($row['id']), '<i class="far fa-fw fa-trash-alt"></i>', array('aria-label' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), '');

			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell(new LinkHTMLElement($row['requested_url'], $row['requested_url'])),
				new HTMLTableRowCell(new LinkHTMLElement($row['from_url'], $row['from_url'])),
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

		return $table->get_page_number();
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
			AppContext::get_response()->redirect(AdminErrorsUrlBuilder::list_404_errors(), LangLoader::get_message('process.success', 'status-messages-common'));
		}
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
