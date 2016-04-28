<?php
/*##################################################
 *                      UserExploreGroupsController.class.php
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

class UserExploreGroupsController extends AbstractController
{
	private $lang;
	private $groups_cache;
	private $view;

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
			$group = $this->groups_cache->get_group($group_id);
			$this->view->put_all(array(
				'C_ADMIN' => AppContext::get_current_user()->check_level(User::ADMIN_LEVEL),
				'U_ADMIN_GROUPS' => TPL_PATH_TO_ROOT .'/admin/admin_groups.php?id=' . $group_id,
				'GROUP_NAME' => $group['name']
			));
		}
		else
		{
			$this->view->put_all(array(
				'GROUP_NAME' => $this->lang['groups']
			));
		}
		
		$user_accounts_config = UserAccountsConfig::load();
		$number_member = 0;
		foreach ($this->get_members_group($group_id) as $user_id)
		{
			if (!empty($user_id))
			{
				$user = PersistenceContext::get_querier()->select('SELECT 
					member.display_name, member.level, member.groups, member.warning_percentage, member.delay_banned, ext_field.user_avatar
					FROM ' . DB_TABLE_MEMBER . ' member
					LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = member.user_id
					WHERE member.user_id = :user_id
				', array('user_id' => $user_id))->fetch();

				if (!empty($user))
				{
					//Avatar
					$user_avatar = !empty($user['user_avatar']) ? Url::to_rel($user['user_avatar']) : ($user_accounts_config->is_default_avatar_enabled() ? Url::to_rel('/templates/' . AppContext::get_current_user()->get_theme() . '/images/' .  $user_accounts_config->get_default_avatar_name()) : '');
					
					$group_color = User::get_group_color($user['groups'], $user['level']);
					$this->view->assign_block_vars('members_list', array(
						'C_AVATAR' => $user['user_avatar'] || ($user_accounts_config->is_default_avatar_enabled()),
						'C_GROUP_COLOR' => !empty($group_color),
						'PSEUDO' => $user['display_name'],
						'LEVEL' => ($user['warning_percentage'] < '100' || (time() - $user['delay_banned']) < 0) ? UserService::get_level_lang($user['level']) : $this->lang['banned'],
						'LEVEL_CLASS' => UserService::get_level_class($user['level']),
						'GROUP_COLOR' => $group_color,
						'U_PROFILE' => UserUrlBuilder::profile($user_id)->rel(),
						'U_AVATAR' => $user_avatar
					));
					$number_member++;
				}
			}
		}

		$this->view->put_all(array(
			'C_NOT_MEMBERS' => $number_member == 0,
			'SELECT_GROUP' => $this->build_form($group_id)->display(),
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
	
	private function build_form($group_id_selected)
	{
		$form = new HTMLForm('groups', '', false);

		$fieldset = new FormFieldsetHorizontal('show_group');
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('groups_select', $this->lang['groups.select'] . ' : ', $group_id_selected, $this->build_select_groups(), 
			array('events' => array('change' => 'document.location = "'. UserUrlBuilder::groups()->rel() .'" + HTMLForms.getField("groups_select").getValue();')
		)));

		return $form;
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
	
	private function get_all_members()
	{
		$members = array();
		foreach ($this->groups_cache->get_groups() as $groups)
		{
			foreach ($groups['members'] as $user_id)
			{
				if (!in_array($user_id, $members))
				{
					$members[] = $user_id;
				}
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