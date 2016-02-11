<?php
/*##################################################
 *                              AdminSmileysListController.class.php
 *                            -------------------
 *   begin                : May 22, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class AdminSmileysListController extends AdminController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_table();
		
		return new AdminSmileysDisplayResponse($this->view, $this->lang['smiley_management']);
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
			$edit_link = new LinkHTMLElement(AdminSmileysUrlBuilder::edit($row['idsmiley']), '', array('title' => LangLoader::get_message('edit', 'common')), 'fa fa-edit');
			
			$delete_link = new LinkHTMLElement(AdminSmileysUrlBuilder::delete($row['idsmiley']), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'fa fa-delete');
			
			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell(new ImgHTMLElement(Url::to_rel('/images/smileys/') . $row['url_smiley'], array('id' => 'smiley-' . $row['idsmiley'] . '-img', 'alt' => ''))),
				new HTMLTableRowCell($code),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display())
			));
		}
		$table->set_rows(count($results), $results);
		
		$this->view->put('table', $table->display());
	}
}
?>
