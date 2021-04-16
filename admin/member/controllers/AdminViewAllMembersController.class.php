<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 16
 * @since       PHPBoost 3.0 - 2010 02 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminViewAllMembersController extends AdminController
{
	private $view;
	private $lang;

	private $elements_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();
		$current_page = $this->build_table();

		$this->execute_multiple_delete_if_needed($request);

		return new AdminMembersDisplayResponse($this->view, LangLoader::get_message('members.members-management', 'admin-user-common'), $current_page);
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

		$this->view->put('FORM', $form->display());
	}

	private function build_table()
	{
		$number_admins = UserService::count_admin_members();

		$table_model = new SQLHTMLTableModel(DB_TABLE_MEMBER, 'table', array(
			new HTMLTableColumn($this->lang['display_name'], 'display_name'),
			new HTMLTableColumn($this->lang['level'], 'level'),
			new HTMLTableColumn($this->lang['email'], 'email'),
			new HTMLTableColumn($this->lang['registration_date'], 'registration_date'),
			new HTMLTableColumn($this->lang['last_connection'], 'last_connection_date'),
			new HTMLTableColumn($this->lang['approbation'], 'approved'),
			new HTMLTableColumn(LangLoader::get_message('actions', 'admin-common'), '', array('sr-only' => true))
		), new HTMLTableSortingRule('display_name', HTMLTableSortingRule::ASC));

		$table_model->set_filters_menu_title(LangLoader::get_message('filter.members', 'admin-user-common'));
		$table_model->add_filter(new HTMLTableContainsTextSQLFilter('display_name', 'filter1', $this->lang['display_name']));
		$table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('level', 'filter2', $this->lang['level'], array(2 => LangLoader::get_message('admin_s', 'main'), 1 => LangLoader::get_message('modo_s', 'main'), 0 => LangLoader::get_message('member_s', 'main'))));
		$table_model->add_filter(new HTMLTableContainsTextSQLFilter('email', 'filter3', $this->lang['email']));
		$table_model->add_filter(new HTMLTableDateGreaterThanOrEqualsToSQLFilter('registration_date', 'filter4', $this->lang['registration_date'] . ' ' . TextHelper::lcfirst(LangLoader::get_message('minimum', 'common'))));
		$table_model->add_filter(new HTMLTableDateLessThanOrEqualsToSQLFilter('registration_date', 'filter5', $this->lang['registration_date'] . ' ' . TextHelper::lcfirst(LangLoader::get_message('maximum', 'common'))));
		$table_model->add_filter(new HTMLTableDateGreaterThanOrEqualsToSQLFilter('last_connection_date', 'filter6', $this->lang['last_connection'] . ' ' . TextHelper::lcfirst(LangLoader::get_message('minimum', 'common'))));
		$table_model->add_filter(new HTMLTableDateLessThanOrEqualsToSQLFilter('last_connection_date', 'filter7', $this->lang['last_connection'] . ' ' . TextHelper::lcfirst(LangLoader::get_message('maximum', 'common'))));
		$table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('approved', 'filter4', $this->lang['approbation'], array(1 => LangLoader::get_message('status.approved', 'common'), 0 => LangLoader::get_message('status.unapproved', 'common'))));

		$table = new HTMLTable($table_model);
		$table->set_filters_fieldset_class_HTML();

		$table_model->set_caption(LangLoader::get_message('members.members-management', 'admin-user-common'));

		$results = array();
		$result = $table_model->get_sql_results('m LEFT JOIN ' . DB_TABLE_INTERNAL_AUTHENTICATION .' ia ON ia.user_id = m.user_id', array('m.*', 'ia.approved', 'ia.login'));
		foreach ($result as $row)
		{
			$user = new User();
			$user->set_properties($row);

			$this->elements_number++;
			$this->ids[$this->elements_number] = $user->get_id();

			$edit_link = new EditLinkHTMLElement(UserUrlBuilder::edit_profile($user->get_id()));

			if ($user->get_level() != User::ADMIN_LEVEL || ($user->get_level() == User::ADMIN_LEVEL && $number_admins > 1))
				$delete_link = new DeleteLinkHTMLElement(AdminMembersUrlBuilder::delete($user->get_id()));
			else
				$delete_link = new DeleteLinkHTMLElement('', '', array('disabled' => true));

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell(new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level()))),
				new HTMLTableRowCell(UserService::get_level_lang($user->get_level())),
				new HTMLTableRowCell(new LinkHTMLElement('mailto:' . $user->get_email(), '<i class="fa fa-fw iboost fa-iboost-email"></i>', array('aria-label' => $this->lang['email']), 'button submit')),
				new HTMLTableRowCell(Date::to_format($row['registration_date'], Date::FORMAT_DAY_MONTH_YEAR)),
				new HTMLTableRowCell(!empty($row['last_connection_date']) && (empty($row['login']) || $row['approved']) ? Date::to_format($row['last_connection_date'], Date::FORMAT_DAY_MONTH_YEAR) : LangLoader::get_message('never', 'main')),
				new HTMLTableRowCell($user->is_banned() ? $this->lang['banned'] : (empty($row['login']) || $row['approved'] ? LangLoader::get_message('yes', 'common') : LangLoader::get_message('no', 'common'))),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display(), 'controls')
			));
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
				$last_admin_delete = false;
				$selected_users_number = 0;
				if ($request->get_value('delete-checkbox-' . $i, 'off') == 'on')
				{
					if (isset($this->ids[$i]))
					{
						$selected_users_number++;
						$user = UserService::get_user($this->ids[$i]);
						if (!$user->is_admin() || ($user->is_admin() && UserService::count_admin_members() > 1))
						{
							try
							{
								UserService::delete_by_id($user->get_id());
							}
							catch (RowNotFoundException $ex) {}
						}
						if ($user->is_admin() && UserService::count_admin_members() == 1)
							$last_admin_delete = true;
					}
				}
			}
			if ($last_admin_delete && $selected_users_number == 1)
				AppContext::get_response()->redirect(AdminMembersUrlBuilder::management(), LangLoader::get_message('error.action.unauthorized', 'status-messages-common'), MessageHelper::ERROR);
			else
				AppContext::get_response()->redirect(AdminMembersUrlBuilder::management(), LangLoader::get_message('process.success', 'status-messages-common'));
		}
	}
}
?>
