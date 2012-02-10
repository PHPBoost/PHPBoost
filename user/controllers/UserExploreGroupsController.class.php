<?php
/*##################################################
 *                      UserExploreGroupsController.class.php
 *                            -------------------
 *   begin                : October 09, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
	private $user_account_config;
	private $view;

	public function execute(HTTPRequest $request)
	{
		$group_id = $request->get_getint('id', 0);
		$this->init();
		
		if ($group_id !== 0 && !$this->groups_cache->group_exists($group_id))
		{
			AppContext::get_response()->redirect(UserUrlBuilder::home()->absolute());
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
				'U_ADMIN_GROUPS' => PATH_TO_ROOT .'/admin/admin_groups.php?id=' . $group_id,
				'GROUP_NAME' => $group['name']
			));
		}
		else
		{
			$this->view->put_all(array(
				'GROUP_NAME' => $this->lang['groups']
			));
		}
		
		$this->view->put_all(array(
			'SELECT_GROUP' => $this->build_form($group_id)->display(),
		));
		
		foreach ($this->get_members_group($group_id) as $user_id)
		{
			$user = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('*'), 'WHERE user_aprob = 1 AND user_id = :user_id', array('user_id' => $user_id));
			$this->view->assign_block_vars('members_list', array(
				'PROFILE_LINK' => UserUrlBuilder::profile($user_id)->absolute(),
				'PSEUDO' => $user['login'],
				'U_AVATAR' => empty($user['user_avatar']) && $this->user_account_config->is_default_avatar_enabled() ? 
					PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/' .  $this->user_account_config->get_default_avatar_name() : $user['user_avatar'],
				'STATUS' => UserService::get_level_lang($user['level'])
			));
		}
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
		$form = new HTMLForm('groups');

		$fieldset = new FormFieldsetHorizontal('show_group');
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('groups_select', $this->lang['groups.select'] . ' : ', $group_id_selected, $this->build_select_groups(), 
			array('events' => array('change' => 'document.location = "'. UserUrlBuilder::groups()->absolute() .'" + HTMLForms.getField("groups_select").getValue();')
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
		$this->user_account_config = UserAccountsConfig::load();
	}

	private function build_response()
	{
		$response = new UserDisplayResponse();
		$response->set_page_title($this->lang['groups']);
		$response->add_breadcrumb($this->lang['user'], UserUrlBuilder::users()->absolute());
		$response->add_breadcrumb($this->lang['groups'], UserUrlBuilder::groups()->absolute());
		return $response->display($this->view);
	}
}
?>