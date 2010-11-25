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
		global $_WIKI_CATS, $LANG, $User, $_WIKI_CONFIG, $Cache;
		
		load_module_lang('wiki');
		$Cache->load('wiki');
		
		$wiki_link = new SitemapLink($LANG['wiki'], new Url('wiki/wiki.php'), Sitemap::FREQ_DEFAULT, Sitemap::PRIORITY_LOW);
		$module_map = new ModuleMap($wiki_link, 'wiki');
		
		$id_cat = 0;
	    $keys = array_keys($_WIKI_CATS);
		$num_cats = count($_WIKI_CATS);
		$properties = array();
		
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $_WIKI_CATS[$id];
			if ($id != 0 && $properties['id_parent'] == $id_cat)
			{
				$module_map->add($this->create_module_map_sections($id, $auth_mode));
			}
		}
		
		return $module_map; 
	}

	private function create_module_map_sections($id_cat, $auth_mode)
	{
		global $_WIKI_CATS, $LANG, $User, $_WIKI_CONFIG;
		
		$this_category = new SitemapLink($_WIKI_CATS[$id_cat]['name'], new Url('/wiki/' . url('wiki.php?title='.Url::encode_rewrite($_WIKI_CATS[$id_cat]['name']), Url::encode_rewrite($_WIKI_CATS[$id_cat]['name']))));
			
		$category = new SitemapSection($this_category);
		
		$i = 0;
		
		$keys = array_keys($_WIKI_CATS);
		$num_cats = count($_WIKI_CATS);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $_WIKI_CATS[$id];
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