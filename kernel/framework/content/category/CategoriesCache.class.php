<?php
/*##################################################
 *                        CategoriesCache.class.php
 *                            -------------------
 *   begin                : January 31, 2013
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

abstract class CategoriesCache implements CacheData
{
	private $categories;
	
	public function synchronize()
	{
		$categories_cache = self::get_class();
		$category_class = $categories_cache->get_category_class();
		
		$root_category = $categories_cache->get_root_category();
		$this->categories[Category::ROOT_CATEGORY] = $root_category;
		$result = PersistenceContext::get_querier()->select_rows($categories_cache->get_table_name(), array('*'), 'ORDER BY id_parent, c_order');
		while ($row = $result->fetch())
		{
			$category = new $category_class();
			$category->set_properties($row);
			if ($category->auth_is_empty())
			{
				$category->set_auth($root_category->get_auth());
			}
			$this->categories[$row['id']] = $category;
		}
	}
	
	abstract protected function get_table_name();
	
	abstract protected function get_category_class();
	
	abstract protected function get_module_identifier();
	
	abstract protected function get_root_category();
	
	public function get_categories()
	{
		return $this->categories;
	}
	
	public function get_childrens($id_category)
	{
		$childrens = array();
		foreach ($this->categories as $id => $category)
		{
			if ($category->get_id_parent() == $id_category)
			{
				$childrens[$id] = $category;
			}
		}
		return $childrens;
	}
	
	public function category_exists($id)
	{
		return array_key_exists($id, $this->categories);
	}
	
	public function get_category($id)
	{
		if ($this->category_exists($id))
		{
			return $this->categories[$id];
		}
		throw new CategoryNotFoundException();
	}
	
	/**
	 * Loads and returns the categories cached data.
	 * @return CategoriesCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(get_called_class(), self::get_class()->get_module_identifier(), 'categories');
	}
	
	/**
	 * Invalidates categories cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate(self::get_class()->get_module_identifier(), 'categories');
	}
	
	public static function get_class()
	{
		$class_name = get_called_class();
		return new $class_name();
	}
}
?>