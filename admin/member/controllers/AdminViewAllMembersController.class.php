<?php
/*##################################################
 *                      AdminViewAllMembersController.class.php
 *                            -------------------
 *   begin                : February 28, 2010
 *   copyright            : (C) 2010 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
class AdminViewAllMembersController extends AdminController
{
	private $view;
	private $lang;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_table();
		
		return new AdminMembersDisplayResponse($this->view, LangLoader::get_message('members.members-management', 'admin-user-common'));
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('user-common');
		$this->view = new StringTemplate('# INCLUDE FORM # # INCLUDE table #');
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('search_member', LangLoader::get_message('search_member', 'main'));
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldAjaxSearchUserAutoComplete('member', $this->lang['display_name'], ''));
		
		return $form;
	}
	
	private function build_table()
	{
		$number_admins = UserService::count_admin_members();
		
		$table_model = new SQLHTMLTableModel(DB_TABLE_MEMBER, 'table', array(
			new HTMLTableColumn($this->lang['display_name'], 'display_name'),
			new HTMLTableColumn($this->lang['level'], 'level'),
			new HTMLTableColumn($this->lang['email']),
			new HTMLTableColumn($this->lang['registration_date'], 'registration_date'),
			new HTMLTableColumn($this->lang['last_connection'], 'last_connection_date'),
			new HTMLTableColumn($this->lang['approbation'], 'approved'),
			new HTMLTableColumn('')
		), new HTMLTableSortingRule('display_name', HTMLTableSortingRule::ASC));
		
		$table = new HTMLTable($table_model);
		
		$table_model->set_caption(LangLoader::get_message('members.members-management', 'admin-user-common'));
		
		$results = array();
		$result = $table_model->get_sql_results('m LEFT JOIN ' . DB_TABLE_INTERNAL_AUTHENTICATION .' ia ON ia.user_id = m.user_id', array('m.*', 'ia.approved', 'ia.login'));
		foreach ($result as $row)
		{
			$user = new User();
			$user->set_properties($row);
			$edit_link = new LinkHTMLElement(UserUrlBuilder::edit_profile($user->get_id()), '', array('title' => LangLoader::get_message('edit', 'common')), 'fa fa-edit');

			if ($user->get_level() != User::ADMIN_LEVEL || ($user->get_level() == User::ADMIN_LEVEL && $number_admins > 1))
				$delete_link = new LinkHTMLElement(AdminMembersUrlBuilder::delete($user->get_id()), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'fa fa-delete');
			else
				$delete_link = new LinkHTMLElement('', '', array('title' => LangLoader::get_message('delete', 'common'), 'onclick' => 'return false;'), 'fa fa-delete icon-disabled');

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			
			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell(new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level()))),
				new HTMLTableRowCell(UserService::get_level_lang($user->get_level())),
				new HTMLTableRowCell(new LinkHTMLElement('mailto:' . $user->get_email(), $this->lang['email'], array(), 'basic-button smaller')),
				new HTMLTableRowCell(Date::to_format($row['registration_date'], Date::FORMAT_DAY_MONTH_YEAR)),
				new HTMLTableRowCell(!empty($row['last_connection_date']) && $row['approved'] ? Date::to_format($row['last_connection_date'], Date::FORMAT_DAY_MONTH_YEAR) : LangLoader::get_message('never', 'main')),
				new HTMLTableRowCell(empty($row['login']) || $row['approved'] ? LangLoader::get_message('yes', 'common') : LangLoader::get_message('no', 'common')),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display())
			));
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put_all(array(
			'FORM' => $this->build_form()->display(),
			'table' => $table->display()
		));
	}
}
?>
