<?php
/*##################################################
 *                     NewsSitemapExtensionPoint.class.php
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

class NewsSitemapExtensionPoint implements SitemapExtensionPoint
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
		global $NEWS_CAT, $NEWS_LANG, $LANG, $User, $NEWS_CONFIG, $Cache;

		require_once PATH_TO_ROOT . '/news/news_begin.php';

		$news_link = new SitemapLink($NEWS_LANG['news'], new Url('/news/news.php'), Sitemap::FREQ_DAILY, Sitemap::PRIORITY_MAX);

		$module_map = new ModuleMap($news_link, 'news');

		$id_cat = 0;
		$keys = array_keys($NEWS_CAT);
		$num_cats = count($NEWS_CAT);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $NEWS_CAT[$id];

			if ($auth_mode == Sitemap::AUTH_PUBLIC)
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['auth'], AUTH_NEWS_READ) : Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $NEWS_CONFIG['global_auth'], AUTH_NEWS_READ);
			}
			else
			{
				$this_auth = is_array($properties['auth']) ? $User->check_auth($properties['auth'], AUTH_NEWS_READ) : $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_READ);
			}

			if ($this_auth && $id != 0 && $properties['visible'] && $properties['id_parent'] == $id_cat)
			{
				$module_map->add($this->create_module_map_sections($id, $auth_mode));
			}
		}

		return $module_map;
	}
	
	private function create_module_map_sections($id_cat, $auth_mode)
	{
		global $NEWS_CAT, $LANG, $User, $NEWS_CONFIG;
		
		$this_category = new SitemapLink($NEWS_CAT[$id_cat]['name'], new Url('/news/news' . url('.php?cat='.$id_cat, '-' . $id_cat . '+' . Url::encode_rewrite($NEWS_CAT[$id_cat]['name']) . '.php')));

		$category = new SitemapSection($this_category);
		
		$i = 0;
		
		$keys = array_keys($NEWS_CAT);
		$num_cats = count($NEWS_CAT);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $NEWS_CAT[$id];
			if ($id != 0 && $properties['id_parent'] == $id_cat)
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