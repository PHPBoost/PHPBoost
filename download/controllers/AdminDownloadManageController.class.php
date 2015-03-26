<?php
/*##################################################
 *                      AdminDownloadManageController.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */

class AdminDownloadManageController extends AdminModuleController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_table();
		
		return new AdminDownloadDisplayResponse($this->view, $this->lang['download.management']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'download');
		$this->view = new StringTemplate('# INCLUDE table #');
	}
	
	private function build_table()
	{
		$table_model = new SQLHTMLTableModel(DownloadSetup::$download_table, array(
				new HTMLTableColumn(''),
				new HTMLTableColumn(LangLoader::get_message('form.name', 'common'), 'name'),
				new HTMLTableColumn(LangLoader::get_message('category', 'categories-common'), 'id_category'),
				new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'display_name'),
				new HTMLTableColumn(LangLoader::get_message('form.date.creation', 'common'), 'creation_date'),
				new HTMLTableColumn(LangLoader::get_message('status', 'common'), 'approbation_type'),
			), new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));
		
		$table = new HTMLTable($table_model);
		
		$table_model->set_caption($this->lang['download.management']);
		
		$results = array();
		$result = $table_model->get_sql_results('download
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = download.id AND com.module_id = \'download\'
			LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = download.id AND notes.module_name = \'download\'
			LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = download.id AND note.module_name = \'download\' AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = download.author_user_id'
		);
		foreach ($result as $row)
		{
			$downloadfile = new DownloadFile();
			$downloadfile->set_properties($row);
			$category = $downloadfile->get_category();
			$user = $downloadfile->get_author_user();

			$edit_link = new LinkHTMLElement(DownloadUrlBuilder::edit($downloadfile->get_id(), SITE_REWRITED_SCRIPT), '', array('title' => LangLoader::get_message('edit', 'common')), 'fa fa-edit');
			$delete_link = new LinkHTMLElement(DownloadUrlBuilder::delete($downloadfile->get_id(), SITE_REWRITED_SCRIPT), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'fa fa-delete');

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('color' => $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell($edit_link->display() . $delete_link->display()),
				new HTMLTableRowCell(new LinkHTMLElement(DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $downloadfile->get_id(), $downloadfile->get_rewrited_name()), $downloadfile->get_name()), 'left'),
				new HTMLTableRowCell(new LinkHTMLElement(DownloadUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), $category->get_name())),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($downloadfile->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($downloadfile->get_status()),
			));
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('table', $table->display());
	}
}
?>
