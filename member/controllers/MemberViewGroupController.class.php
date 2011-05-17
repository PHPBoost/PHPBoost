<?php
/*##################################################
 *                      MemberViewGroupController.class.php
 *                            -------------------
 *   begin                : May 17, 2011
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

class MemberViewGroupController extends AbstractController
{
	private $lang;
	private $group_id_selected;
	private $view;

	public function execute(HTTPRequest $request)
	{
		$this->group_id_selected = $request->get_getint('id', 0);
		$group_cache = GroupsCache::load();
		
		$this->init();
		
		if ($this->group_id_selected < 1)
		{
			AppContext::get_response()->redirect(DispatchManager::get_url('/member', '/member/')->absolute());
		}
		
		$groups = $group_cache->get_groups();
		if (!array_key_exists($this->group_id_selected, $groups))
		{
			AppContext::get_response()->redirect(DispatchManager::get_url('/member', '/member/')->absolute());
		}
		
		$group = $group_cache->get_group($this->group_id_selected);
		$id_members = explode('|', $group['members']);
		foreach ($id_members as $id)
		{
			if ($this->user_exist($id))
			{
				$row = PersistenceContext::get_sql()->query_array(DB_TABLE_MEMBER, '*', "WHERE user_aprob = 1 AND user_id = '" . $id . "' ");
				
				switch ($row['level'])
				{
					case 0:
						$status = $this->lang['member'];
					break;
					case 1:
						$status = $this->lang['modo'];
					break;
					case 2:
						$status = $this->lang['admin'];
					break;
				}
				
				$user_account_config = UserAccountsConfig::load();
				$this->view->assign_block_vars('members_list', array(
					'PSEUDO' => $row['login'],
					'AVATAR' => empty($row['user_avatar']) && $user_account_config->is_default_avatar_enabled() ? '<img class="valign_middle" src="'. PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/' .  $user_account_config->get_default_avatar_name() . '" alt="" />' : '<img class="valign_middle" src="' . $row['user_avatar'] . '" alt=""	/>',
					'STATUS' => $status
				));
			}
		}
			
		$group = $group_cache->get_group($this->group_id_selected);
		$group_name = $group['name'];
		
		$this->view->put_all(array(
			'ADMIN_GROUPS' => (AppContext::get_user()->check_level(ADMIN_LEVEL)) ? '<a href="'. PATH_TO_ROOT .'/admin/admin_groups.php?id=' . $this->group_id_selected . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" alt ="" class="valign_middle" /></a>' : '',
			'GROUP_NAME' => $group_name,
			'SELECT' => $this->build_form_select()->display()
		));

		return $this->build_response($this->view);
	}
	
	private function build_form_select()
	{
		$group_cache = GroupsCache::load();
		
		$form = new HTMLForm('groups');

		$fieldset = new FormFieldsetHorizontal('show_group');
		$form->add_fieldset($fieldset);
		
		$field = array();
		$field[] = new FormFieldSelectChoiceOption('-- '. $this->lang['list'] .' --', '0');
		foreach ($group_cache->get_groups() as $id => $row)
		{
			$field[] = new FormFieldSelectChoiceOption($row['name'], $id);
		}
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('groups_select', $this->lang['select_group'] .' : ', $this->group_id_selected, $field, array('events' => 
			array('change' => 'if (HTMLForms.getField("groups_select").getValue() > 0) {document.location = "'. DispatchManager::get_url('/member', '/group/')->absolute() .'" + HTMLForms.getField("groups_select").getValue();}')
		)));

		return $form;
	}

	private function init()
	{
		$this->lang = LangLoader::get('main');
		$this->view = new FileTemplate('member/MemberViewGroupController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$env = $response->get_graphical_environment();
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['member'], DispatchManager::get_url('/member', '/member/')->absolute());
		$breadcrumb->add($this->lang['groups'], DispatchManager::get_url('/member', '/group/')->absolute());
		$env->set_page_title($this->lang['groups']);
		return $response;
	}
	
	private function user_exist($user_id)
	{
		return PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, "WHERE user_aprob = 1 AND user_id = '" . $user_id . "'") > 0 ? true : false;
	}
}

?>