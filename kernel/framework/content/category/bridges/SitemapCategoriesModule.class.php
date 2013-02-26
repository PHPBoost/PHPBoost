<?php
/*##################################################
 *                     SitemapCategoriesModule.class.php
 *                            -------------------
 *   begin                : February 19, 2013
 *   copyright            : (C) 2013 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
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
		$this_category = new SitemapLink($this->categories_manager->get_categories_cache()->get_category($id_cat)->get_name(), new Url('s'));

		$section = new SitemapSection($this_category);
	
		foreach ($categories as $id => $category)
		{
			if ($id != Category::ROOT_CATEGORY && $category->get_id_parent() == $id_cat)
			{
				$section->add($this->create_module_map_sections($categories, $id, $auth_mode));
			}
		}
		
		return $section;
	}
	
	abstract protected function get_category_url($id, $rewrited_name);
}
?>