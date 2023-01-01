<?php
/**
 * @package     Content
 * @subpackage  Category\bridges
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 4.0 - 2013 02 19
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

abstract class SitemapCategoriesModule implements SitemapExtensionPoint
{
	/**
	 * @var CategoriesManager
	 */
	private $categories_manager;

	public function __construct(CategoriesManager $categories_manager)
	{
		$this->categories_manager = $categories_manager;
	}

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
		$module = ModulesManager::get_module($this->categories_manager->get_module_id());
		$module_configuration = $module->get_configuration();

		$link = new SitemapLink($module_configuration->get_name(), new Url('/' . $module->get_id() . '/'), Sitemap::FREQ_DAILY, Sitemap::PRIORITY_MAX);
		$module_map = new ModuleMap($link, $module->get_id());

		$categories = $this->categories_manager->get_categories_cache()->get_categories();

		foreach ($categories as $id => $category)
		{
			if ($auth_mode == Sitemap::AUTH_PUBLIC)
			{
				$this_auth = Authorizations::check_auth(RANK_TYPE, User::VISITOR_LEVEL, $category->get_authorizations(), Category::READ_AUTHORIZATIONS);
			}
			else
			{
				$this_auth = AppContext::get_current_user()->check_auth($category->get_authorizations(), Category::READ_AUTHORIZATIONS);
			}

			if ($this_auth && $id != Category::ROOT_CATEGORY && $category->get_id_parent() == Category::ROOT_CATEGORY)
			{
				$module_map->add($this->create_module_map_sections($categories, $id, $auth_mode));
			}
		}

		return $module_map;
	}

	private function create_module_map_sections($categories, $id_cat, $auth_mode)
	{
		$category = $this->categories_manager->get_categories_cache()->get_category($id_cat);
		$this_category = new SitemapLink($category->get_name(), $this->get_category_url($category));

		$section = new SitemapSection($this_category);

		$i = 0;
		foreach ($categories as $id => $category)
		{
			if ($auth_mode == Sitemap::AUTH_PUBLIC)
			{
				$this_auth = Authorizations::check_auth(RANK_TYPE, User::VISITOR_LEVEL, $category->get_authorizations(), Category::READ_AUTHORIZATIONS);
			}
			else
			{
				$this_auth = AppContext::get_current_user()->check_auth($category->get_authorizations(), Category::READ_AUTHORIZATIONS);
			}

			if ($this_auth && $id != Category::ROOT_CATEGORY && $category->get_id_parent() == $id_cat)
			{
				$section->add($this->create_module_map_sections($categories, $id, $auth_mode));
				$i++;
			}
		}

		if ($i == 0)
			$section = $this_category;

		return $section;
	}

	abstract protected function get_category_url(Category $category);
}
?>
