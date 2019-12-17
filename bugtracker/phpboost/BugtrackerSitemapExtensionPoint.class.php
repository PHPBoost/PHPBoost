<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 09
 * @since       PHPBoost 4.0 - 2014 01 22
*/

class BugtrackerSitemapExtensionPoint implements SitemapExtensionPoint
{
	public function get_public_sitemap()
	{
		return $this->get_module_map(Sitemap::AUTH_PUBLIC);
	}

	public function get_user_sitemap()
	{
		return $this->get_module_map(Sitemap::AUTH_USER);
	}

	private function get_module_map($auth_mode)
	{
		$lang = LangLoader::get('common', 'bugtracker');
		$config = BugtrackerConfig::load();
		$current_user = AppContext::get_current_user();

		$link = new SitemapLink($lang['module_title'], BugtrackerUrlBuilder::home(), Sitemap::FREQ_DEFAULT, Sitemap::PRIORITY_MAX);
		$module_map = new ModuleMap($link, 'bugtracker');

		if ($auth_mode == Sitemap::AUTH_PUBLIC)
		{
			$this_auth = Authorizations::check_auth(RANK_TYPE, User::VISITOR_LEVEL, $config->get_authorizations(), BugtrackerAuthorizationsService::READ_AUTHORIZATIONS);
		}
		else if ($auth_mode == Sitemap::AUTH_USER)
		{
			if ($current_user->get_level() == User::ADMIN_LEVEL)
				$this_auth = true;
			else
				$this_auth = Authorizations::check_auth(RANK_TYPE, $current_user->get_level(), $config->get_authorizations(), BugtrackerAuthorizationsService::READ_AUTHORIZATIONS);
		}

		if ($this_auth)
		{
			$module_map->add(new SitemapLink($lang['titles.unsolved'], BugtrackerUrlBuilder::unsolved()));
			$module_map->add(new SitemapLink($lang['titles.solved'], BugtrackerUrlBuilder::solved()));

			if ($config->is_roadmap_enabled() && $config->get_versions())
				$module_map->add(new SitemapLink($lang['titles.roadmap'], BugtrackerUrlBuilder::roadmap()));

			if ($config->are_stats_enabled())
				$module_map->add(new SitemapLink($lang['titles.stats'], BugtrackerUrlBuilder::stats()));
		}

		return $module_map;
	}
}
?>
