<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 11
 * @since       PHPBoost 3.0 - 2010 02 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminViewAllMembersController extends DefaultAdminController
{
	private $elements_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();
		$current_page = $this->build_table();

		$this->execute_multiple_delete_if_needed($request);

		return new AdminMembersDisplayResponse($this->view, $this->lang['user.members.management'], $current_page);
	}

	private function init()
	{
		$this->view = new StringTemplate('# INCLUDE FORM # # INCLUDE TABLE #');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('search_member', $this->lang['user.search.member']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldAjaxSearchUserAutoComplete('member', $this->lang['user.display.name'], '',
			array('description' => $this->lang['user.search.joker'])
		));

		$this->view->put('FORM', $form->display());
	}

	private function build_table()
	{
		$number_admins = UserService::count_admin_members();

		$table_model = new SQLHTMLTableModel(DB_TABLE_MEMBER, 'table', array(
			new HTMLTableColumn($this->lang['user.display.name'], 'display_name'),
			new HTMLTableColumn($this->lang['user.rank'], 'level'),
			new HTMLTableColumn($this->lang['user.email'], 'email'),
			new HTMLTableColumn($this->lang['user.registration.date'], 'registration_date'),
			new HTMLTableColumn($this->lang['user.last.connection'], 'last_connection_date'),
			new HTMLTableColumn($this->lang['user.approbation'], 'approved'),
			new HTMLTableColumn($this->lang['user.caution'], 'warning_percentage'),
			new HTMLTableColumn($this->lang['common.moderation'], '', array('sr-only' => true))
		), new HTMLTableSortingRule('display_name', HTMLTableSortingRule::ASC));

		$table_model->set_filters_menu_title($this->lang['user.filter.members']);
		$table_model->add_filter(new HTMLTableContainsTextSQLFilter('display_name', 'filter1', $this->lang['user.display.name']));
		$table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('level', 'filter2', $this->lang['user.rank'], array(2 => $this->lang['user.administrators'], 1 => $this->lang['user.moderators'], 0 => $this->lang['user.members'])));
		$table_model->add_filter(new HTMLTableContainsTextSQLFilter('email', 'filter3', $this->lang['user.email']));
		$table_model->add_filter(new HTMLTableDateGreaterThanOrEqualsToSQLFilter('registration_date', 'filter4', $this->lang['user.registration.date'] . ' ' . TextHelper::lcfirst($this->lang['common.minimum'])));
		$table_model->add_filter(new HTMLTableDateLessThanOrEqualsToSQLFilter('registration_date', 'filter5', $this->lang['user.registration.date'] . ' ' . TextHelper::lcfirst($this->lang['common.maximum'])));
		$table_model->add_filter(new HTMLTableDateGreaterThanOrEqualsToSQLFilter('last_connection_date', 'filter6', $this->lang['user.last.connection'] . ' ' . TextHelper::lcfirst($this->lang['common.minimum'])));
		$table_model->add_filter(new HTMLTableDateLessThanOrEqualsToSQLFilter('last_connection_date', 'filter7', $this->lang['user.last.connection'] . ' ' . TextHelper::lcfirst($this->lang['common.maximum'])));
		$table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('approved', 'filter8', $this->lang['user.approbation'], array(1 => $this->lang['common.status.approved'], 0 => $this->lang['common.status.unapproved'])));

		$table = new HTMLTable($table_model);
		$table->set_filters_fieldset_class_HTML();

		$table_model->set_caption($this->lang['user.members.management']);

		$results = array();
		$result = $table_model->get_sql_results('m LEFT JOIN ' . DB_TABLE_INTERNAL_AUTHENTICATION .' ia ON ia.user_id = m.user_id', array('m.*', 'ia.approved', 'ia.login'));
		foreach ($result as $row)
		{
			$user = new User();
			$user->set_properties($row);

			$this->elements_number++;
			$this->ids[$this->elements_number] = $user->get_id();

			$edit_link = new EditLinkHTMLElement(UserUrlBuilder::edit_profile($user->get_id()));

			if ($user->get_level() != User::ADMINISTRATOR_LEVEL || ($user->get_level() == User::ADMINISTRATOR_LEVEL && $number_admins > 1))
				$delete_link = new DeleteLinkHTMLElement(AdminMembersUrlBuilder::delete($user->get_id()));
			else
				$delete_link = new DeleteLinkHTMLElement('', '', array('disabled' => true));

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell(new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level()))),
				new HTMLTableRowCell(UserService::get_level_lang($user->get_level())),
				new HTMLTableRowCell(new LinkHTMLElement('mailto:' . $user->get_email(), '<i class="fa fa-fw iboost fa-iboost-email"></i>', array('aria-label' => $this->lang['user.email']), 'button submit')),
				new HTMLTableRowCell(Date::to_format($row['registration_date'], Date::FORMAT_DAY_MONTH_YEAR)),
				new HTMLTableRowCell(!empty($row['last_connection_date']) && (empty($row['login']) || $row['approved']) ? Date::to_format($row['last_connection_date'], Date::FORMAT_DAY_MONTH_YEAR) : $this->lang['common.never']),
				new HTMLTableRowCell(empty($row['login']) || $row['approved'] ? $this->lang['common.yes'] : $this->lang['common.no']),
				new HTMLTableRowCell($user->get_warning_percentage() . '%' . ($user->is_banned() ? '<br />' . $this->lang['user.banned'] : '') . ($user->is_readonly() ? '<br />' . $this->lang['user.read.only'] : '')),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display(), 'controls')
			));
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('TABLE', $table->display());

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
							HooksService::execute_hook_action('delete_user', 'user', array_merge($user->get_properties(), array('title' => $user->get_display_name())));
						}
						if ($user->is_admin() && UserService::count_admin_members() == 1)
							$last_admin_delete = true;
					}
				}
			}
			if ($last_admin_delete && $selected_users_number == 1)
				AppContext::get_response()->redirect(AdminMembersUrlBuilder::management(), $this->lang['warning.unauthorized.action'], MessageHelper::ERROR);
			else
				AppContext::get_response()->redirect(AdminMembersUrlBuilder::management(), $this->lang['warning.process.success']);
		}
	}
}
?>
