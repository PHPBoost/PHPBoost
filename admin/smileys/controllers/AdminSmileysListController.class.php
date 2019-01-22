<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 10 25
 * @since   	PHPBoost 4.1 - 2015 05 22
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class AdminSmileysListController extends AdminController
{
	private $lang;
	private $view;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$current_page = $this->build_table();

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
			$edit_link = new LinkHTMLElement(AdminSmileysUrlBuilder::edit($row['idsmiley']), '', array('title' => LangLoader::get_message('edit', 'common')), 'far fa-edit');

			$delete_link = new LinkHTMLElement(AdminSmileysUrlBuilder::delete($row['idsmiley']), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'fa fa-delete');

			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell(new ImgHTMLElement(Url::to_rel('/images/smileys/') . $row['url_smiley'], array('id' => 'smiley-' . $row['idsmiley'] . '-img', 'alt' => $row['idsmiley'], 'title' => $row['idsmiley']))),
				new HTMLTableRowCell($code),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display())
			));
		}
		$table->set_rows(count($results), $results);

		$this->view->put('table', $table->display());

		return $table->get_page_number();
	}
}
?>
