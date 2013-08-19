<?php
/*##################################################
 *                           GuestbookModuleHomePage.class.php
 *                            -------------------
 *   begin                : November 12, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class GuestbookModuleHomePage implements ModuleHomePage
{
	private $lang;
	private $view;
	
	public static function get_view()
	{
		$object = new self();
		return $object->build_view();
	}
	
	private function build_view()
	{
		$this->init();
		
		$this->check_authorizations();
		
		$messages_number = GuestbookService::count();
		$pagination = $this->get_pagination($messages_number);
		$main_lang = LangLoader::get('main');
		$page = AppContext::get_request()->get_getint('page', 1);
		$is_guest = !AppContext::get_current_user()->check_level(User::MEMBER_LEVEL);
		
		$this->view->put_all(array(
			'C_ADD' => GuestbookAuthorizationsService::check_authorizations()->write(),
			'C_PAGINATION' => $messages_number > GuestbookConfig::load()->get_items_per_page(),
			'PAGINATION' => $pagination->display(),
			'L_EDIT' => $main_lang['edit'],
			'L_DELETE' => $main_lang['delete'],
			'L_ON' => $main_lang['on'],
			'L_GUEST' => $main_lang['guest'],
			'L_GROUP' => $main_lang['group'],
			'FORM' => GuestbookFormController::get_view(),
			'U_ADD' => GuestbookUrlBuilder::add($page)->rel(),
		));
		
		$result = PersistenceContext::get_querier()->select("SELECT g.id, g.login, g.contents, g.timestamp, m.user_id, m.login as mlogin, m.level, m.user_groups, ext_field.user_avatar
		FROM " . PREFIX . "guestbook g
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = g.user_id
		GROUP BY g.id
		ORDER BY g.timestamp DESC
		LIMIT :number_items_per_page OFFSET :display_from",
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			)
		);
		
		while ($row = $result->fetch())
		{
			$user_accounts_config = UserAccountsConfig::load();
			$user_group_color = User::get_group_color($row['user_groups'], $row['level']);
			
			$this->view->assign_block_vars('messages', array(
				'C_MODERATOR' => GuestbookAuthorizationsService::check_authorizations()->moderation() || GuestbookAuthorizationsService::check_authorizations()->write() && $row['user_id'] == AppContext::get_current_user()->get_id() && !$is_guest,
				'C_AVATAR' => $row['user_avatar'] || ($user_accounts_config->is_default_avatar_enabled() && !empty($row['user_id'])),
				'C_USER_GROUPS' => !empty($row['user_groups']),
				'C_USER_GROUP_COLOR' => !empty($user_group_color),
				'C_VISITOR' => empty($row['user_id']),
				'ID' => $row['id'],
				'CONTENTS' => FormatingHelper::second_parse($row['contents']),
				'DATE' => gmdate_format('date_format', $row['timestamp']),
				'USER_PSEUDO' => $row['mlogin'] ? $row['mlogin'] : $row['login'],
				'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'USER_GROUP_COLOR' => $user_group_color,
				'U_ANCHOR' => GuestbookUrlBuilder::home($page, $row['id'])->absolute(),
				'U_AVATAR' => $row['user_avatar'] ? Url::to_rel($row['user_avatar']) : PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' .  $user_accounts_config->get_default_avatar_name(),
				'U_EDIT' => GuestbookUrlBuilder::edit($row['id'], $page)->rel(),
				'U_DELETE' => GuestbookUrlBuilder::delete($row['id'], $page)->rel(),
				'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->absolute(),
			));
			
			//user's groups
			if (!empty($row['user_groups']))
			{
				$groups_cache = GroupsCache::load();
				$user_groups = explode('|', $row['user_groups']);
				foreach ($user_groups as $user_group_id)
				{
					if ($groups_cache->group_exists($user_group_id))
					{
						$group = $groups_cache->get_group($user_group_id);
						$this->view->assign_block_vars('messages.user_groups', array(
							'C_GROUP_PICTURE' => !empty($group['img']),
							'GROUP_PICTURE' => $group['img'],
							'GROUP_NAME' => $group['name']
						));
					}
				}
			}
		}
		
		return $this->view;
	}
	
	private function init()
	{
		$this->current_user = AppContext::get_current_user();
		
		$this->lang = LangLoader::get('common', 'guestbook');
		$this->view = new FileTemplate('guestbook/GuestbookController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function check_authorizations()
	{
		if (!GuestbookAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function get_pagination($messages_number)
	{
		$page = AppContext::get_request()->get_getint('page', 1);
		
		$pagination = new ModulePagination($page, $messages_number, (int)GuestbookConfig::load()->get_items_per_page());
		$pagination->set_url(GuestbookUrlBuilder::home('%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
}
?>