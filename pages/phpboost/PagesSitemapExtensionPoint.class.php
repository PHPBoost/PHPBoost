<?php
/*##################################################
 *                     PagesSitemapExtensionPoint.class.php
 *                            -------------------
 *   begin                : June 13, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class PagesSitemapExtensionPoint implements SitemapExtensionPoint
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
		global $LANG;
		
		include(PATH_TO_ROOT.'/pages/pages_defines.php');
		load_module_lang('pages');
		$pages_config = PagesConfig::load();
		$categories_cache = PagesCategoriesCache::load();
		$categories = $categories_cache->get_categories();
		
		//Configuration des authorisations
		$config_authorizations = $pages_config->get_authorizations();

		$pages_link = new SitemapLink($LANG['pages'], new Url('/pages/explorer.php'), Sitemap::FREQ_DEFAULT, Sitemap::PRIORITY_MAX);
		$module_map = new ModuleMap($pages_link, 'pages');

		$id_cat = 0;
		$keys = array_keys($categories);
		$num_cats = $categories_cache->get_number_categories();
		$properties = array();

		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $categories[$id];
			if ($auth_mode == Sitemap::AUTH_PUBLIC)
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, User::VISITOR_LEVEL, $properties['auth'], READ_PAGE) : Authorizations::check_auth(RANK_TYPE, User::VISITOR_LEVEL, $config_authorizations, READ_PAGE);
			}
			elseif ($auth_mode == Sitemap::AUTH_USER)
			{
				if (AppContext::get_current_user()->get_level() == User::ADMIN_LEVEL)
				$this_auth = true;
				else
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, AppContext::get_current_user()->get_level(), $properties['auth'], READ_PAGE) : Authorizations::check_auth(RANK_TYPE, AppContext::get_current_user()->get_level(), $config_authorizations, READ_PAGE);
			}
			if ($this_auth && $id != 0 && $properties['id_parent'] == $id_cat)
			{
				$module_map->add($this->create_module_map_sections($id, $auth_mode));
			}
		}

		return $module_map;
	}

	private function create_module_map_sections($id_cat, $auth_mode)
	{
		global $LANG;
		
		$pages_config = PagesConfig::load();
		$categories_cache = PagesCategoriesCache::load();
		$categories = $categories_cache->get_categories();
		
		//Configuration des authorisations
		$config_authorizations = $pages_config->get_authorizations();
		
		$this_category = new SitemapLink($categories[$id_cat]['title'], new Url('/pages/' . url('pages.php?title='.Url::encode_rewrite($categories[$id_cat]['title']), Url::encode_rewrite($categories[$id_cat]['title']))));
		
		$category = new SitemapSection($this_category);

		$i = 0;

		$keys = array_keys($categories);
		$num_cats = $categories_cache->get_number_categories();
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $categories[$id];
			if ($auth_mode == Sitemap::AUTH_PUBLIC)
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, User::VISITOR_LEVEL, $properties['auth'], READ_PAGE) : Authorizations::check_auth(RANK_TYPE, User::VISITOR_LEVEL, $config_authorizations, READ_PAGE);
			}
			elseif ($auth_mode == Sitemap::AUTH_USER)
			{
				if (AppContext::get_current_user()->get_level() == User::ADMIN_LEVEL)
				$this_auth = true;
				else
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, AppContext::get_current_user()->get_level(), $properties['auth'], READ_PAGE) : Authorizations::check_auth(RANK_TYPE, AppContext::get_current_user()->get_level(), $config_authorizations, READ_PAGE);
			}
			if ($this_auth && $id != 0 && $properties['id_parent'] == $id_cat)
			{
				$category->add($this->create_module_map_sections($id, $auth_mode));
				$i++;
			}
		}

		if ($i == 0	)
		$category = $this_category;

		return $category;
	}
}
?>