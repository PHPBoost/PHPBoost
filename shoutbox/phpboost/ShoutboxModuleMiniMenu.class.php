<?php
/*##################################################
 *                          ShoutboxModuleMiniMenu.class.php
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

class ShoutboxModuleMiniMenu extends ModuleMiniMenu
{    
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}
	
	public function admin_display()
	{
		return '';
	}
	
	public function display($tpl = false)
	{
		//Mini Shoutbox non activée si sur la page archive shoutbox.
		if (!Url::is_current_url('/shoutbox/') && ShoutboxAuthorizationsService::check_authorizations()->read())
		{
			$tpl = new FileTemplate('shoutbox/ShoutboxModuleMiniMenu.tpl');
			MenuService::assign_positions_conditions($tpl, $this->get_block());
			
			$lang = LangLoader::get('common', 'shoutbox');
			$tpl->add_lang($lang);
			
			$config_shoutbox = ShoutboxConfig::load();
			
			$is_member = AppContext::get_current_user()->check_level(User::MEMBER_LEVEL);
			
			$tpl->put_all(array(
				'C_MEMBER' => $is_member,
				'SHOUTBOX_PSEUDO' => $is_member ? AppContext::get_current_user()->get_display_name() : LangLoader::get_message('guest', 'main'),
				'SHOUT_REFRESH_DELAY' => $config_shoutbox->get_refresh_delay(),
				'L_ALERT_LINK_FLOOD' => sprintf($lang['e_l_flood'], $config_shoutbox->get_max_links_number_per_message()),
				'SHOUTBOX_MESSAGES' => ShoutboxAjaxRefreshMessagesController::get_view()
			));
			
			return $tpl->render();
		}
		return '';
	}
}
?>