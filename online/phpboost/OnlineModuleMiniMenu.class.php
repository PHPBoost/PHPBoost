<?php
/*##################################################
 *                          OnlineModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : October 08, 2011
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

class OnlineModuleMiniMenu extends ModuleMiniMenu
{    
    public function get_default_block()
    {
    	return self::BLOCK_POSITION__RIGHT;
    }

	public function display($tpl = false)
    {
		if (!Url::is_current_url('/online/index.php'))
	    {
			$tpl = new FileTemplate('online/OnlineModuleMiniMenu.tpl');
			MenuService::assign_positions_conditions($tpl, $this->get_block());
			
			$lang = LangLoader::get('online_common', 'online');
			
			//On compte les visiteurs en ligne dans la bdd, en prenant en compte le temps max de connexion.
			list($count_visit, $count_member, $count_modo, $count_admin) = array(0, 0, 0, 0);
			
			$i = 0;
			$array_class = array('member', 'modo', 'admin');
			
			$online_user_list = OnlineService::get_online_users("WHERE s.session_time > ':time' ORDER BY :display_order", array('time' => (time() - SessionsConfig::load()->get_active_session_duration()), 'display_order' => OnlineConfig::load()->get_display_order_request()));
			
			foreach ($online_user_list as $user)
			{
				if ($i < OnlineConfig::load()->get_number_member_displayed())
				{
					//Visiteurs non pris en compte.
					if ($user->get_level() !== '-1')
					{
						$group_color = User::get_group_color(implode('|', $user->get_groups()), $user->get_level());
						$tpl->assign_block_vars('online', array(
							'USER' => '<a href="'. UserUrlBuilder::profile($user->get_id())->absolute() .'" class="' . UserService::get_level_lang($user->get_level()) . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . TextHelper::wordwrap_html($user->get_pseudo(), 19) . '</a><br />'
						));
						$i++;
					}
				}
				
				switch ($user->get_level())
				{
					case '-1':
					$count_visit++;
					break;
					case '0':
					$count_member++;
					break;
					case '1':
					$count_modo++;
					break;
					case '2':
					$count_admin++;
					break;
				}
			}
			
			$count_visit = (empty($count_visit) && empty($count_member) && empty($count_modo) && empty($count_admin)) ? '1' : $count_visit;
			$total_members = $count_member + $count_modo + $count_admin;
			
			$lang_member_s = LangLoader::get_message('member_s', 'main');
			$member_online = $lang_member_s . ' ' . strtolower($lang['online']);

			$tpl->put_all(array(
				'VISIT' => $count_visit,
				'MEMBER' => $count_member,
				'MODO' => $count_modo,
				'ADMIN' => $count_admin,
				'MORE' => ($total_members > OnlineConfig::load()->get_number_member_displayed()) ? '<br /><a href="' . OnlineUrlBuilder::home()->absolute() . '" title="' . $member_online . '">' . $member_online . '</a><br />' : '', //Plus de 4 membres connectés.
				'TOTAL' => $count_visit + $total_members,
				'L_VISITOR' => ($count_visit > 1) ? LangLoader::get_message('guest_s', 'main') : LangLoader::get_message('guest', 'main'),
				'L_MEMBER' => ($count_member > 1) ? $lang_member_s : LangLoader::get_message('member', 'main'),
				'L_MODO' => ($count_modo > 1) ? LangLoader::get_message('modo_s', 'main') : LangLoader::get_message('modo', 'main'),
				'L_ADMIN' => ($count_admin > 1) ? LangLoader::get_message('admin_s', 'main') : LangLoader::get_message('admin', 'main'),
				'L_ONLINE' => $lang['online'],
				'L_TOTAL' => LangLoader::get_message('total', 'main')
			));
		
			return $tpl->render();    
	    }
	
	    return '';
    } 
}
?>