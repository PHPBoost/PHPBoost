<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 16
 * @since       PHPBoost 3.0 - 2011 10 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class UserExploreGroupsController extends AbstractController
{
	private $lang;
	private $groups_cache;
	private $view;
	private $extended_fields_number;

	public function execute(HTTPRequestCustom $request)
	{
		$group_id = $request->get_getint('id', 0);

		$this->init();

		if ($group_id !== 0 && !$this->groups_cache->group_exists($group_id))
		{
			AppContext::get_response()->redirect(UserUrlBuilder::home());
		}

		$this->build_view($group_id);

		return $this->build_response();
	}

	private function build_view($group_id)
	{

		if (!empty($group_id))
		{
			//Affichage d'un seul groupe sur la page
			$group = $this->groups_cache->get_group($group_id);

			$users_data = "";
			$number_member = 0;
			$group_users_id = "";

			foreach ($this->get_members_group($group_id) as $user_id)
			{
				if (!empty($user_id))
				{
					if ($number_member != 0)
						$group_users_id .=  ',' . $user_id;
					else
						$group_users_id .= $user_id;
					$number_member++;
				}
			}

			if (!empty($group_users_id))
				$this->display_group_user($group_users_id, 'members_list');

			$this->view->put_all(array(
				'C_ONE_GROUP'    => true,
				'C_NO_MEMBERS'   => $number_member == 0,
				'U_GROUP_LIST'   => UserUrlBuilder::groups()->rel(),
				'C_ADMIN'        => AppContext::get_current_user()->check_level(User::ADMIN_LEVEL),
				'U_ADMIN_GROUPS' => TPL_PATH_TO_ROOT .'/admin/admin_groups.php?id=' . $group_id,
				'GROUP_NAME'     => $group['name'],
				'NUMBER_MEMBERS' => $number_member,
			));

		}
		else
		{
			//Affichage de tous les groupes + admin + modos sur la même page
			//Affichages des administrateurs et des modérateurs
			$users_data = PersistenceContext::get_querier()->select('SELECT
				member.user_id, member.display_name, member.level, member.groups, member.warning_percentage, member.delay_banned, ext_field.user_avatar
				FROM ' . DB_TABLE_MEMBER . ' member
				LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = member.user_id
				WHERE member.level IN (1,2)
			');
			$number_modos  = 0;
			$number_admins = 0;

			foreach ($users_data as $key => $user)
			{
				if (!empty($user))
				{
					if ($user['level'] == 1)
					{
						$this->display_data($user, 'modos_list');
						$number_modos++;
					}
					if ($user['level'] == 2){
						$this->display_data($user, 'admins_list');
						$number_admins++;
					}
				}
			}

			//Affichages de tous les groupes
			foreach ($this->groups_cache->get_groups() as $key => $group)
			{
				//Récupération du nombre de membre dans le groupe
				$users_data = "";
				$number_member = 0;
				$group_users_id = "";

				foreach ($this->get_members_group($key) as $user_id)
				{
					if (!empty($user_id))
					{
						if ($number_member != 0)
							$group_users_id .=  ',' . $user_id;
						else
							$group_users_id .= $user_id;
						$number_member++;
					}
				}

				// Affichage des groupes pour selection
				$group_color = User::get_group_color($key);
				$this->view->assign_block_vars('group', array(
					'GROUP_ID'        => $key,
					'GROUP_NAME'      => $group['name'],
					'U_GROUP'         => UserUrlBuilder::groups()->rel() . $key,
					'C_GROUP_COLOR'   => !empty($group_color),
					'GROUP_COLOR'     => $group_color,
					'C_GROUP_HAS_IMG' => !empty($group['img']),
					'U_GROUP_IMG'     => Url::to_rel('/images/group/' . $group['img']),
					'C_HAS_MEMBERS'   => $number_member > 0,
					'NUMBER_MEMBERS'  => $number_member,
					'C_ADMIN'         => AppContext::get_current_user()->check_level(User::ADMIN_LEVEL),
					'U_ADMIN_GROUPS'  => TPL_PATH_TO_ROOT .'/admin/admin_groups.php?id=' . $group_id,
				));

				// Affichage des membres des groupes
				if (!empty($group_users_id))
					$this->display_group_user($group_users_id, 'group.group_members_list');
			}

			$this->view->put_all(array(
				'C_ONE_GROUP'   => false,
				'C_HAS_ADMINS'  => $number_admins > 0,
				'NUMBER_ADMINS' => $number_admins,
				'C_HAS_MODOS'   => $number_modos > 0,
				'NUMBER_MODOS'  => $number_modos,
				'C_HAS_GROUP'   => !empty($this->groups_cache->get_groups())
			));
		}
	}

	private function display_group_user($group_users_id, $list)
	{
		if (!empty($group_users_id))
		{
			$users_data = PersistenceContext::get_querier()->select('SELECT
				member.user_id, member.display_name, member.level, member.groups, member.warning_percentage, member.delay_banned, ext_field.user_avatar
				FROM ' . DB_TABLE_MEMBER . ' member
				LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = member.user_id
				WHERE member.user_id IN (' . $group_users_id . ')
			');

			if (!empty($users_data))
			{
				foreach ($users_data as $key => $user)
				{
					if (!empty($user))
						$this->display_data($user, $list);
				}
			}
		}
	}

	private function display_data($user, $list_name)
	{
		$user_accounts_config = UserAccountsConfig::load();

		//Avatar
		$user_avatar = !empty($user['user_avatar']) ? Url::to_rel($user['user_avatar']) : ($user_accounts_config->is_default_avatar_enabled() ? Url::to_rel('/templates/' . AppContext::get_current_user()->get_theme() . '/images/' .  $user_accounts_config->get_default_avatar_name()) : '');

		$group_color = User::get_group_color($user['groups'], $user['level']);
		$this->view->assign_block_vars($list_name, array(
			'C_AVATAR'          => $user['user_avatar'] || ($user_accounts_config->is_default_avatar_enabled()),
			'C_GROUP_COLOR'     => !empty($group_color),
			'PSEUDO'            => $user['display_name'],
			'LEVEL'             => ($user['warning_percentage'] < '100' || (time() - $user['delay_banned']) < 0) ? UserService::get_level_lang($user['level']) : $this->lang['banned'],
			'LEVEL_CLASS'       => UserService::get_level_class($user['level']),
			'GROUP_COLOR'       => $group_color,
			'U_PROFILE'         => UserUrlBuilder::profile($user['user_id'])->rel(),
			'U_AVATAR'          => $user_avatar
		));

		foreach (MemberExtendedFieldsService::display_profile_fields($user['user_id']) as $field)
		{
			if ($field['name'] != 'Avatar')
			{
				$this->view->assign_block_vars($list_name . '.extended_fields', array(
					'NAME' => $field['name'],
					'REWRITED_NAME' => Url::encode_rewrite($field['name']),
					'VALUE' => $field['value']
				));
			}
			$this->extended_fields_number++;
		}

		$this->view->put_all(array(
			'C_EXTENDED_FIELDS' => $this->extended_fields_number
		));
	}

	private function get_members_group($group_id_selected)
	{
		if (empty($group_id_selected))
		{
			return $this->get_all_members();
		}
		$group = $this->groups_cache->get_group($group_id_selected);
		return $group['members'];
	}

	private function get_all_members()
	{
		$members = array();
		foreach ($this->groups_cache->get_groups() as $groups)
		{
			foreach ($groups['members'] as $user_id)
			{
				if (!in_array($user_id, $members))
					$members[] = $user_id;
			}
		}
		return $members;
	}

	private function init()
	{
		$this->lang = LangLoader::get('user-common');
		$this->view = new FileTemplate('user/UserExploreGroupsController.tpl');
		$this->view->add_lang($this->lang);
		$this->groups_cache = GroupsCache::load();
	}

	private function build_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['groups'], $this->lang['user']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['seo.user.groups']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(UserUrlBuilder::groups());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['users'], UserUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['groups'], UserUrlBuilder::groups()->rel());

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
