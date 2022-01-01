<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 04
 * @since       PHPBoost 4.1 - 2015 05 22
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminSmileysListController extends DefaultAdminController
{
	private $elements_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		$current_page = $this->build_table();

		$this->execute_multiple_delete_if_needed($request);

		return new AdminSmileysDisplayResponse($this->view, $this->lang['admin.smileys.management'], $current_page);
	}

	private function build_table()
	{
		$table_model = new HTMLTableModel('table', array(
			new HTMLTableColumn($this->lang['admin.smiley']),
			new HTMLTableColumn($this->lang['admin.code']),
			new HTMLTableColumn($this->lang['common.moderation'], '', array('sr-only' => true))
		), new HTMLTableSortingRule(''), HTMLTableModel::NO_PAGINATION);

		$table = new HTMLTable($table_model);

		$table_model->set_caption($this->lang['admin.smileys.management']);

		$results = array();
		foreach(SmileysCache::load()->get_smileys() as $code => $row)
		{
			$this->elements_number++;
			$this->ids[$this->elements_number] = $row['idsmiley'];

			$edit_link = new EditLinkHTMLElement(AdminSmileysUrlBuilder::edit($row['idsmiley']));

			$delete_link = new DeleteLinkHTMLElement(AdminSmileysUrlBuilder::delete($row['idsmiley']));

			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell(new ImgHTMLElement(Url::to_rel('/images/smileys/') . $row['url_smiley'], array('id' => 'smiley-' . $row['idsmiley'] . '-img', 'alt' => $row['idsmiley'], 'aria-label' => $row['idsmiley']))),
				new HTMLTableRowCell($code),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display(), 'controls')
			));
		}
		$table->set_rows(count($results), $results);

		$this->view->put('CONTENT', $table->display());

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
			AppContext::get_response()->redirect(AdminSmileysUrlBuilder::management(), $this->lang['warning.process.success']);
		}
	}
}
?>
