<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 28
 * @since       PHPBoost 3.0 - 2011 10 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class UserUsersListController extends AbstractController
{
	private $lang;
	private $view;
	private $groups_cache;
	
	private $elements_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_select_group_form();
		$current_page = $this->build_table();

		if (AppContext::get_current_user()->is_admin())
			$this->execute_multiple_delete_if_needed($request);
		
		return $this->generate_response($current_page);
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
		$number_admins = UserService::count_admin_members();

		$sql_html_table_model = array(
			new HTMLTableColumn($this->lang['display_name'], 'display_name'),
			new HTMLTableColumn($this->lang['email']),
			new HTMLTableColumn($this->lang['registration_date'], 'registration_date'),
			new HTMLTableColumn($this->lang['messages'], 'posted_msg'),
			new HTMLTableColumn($this->lang['last_connection'], 'last_connection_date'),
			new HTMLTableColumn($this->lang['private_message'])
		);

		if (AppContext::get_current_user()->is_admin())
			$sql_html_table_model[] = new HTMLTableColumn('');

		$table_model = new SQLHTMLTableModel(DB_TABLE_MEMBER, 'table', $sql_html_table_model, new HTMLTableSortingRule('display_name', HTMLTableSortingRule::ASC));

		$table = new HTMLTable($table_model);
		if (!AppContext::get_current_user()->is_admin())
			$table->hide_multiple_delete();

		$results = array();
		$result = $table_model->get_sql_results();
		foreach ($result as $row)
		{
			$this->elements_number++;
			$this->ids[$this->elements_number] = $row['user_id'];
			
			$posted_msg = !empty($row['posted_msg']) ? $row['posted_msg'] : '0';
			$group_color = User::get_group_color($row['groups'], $row['level']);

			$author = new LinkHTMLElement(UserUrlBuilder::profile($row['user_id']), $row['display_name'], (!empty($group_color) ? array('style' => 'color: ' . $group_color) : array()), UserService::get_level_class($row['level']));

			$html_table_row = array(
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($row['show_email'] == 1 ? new LinkHTMLElement('mailto:' . $row['email'], $this->lang['email'], array(), 'alt-button smaller') : ''),
				new HTMLTableRowCell(Date::to_format($row['registration_date'], Date::FORMAT_DAY_MONTH_YEAR)),
				new HTMLTableRowCell($posted_msg),
				new HTMLTableRowCell(!empty($row['last_connection_date']) ? Date::to_format($row['last_connection_date'], Date::FORMAT_DAY_MONTH_YEAR) : LangLoader::get_message('never', 'main')),
				new HTMLTableRowCell(new LinkHTMLElement(UserUrlBuilder::personnal_message($row['user_id']), 'PM', array(), 'alt-button smaller'))
			);

			if (AppContext::get_current_user()->is_admin())
			{
				$user = new User();
				$user->set_properties($row);
				$edit_link = new LinkHTMLElement(UserUrlBuilder::edit_profile($user->get_id()), '', array('aria-label' => LangLoader::get_message('edit', 'common')), 'fa fa-edit');

				if ($user->get_level() != User::ADMIN_LEVEL || ($user->get_level() == User::ADMIN_LEVEL && $number_admins > 1))
					$delete_link = new LinkHTMLElement(AdminMembersUrlBuilder::delete($user->get_id()), '', array('aria-label' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'fa fa-trash-alt');
				else
					$delete_link = new LinkHTMLElement('', '', array('aria-label' => LangLoader::get_message('delete', 'common'), 'onclick' => 'return false;'), 'fa fa-trash-alt icon-disabled');

				$html_table_row[] = new HTMLTableRowCell($edit_link->display() . $delete_link->display());
			}

			$results[] = new HTMLTableRow($html_table_row);
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

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
					{
						$user = UserService::get_user($this->ids[$i]);
						if (!$user->is_admin() || ($user->is_admin() && UserService::count_admin_members() > 1))
						{
							try
							{
								UserService::delete_by_id($user->get_id());
							}
							catch (RowNotFoundException $ex) {}
						}
					}
				}
			}
			AppContext::get_response()->redirect(UserUrlBuilder::home(), LangLoader::get_message('process.success', 'status-messages-common'));
		}
	}

	private function build_select_group_form()
	{
		$form = new HTMLForm('groups', '', false);

		$fieldset = new FormFieldsetHTML('show_group');
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

	private function generate_response($page = 1)
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['users'], '', $page);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['seo.user.list'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(UserUrlBuilder::home());

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
