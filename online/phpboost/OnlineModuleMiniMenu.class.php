<?php
/*##################################################
 *                          OnlineModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : October 08, 2011
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

class OnlineModuleMiniMenu extends ModuleMiniMenu
{
	private $number_visitor = 0;
	private $number_member = 0;
	private $number_moderator = 0;
	private $number_administrator = 0;
	private $total_users = 0;
	
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}
	
	public function get_menu_id()
	{
		return 'module-mini-online';
	}
	
	public function get_menu_title()
	{
		return LangLoader::get_message('online', 'common', 'online');
	}
	
	public function is_displayed()
	{
		return !Url::is_current_url('/online/') && OnlineAuthorizationsService::check_authorizations()->read();
	}
	
	public function get_menu_content()
	{
		$tpl = new FileTemplate('online/OnlineModuleMiniMenu.tpl');
		
		$lang = LangLoader::get('common', 'online');
		$tpl->add_lang($lang);
		
		$online_config = OnlineConfig::load();
		$condition = 'WHERE s.timestamp > :time ORDER BY '. $online_config->get_display_order_request();
		$parameters = array(
			'time' => (time() - SessionsConfig::load()->get_active_session_duration())
		);
		
		$users = OnlineService::get_online_users($condition, $parameters);
		foreach ($users as $user)
		{
			$this->incremente_level($user);
			
			if ($this->total_users <= $online_config->get_number_member_displayed())
			{
				$group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
				
				if ($user->get_level() != User::VISITOR_LEVEL) 
				{
					$tpl->assign_block_vars('users', array(
						'U_PROFILE' => UserUrlBuilder::profile($user->get_id())->rel(),
						'PSEUDO' => $user->get_display_name(),
						'LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
						'C_GROUP_COLOR' => !empty($group_color),
						'GROUP_COLOR' => $group_color,
					));
				}
			}
		}
		
		$main_lang = LangLoader::get('main');
		$tpl->put_all(array(
			'C_MORE_USERS' => $this->total_users > $online_config->get_number_member_displayed(),
			'L_VISITOR' => $this->number_visitor > 1 ? $main_lang['guest_s'] : $main_lang['guest'],
			'L_MEMBER' => $this->number_member > 1 ? $main_lang['member_s'] : $main_lang['member'],
			'L_MODO' => $this->number_moderator > 1 ? $main_lang['modo_s'] : $main_lang['modo'],
			'L_ADMIN' => $this->number_administrator > 1 ? $main_lang['admin_s'] : $main_lang['admin'],
			'L_USERS_ONLINE' => $this->total_users > 1 ? $lang['online_users'] : $lang['online_user'],
			'L_TOTAL' => $main_lang['total'],
			'TOTAL_USERS_CONNECTED' => $this->total_users,
			'TOTAL_VISITOR_CONNECTED' => $this->number_visitor,
			'TOTAL_MEMBER_CONNECTED' => $this->number_member,
			'TOTAL_MODERATOR_CONNECTED' => $this->number_moderator,
			'TOTAL_ADMINISTRATOR_CONNECTED' => $this->number_administrator
		));
		
		return $tpl->render();
	}
	
	private function incremente_level(User $user)
	{
		$this->incremente_user();
		switch ($user->get_level())
		{
			case User::VISITOR_LEVEL:
				$this->incremente_visitor();
			break;
			case User::MEMBER_LEVEL:
				$this->incremente_member();
			break;
			case User::MODERATOR_LEVEL:
				$this->incremente_moderator();
			break;
			case User::ADMIN_LEVEL:
				$this->incremente_administrator();
			break;
		}
	}
	
	private function incremente_user()
	{
		$this->total_users++;
	}
	
	private function incremente_visitor()
	{
		$this->number_visitor++;
	}
	
	private function incremente_member()
	{
		$this->number_member++;
	}
	
	private function incremente_moderator()
	{
		$this->number_moderator++;
	}
	
	private function incremente_administrator()
	{
		$this->number_administrator++;
	}
}
?>
