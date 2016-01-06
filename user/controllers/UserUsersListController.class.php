<?php
/*##################################################
 *                      UserUsersListController.class.php
 *                            -------------------
 *   begin                : October 09, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class UserUsersListController extends AbstractController
{
	private $lang;
	private $view;
	private $groups_cache;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_select_group_form();
		$this->build_table();

		return $this->build_response($this->view);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('user-common');
		$this->view = new FileTemplate('user/UserUsersListController.tpl');
		$this->view->add_lang($this->lang);
		$this->groups_cache = GroupsCache::load();
	}

	private function build_table()
	{
		$table_model = new SQLHTMLTableModel(DB_TABLE_MEMBER, 'table', array(
			new HTMLTableColumn($this->lang['display_name'], 'display_name'),
			new HTMLTableColumn($this->lang['email']),
			new HTMLTableColumn($this->lang['registration_date'], 'registration_date'),
			new HTMLTableColumn($this->lang['messages'], 'posted_msg'),
			new HTMLTableColumn($this->lang['last_connection'], 'last_connection_date'),
			new HTMLTableColumn($this->lang['private_message'])
		), new HTMLTableSortingRule('display_name', HTMLTableSortingRule::ASC));

		$table = new HTMLTable($table_model);

		$results = array();
		$result = $table_model->get_sql_results();
		foreach ($result as $row)
		{
			$posted_msg = !empty($row['posted_msg']) ? $row['posted_msg'] : '0';
			$group_color = User::get_group_color($row['groups'], $row['level']);

			$author = new LinkHTMLElement(UserUrlBuilder::profile($row['user_id']), $row['display_name'], (!empty($group_color) ? array('style' => 'color: ' . $group_color) : array()), UserService::get_level_class($row['level']));

			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($row['show_email'] == 1 ? new LinkHTMLElement('mailto:' . $row['email'], $this->lang['email'], array(), 'basic-button smaller') : ''),
				new HTMLTableRowCell(Date::to_format($row['registration_date'], Date::FORMAT_DAY_MONTH_YEAR)),
				new HTMLTableRowCell($posted_msg),
				new HTMLTableRowCell(!empty($row['last_connection_date']) ? Date::to_format($row['last_connection_date'], Date::FORMAT_DAY_MONTH_YEAR) : LangLoader::get_message('never', 'main')),
				new HTMLTableRowCell(new LinkHTMLElement(UserUrlBuilder::personnal_message($row['user_id']), 'PM', array(), 'basic-button smaller')),
			));
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('table', $table->display());
	}
	
	private function build_select_group_form()
	{
		$form = new HTMLForm('groups', '', false);

		$fieldset = new FormFieldsetHorizontal('show_group');
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('groups_select', $this->lang['groups.select'] . ' : ', '', $this->build_select_groups(), 
			array('events' => array('change' => 'document.location = "'. UserUrlBuilder::groups()->rel() .'" + HTMLForms.getField("groups_select").getValue();')
		)));

		$groups = $this->groups_cache->get_groups();
		$this->view->put_all(array(
			'C_ARE_GROUPS' => !empty($groups),
			'SELECT_GROUP' => $form->display()
		));
	}
	
	private function build_select_groups()
	{
		$groups = array();
		$list_lang = LangLoader::get_message('list', 'main');
		$groups[] = new FormFieldSelectChoiceOption('-- '. $list_lang .' --', '');
		foreach ($this->groups_cache->get_groups() as $id => $row)
		{
			$groups[] = new FormFieldSelectChoiceOption($row['name'], $id);
		}
		return $groups;
	}

	private function build_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['users']);
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['users'], UserUrlBuilder::home()->rel());
		
		return $response;
	}
	
	public function get_right_controller_regarding_authorizations()
	{
		if (!AppContext::get_current_user()->check_auth(UserAccountsConfig::load()->get_auth_read_members(), UserAccountsConfig::AUTH_READ_MEMBERS_BIT))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		return $this;
	}
}
?>
