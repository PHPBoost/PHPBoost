<?php
/*##################################################
 *                     WikiSitemapExtensionPoint.class.php
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

class WikiSitemapExtensionPoint implements SitemapExtensionPoint
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
		
		load_module_lang('wiki');
		
		$categories_cache = WikiCategoriesCache::load();
		$categories = $categories_cache->get_categories();
		
		$wiki_link = new SitemapLink($LANG['wiki'], new Url('/wiki/wiki.php'), Sitemap::FREQ_DEFAULT, Sitemap::PRIORITY_LOW);
		$module_map = new ModuleMap($wiki_link, 'wiki');
		
		$id_cat = 0;
	    $keys = array_keys($categories);
		$num_cats = $categories_cache->get_number_categories();
		$properties = array();
		
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $categories[$id];
			if ($id != 0 && $properties['id_parent'] == $id_cat)
			{
				$module_map->add($this->create_module_map_sections($id, $auth_mode));
			}
		}
		
		return $module_map; 
	}

	private function create_module_map_sections($id_cat, $auth_mode)
	{
		global $LANG;
		
		$categories_cache = WikiCategoriesCache::load();
		$categories = $categories_cache->get_categories();
		
		$this_category = new SitemapLink(stripslashes($categories[$id_cat]['title']), new Url('/wiki/' . url('wiki.php?title='.$categories[$id_cat]['encoded_title'], $categories[$id_cat]['encoded_title'])));
		
		$category = new SitemapSection($this_category);
		
		$i = 0;
		
		$keys = array_keys($categories);
		$num_cats = $categories_cache->get_number_categories();
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $categories[$id];
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