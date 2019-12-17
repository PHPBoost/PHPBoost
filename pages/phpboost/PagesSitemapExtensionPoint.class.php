<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 02 15
 * @since       PHPBoost 3.0 - 2010 06 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

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
		
		$title = stripslashes($categories[$id_cat]['title']);
		$this_category = new SitemapLink($title, new Url('/pages/' . url('pages.php?title='.Url::encode_rewrite($title), Url::encode_rewrite($title))));

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
