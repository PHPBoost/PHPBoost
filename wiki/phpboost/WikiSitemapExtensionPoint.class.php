<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 11 24
 * @since       PHPBoost 3.0 - 2010 06 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

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
