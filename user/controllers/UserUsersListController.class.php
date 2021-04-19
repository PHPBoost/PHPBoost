<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 19
 * @since       PHPBoost 3.0 - 2011 10 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UserUsersListController extends AbstractController
{
	private $lang;
	private $view;
	private $groups_cache;
	private $config;

	private $elements_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_view();

		if (AppContext::get_current_user()->is_admin())
			$this->execute_multiple_delete_if_needed($request);

		$this->view->put_all(array(
			'C_TABLE_VIEW' => $this->config->get_display_type() == UserAccountsConfig::TABLE_VIEW,
			'C_GRID_VIEW' => $this->config->get_display_type() == UserAccountsConfig::GRID_VIEW,
		));

		return $this->generate_response($request);
	}

	private function init()
	{
		$this->lang = LangLoader::get('user-lang');
		$this->view = new FileTemplate('user/UserUsersListController.tpl');
		$this->view->add_lang(array_merge($this->lang, LangLoader::get('common-lang')));
		$this->groups_cache = GroupsCache::load();
		$this->config = UserAccountsConfig::load();
	}

	private function build_view()
	{
		$result = PersistenceContext::get_querier()->select('SELECT member.*, mef.user_avatar, mef.user_website
		FROM ' . DB_TABLE_MEMBER . ' member
		LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' mef ON mef.user_id = member.user_id
		WHERE member.warning_percentage < 100
		ORDER BY member.display_name ASC');

		$this->view->put_all(array(
			'C_ENABLED_AVATAR' => $this->config->is_default_avatar_enabled(),
			'C_PAGINATION'     => $result->get_rows_count() > $this->config->get_items_per_page(),
			'C_HAS_GROUP'      => !empty(GroupsService::get_groups()),

			'USERS_NUMBER'   => $result->get_rows_count(),
			'ITEMS_PER_PAGE' => $this->config->get_items_per_page()
		));

		foreach (GroupsService::get_groups() as $group_id => $group_data)
		{
			if (is_numeric($group_id))
			{
				$this->view->assign_block_vars('groups', array(
					'GROUP_NAME' => $group_data['name'],
					'GROUP_NAME_FILTER' => Url::encode_rewrite(TextHelper::strtolower($group_data['name']))
				));
			}
		}

		while ($row = $result->fetch())
		{
			$modules = AppContext::get_extension_provider_service()->get_extension_point(UserExtensionPoint::EXTENSION_POINT);
			$contributions_number = 0;
			foreach ($modules as $module)
			{
				$contributions_number += $module->get_publications_number($row['user_id']);
			}

			$number_admins = UserService::count_admin_members();

			$this->view->assign_block_vars('users', array(
				'C_HAS_WEBSITE'   => $row['user_website'] != '',
				'C_ENABLED_EMAIL' => $row['show_email'],
				'C_IS_GROUP'	  => !empty(User::get_group_color($row['user_groups'], $row['level'])),
				'C_HAS_GROUP'	  => !empty(User::get_group_color($row['user_groups'])),
				'C_CONTROLS' 	  => $row['level'] >= 1,
				'C_DELETE'		  => $row['level'] != User::ADMIN_LEVEL || ($row['level'] == User::ADMIN_LEVEL && $number_admins > 1),

				'DISPLAYED_NAME'              => $row['display_name'],
				'REGISTRATION_DATE'           => Date::to_format($row['registration_date'], Date::FORMAT_DAY_MONTH_YEAR),
				'LAST_CONNECTION'             => !empty($row['last_connection_date']) ? Date::to_format($row['last_connection_date'], Date::FORMAT_DAY_MONTH_YEAR) : LangLoader::get_message('never', 'main'),
				'REGISTRATION_DATE_TIMESTAMP' => Date::to_format($row['registration_date'], Date::FORMAT_TIMESTAMP),
				'LAST_CONNECTION_TIMESTAMP'   => !empty($row['last_connection_date']) ? Date::to_format($row['last_connection_date'], Date::FORMAT_TIMESTAMP) : 0,
				'PUBLICATIONS_NUMBER'         => $contributions_number,
				'RANK_LEVEL'		          => UserService::get_level_lang($row['level']),

				'LEVEL_COLOR' => UserService::get_level_class($row['level']),
				'GROUP_COLOR' => User::get_group_color($row['user_groups'], $row['level']),

				'U_PROFILE'      => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_AVATAR'       => $row['user_avatar'] ? Url::to_rel($row['user_avatar']) : $this->config->get_default_avatar(),
				'U_WEBSITE'      => Url::to_rel($row['user_website']),
				'U_EMAIL'        => $row['email'],
				'U_MP'           => UserUrlBuilder::personnal_message($row['user_id'])->rel(),
				'U_PUBLICATIONS' => UserUrlBuilder::publications($row['user_id'])->rel(),
				'U_EDIT'	     => UserUrlBuilder::edit_profile($row['user_id'])->rel(),
				'U_DELETE'	     => AdminMembersUrlBuilder::delete($row['user_id'])->rel()
			));

			foreach ($modules as $module)
			{
				$this->view->assign_block_vars('users.modules', array(
					'MODULE_NAME' => $module->get_publications_module_name(),
					'MODULE_PUBLICATIONS_NUMBER' => $module->get_publications_number($row['user_id'])
				));
			}

			$user_groups = explode('|', $row['user_groups']);
			foreach (GroupsService::get_groups() as $group_id => $group_data)
			{
				if (is_numeric(array_search($group_id, $user_groups)))
				{
					$this->view->assign_block_vars('users.groups', array(
						'C_HAS_GROUP' => !empty($row['user_groups']),
						'GROUP_NAME' => $group_data['name'],
						'GROUP_COLOR' => User::get_group_color($group_id),
						'GROUP_NAME_FILTER' => Url::encode_rewrite(TextHelper::strtolower($group_data['name']))
					));
				}
			}
		}
		$result->dispose();
	}

	private function execute_multiple_delete_if_needed(HTTPRequestCustom $request)
	{
		if ($request->get_string('delete-selected-elements', false))
		{
			$last_admin_delete = false;
			$selected_users_number = 0;
			for ($i = 1 ; $i <= $this->elements_number ; $i++)
			{
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
				AppContext::get_response()->redirect(UserUrlBuilder::home(), LangLoader::get_message('error.action.unauthorized', 'status-messages-common'), MessageHelper::ERROR);
			else
				AppContext::get_response()->redirect(UserUrlBuilder::home(), LangLoader::get_message('process.success', 'status-messages-common'));
		}
	}
	private function generate_response(HTTPRequestCustom $request)
	{
		$response = new SiteDisplayResponse($this->view);

		$sort_field = $request->get_getstring('field', 'display_name');
		$sort_mode = $request->get_getstring('sort', 'DESC');
		$page = $request->get_getint('page', 1);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['user.users'], '', $page);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['user.seo.list'], $sort_field, $sort_mode, $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(UserUrlBuilder::home());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['user.users'], UserUrlBuilder::home()->rel());

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
