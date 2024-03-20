<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 03 20
 * @since       PHPBoost 3.0 - 2011 10 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class UserUsersListController extends AbstractController
{
	private $lang;
	private $view;
	private $elements_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();
		$current_page = $this->build_table();

		$this->execute_multiple_delete_if_needed($request);

		return $this->generate_response($current_page);
	}

	private function init()
	{
		$this->lang = LangLoader::get_all_langs();
		$this->view = new StringTemplate('# INCLUDE FORM # # INCLUDE TABLE #');
		$this->view->add_lang($this->lang);
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
			new HTMLTableColumn($this->lang['user.registration.date'], 'registration_date'),
			new HTMLTableColumn($this->lang['user.last.connection'], 'last_connection_date'),
			new HTMLTableColumn($this->lang['user.rank'], 'level'),
			new HTMLTableColumn($this->lang['user.email']),
			new HTMLTableColumn($this->lang['user.contact']),
			new HTMLTableColumn($this->lang['user.publications']),
			new HTMLTableColumn($this->lang['common.moderation'], '', array('sr-only' => true))
		), new HTMLTableSortingRule('last_connection_date', HTMLTableSortingRule::DESC));

		$table_model->set_filters_menu_title($this->lang['user.filter.members']);
		$table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('level', 'filter2', $this->lang['user.rank'], array(2 => $this->lang['user.administrators'], 1 => $this->lang['user.moderators'], 0 => $this->lang['user.members'])));
		$table_model->add_filter(new HTMLTableContainsTextSQLFilter('email', 'filter3', $this->lang['user.email']));
		$table_model->add_filter(new HTMLTableDateGreaterThanOrEqualsToSQLFilter('registration_date', 'filter4', $this->lang['user.registration.date'] . ' ' . TextHelper::lcfirst($this->lang['common.minimum'])));
		$table_model->add_filter(new HTMLTableDateLessThanOrEqualsToSQLFilter('registration_date', 'filter5', $this->lang['user.registration.date'] . ' ' . TextHelper::lcfirst($this->lang['common.maximum'])));
		$table_model->add_filter(new HTMLTableDateGreaterThanOrEqualsToSQLFilter('last_connection_date', 'filter6', $this->lang['user.last.connection'] . ' ' . TextHelper::lcfirst($this->lang['common.minimum'])));
		$table_model->add_filter(new HTMLTableDateLessThanOrEqualsToSQLFilter('last_connection_date', 'filter7', $this->lang['user.last.connection'] . ' ' . TextHelper::lcfirst($this->lang['common.maximum'])));
		
		$table = new HTMLTable($table_model);
		$table->set_filters_fieldset_class_HTML();

		$table_model->set_layout_title($this->lang['user.members.management']);

		$results = array();
		$result = $table_model->get_sql_results('m LEFT JOIN ' . DB_TABLE_INTERNAL_AUTHENTICATION .' ia ON ia.user_id = m.user_id', array('m.*', 'ia.approved', 'ia.login'));
		foreach ($result as $row)
		{
			$user = new User();
			$user->set_properties($row);

			$this->elements_number++;
			$this->ids[$this->elements_number] = $user->get_id();

			if (AppContext::get_current_user()->get_level() == User::ADMINISTRATOR_LEVEL || (AppContext::get_current_user()->get_level() == User::ADMINISTRATOR_LEVEL && $user->get_level() == User::ADMINISTRATOR_LEVEL && $number_admins > 1) || AppContext::get_current_user()->get_id() == $user->get_id())
            {
                $edit_link = new EditLinkHTMLElement(UserUrlBuilder::edit_profile($user->get_id()));
				$delete_link = new DeleteLinkHTMLElement(AdminMembersUrlBuilder::delete($user->get_id()));
            }
			else
            {
                $table->hide_multiple_delete();
				$edit_link = new EditLinkHTMLElement('', '', array('aria-label' => '', 'disabled' => true), 'd-none');
				$delete_link = new DeleteLinkHTMLElement('', '', array('aria-label' => '', 'disabled' => true), 'd-none');
            }

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

			$modules = AppContext::get_extension_provider_service()->get_extension_point(UserExtensionPoint::EXTENSION_POINT);
			$contributions_number = 0;
			foreach ($modules as $module)
			{
				$contributions_number += $module->get_publications_number($row['user_id']);
			}

			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell(new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level()))),
				new HTMLTableRowCell(Date::to_format($row['registration_date'], Date::FORMAT_DAY_MONTH_YEAR)),
				new HTMLTableRowCell(!empty($row['last_connection_date']) && (empty($row['login']) || $row['approved']) ? Date::to_format($row['last_connection_date'], Date::FORMAT_DAY_MONTH_YEAR) : $this->lang['common.never']),
				new HTMLTableRowCell(UserService::get_level_lang($user->get_level())),
				new HTMLTableRowCell((int)$user->get_show_email() ? new LinkHTMLElement('mailto:' . $user->get_email(), '<i class="fa fa-fw iboost fa-iboost-email"></i>', array('aria-label' => $this->lang['user.email']), '') : ''),
				new HTMLTableRowCell(new LinkHTMLElement(UserUrlBuilder::personnal_message($row['user_id']), '<i class="fa fa-fw fa-people-arrows"></i>', array('aria-label' => $this->lang['user.private.message']), '')),
				new HTMLTableRowCell(new LinkHTMLElement(UserUrlBuilder::publications($row['user_id']), $contributions_number)),
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
				AppContext::get_response()->redirect(UserUrlBuilder::home(), $this->lang['warning.unauthorized.action'], MessageHelper::ERROR);
			else
				AppContext::get_response()->redirect(UserUrlBuilder::home(), $this->lang['warning.process.success']);
		}
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

	private function generate_response($page = 1)
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['user.users'], '', $page);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['user.seo.list'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(UserUrlBuilder::home());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['user.users'], UserUrlBuilder::home()->rel());

		return $response;
	}
}
?>
