<?php
/*##################################################
 *                      AdminFaqManageController.class.php
 *                            -------------------
 *   begin                : September 2, 2014
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

class AdminFaqManageController extends AdminModuleController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_table();
		
		return new AdminFaqDisplayResponse($this->view, $this->lang['faq.management']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'faq');
		$this->view = new StringTemplate('# INCLUDE table #');
	}
	
	private function build_table()
	{
		$table_model = new SQLHTMLTableModel(FaqSetup::$faq_table, 'AdminTable', array(
			new HTMLTableColumn($this->lang['faq.form.question'], 'question'),
			new HTMLTableColumn(LangLoader::get_message('category', 'categories-common'), 'id_category'),
			new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'display_name'),
			new HTMLTableColumn(LangLoader::get_message('form.date.creation', 'common'), 'creation_date'),
			new HTMLTableColumn(LangLoader::get_message('status.approved', 'common'), 'approved'),
			new HTMLTableColumn('')
		), new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));
		
		$table = new HTMLTable($table_model);
		
		$table_model->set_caption($this->lang['faq.management']);
		
		$results = array();
		$result = $table_model->get_sql_results('faq LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = faq.author_user_id');
		foreach ($result as $row)
		{
			$faq_question = new FaqQuestion();
			$faq_question->set_properties($row);
			$category = $faq_question->get_category();
			$user = $faq_question->get_author_user();

			$edit_link = new LinkHTMLElement(FaqUrlBuilder::edit($faq_question->get_id()), '', array('title' => LangLoader::get_message('edit', 'common')), 'fa fa-edit');
			$delete_link = new LinkHTMLElement(FaqUrlBuilder::delete($faq_question->get_id()), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'fa fa-delete');

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell(new LinkHTMLElement(FaqUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $faq_question->get_id()), $faq_question->get_question()), 'left'),
				new HTMLTableRowCell(new LinkHTMLElement(FaqUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), $category->get_name())),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($faq_question->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($faq_question->is_approved() ? LangLoader::get_message('yes', 'common') : LangLoader::get_message('no', 'common')),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display())
			));
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('table', $table->display());
	}
}
?>
