<?php
/*##################################################
 *                          StatsModuleMiniMenu.class.php
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

class StatsModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}
	
	public function get_menu_id()
	{
		return 'module-mini-stats';
	}
	
	public function get_menu_title()
	{
		global $LANG;
		load_module_lang('stats');
		return $LANG['stats'];
	}
	
	public function is_displayed()
	{
		return StatsAuthorizationsService::check_authorizations()->read();
	}
	
	public function get_menu_content()
	{
		global $LANG;
		//Chargement de la langue du module.
		load_module_lang('stats');
		
		$tpl = new FileTemplate('stats/stats_mini.tpl');
		
		$stats_cache = StatsCache::load();
		$l_member_registered = ($stats_cache->get_stats_properties('nbr_members') > 1) ? $LANG['member_registered_s'] : $LANG['member_registered'];
		
		$group_color = User::get_group_color($stats_cache->get_stats_properties('last_member_groups'), $stats_cache->get_stats_properties('last_member_level'));
		
		$tpl->put_all(array(
			'L_MORE_STAT' => $LANG['more_stats'],
			'L_USER_REGISTERED' => sprintf($l_member_registered, $stats_cache->get_stats_properties('nbr_members')),
			'L_LAST_REGISTERED_USER' => $LANG['last_member'],
			'U_LINK_LAST_USER' => '<a href="' . UserUrlBuilder::profile($stats_cache->get_stats_properties('last_member_id'))->rel() . '" class="' . UserService::get_level_class($stats_cache->get_stats_properties('last_member_level')) . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $stats_cache->get_stats_properties('last_member_login') . '</a>'
		));
		
		return $tpl->render();
	}
}
?>