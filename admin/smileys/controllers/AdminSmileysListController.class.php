<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 28
 * @since       PHPBoost 4.1 - 2015 05 22
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class AdminSmileysListController extends AdminController
{
	private $lang;
	private $view;
	
	private $elements_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$current_page = $this->build_table();

		$this->execute_multiple_delete_if_needed($request);

		return new AdminSmileysDisplayResponse($this->view, $this->lang['smiley_management'], $current_page);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin');
		$this->view = new StringTemplate('# INCLUDE table #');
	}

	private function build_table()
	{
		$table_model = new HTMLTableModel('table', array(
			new HTMLTableColumn($this->lang['smiley']),
			new HTMLTableColumn(LangLoader::get_message('code', 'main')),
			new HTMLTableColumn('')
		), new HTMLTableSortingRule(''), HTMLTableModel::NO_PAGINATION);

		$table = new HTMLTable($table_model);

		$table_model->set_caption($this->lang['smiley_management']);

		$results = array();
		foreach(SmileysCache::load()->get_smileys() as $code => $row)
		{
			$this->elements_number++;
			$this->ids[$this->elements_number] = $row['idsmiley'];
			
			$edit_link = new LinkHTMLElement(AdminSmileysUrlBuilder::edit($row['idsmiley']), '', array('aria-label' => LangLoader::get_message('edit', 'common')), 'far fa-edit');

			$delete_link = new LinkHTMLElement(AdminSmileysUrlBuilder::delete($row['idsmiley']), '', array('aria-label' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'fa fa-trash-alt');

			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell(new ImgHTMLElement(Url::to_rel('/images/smileys/') . $row['url_smiley'], array('id' => 'smiley-' . $row['idsmiley'] . '-img', 'alt' => $row['idsmiley'], 'aria-label' => $row['idsmiley']))),
				new HTMLTableRowCell($code),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display())
			));
		}
		$table->set_rows(count($results), $results);

		$this->view->put('table', $table->display());

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
						PersistenceContext::get_querier()->delete(DB_TABLE_SMILEYS, 'WHERE idsmiley = :id', array('id' => $this->ids[$i]));
				}
			}
			SmileysCache::invalidate();
			AppContext::get_response()->redirect(AdminSmileysUrlBuilder::management(), LangLoader::get_message('process.success', 'status-messages-common'));
		}
	}
}
?>
