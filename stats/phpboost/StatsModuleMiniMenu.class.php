<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 21
 * @since       PHPBoost 3.0 - 2011 10 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
		return ModulesManager::get_module('stats')->get_configuration()->get_name();
	}

	public function is_displayed()
	{
		return StatsAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content()
	{
		$lang = LangLoader::get('common', 'stats');

		$view = new FileTemplate('stats/stats_mini.tpl');
		$view->add_lang($lang);
		MenuService::assign_positions_conditions($view, $this->get_block());

		$stats_cache = StatsCache::load();

		$group_color = User::get_group_color($stats_cache->get_stats_properties('last_member_groups'), $stats_cache->get_stats_properties('last_member_level'));

		$view->put_all(array(
			'C_SEVERAL_REGISTERED_USERS' => $stats_cache->get_stats_properties('nbr_members') > 1,
			'C_LAST_USER_GROUP_COLOR'    => !empty($group_color),

			'REGISTERED_USERS_NUMBER' => $stats_cache->get_stats_properties('nbr_members'),
			'LAST_USER_LEVEL_CLASS'   => UserService::get_level_class($stats_cache->get_stats_properties('last_member_level')),
			'LAST_USER_GROUP_COLOR'   => $group_color,
			'LAST_USER_DISPLAY_NAME'  => $stats_cache->get_stats_properties('last_member_login'),

			'U_LAST_USER_PROFILE' => UserUrlBuilder::profile($stats_cache->get_stats_properties('last_member_id'))->rel(),
		));

		return $view->render();
	}
}
?>
