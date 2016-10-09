<?php
/*##################################################
 *                     BugtrackerSitemapExtensionPoint.class.php
 *                            -------------------
 *   begin                : January 22, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
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