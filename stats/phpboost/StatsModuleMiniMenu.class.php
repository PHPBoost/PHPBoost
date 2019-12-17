<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 04 13
 * @since       PHPBoost 3.0 - 2011 10 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

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
		return $LANG['stats.module.title'];
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
		MenuService::assign_positions_conditions($tpl, $this->get_block());

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
