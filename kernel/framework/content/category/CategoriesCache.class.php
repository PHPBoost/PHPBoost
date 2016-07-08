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
	protected $categories;
	
	public function synchronize()
	{
		$categories_cache = self::get_class();
		$category_class = $categories_cache->get_category_class();
		
		$root_category = $categories_cache->get_root_category();
		$root_category->set_elements_number($categories_cache->get_category_elements_number($root_category->get_id()));
		$this->categories[Category::ROOT_CATEGORY] = $root_category;
		$result = PersistenceContext::get_querier()->select_rows($categories_cache->get_table_name(), array('*'), 'ORDER BY id_parent, c_order');
		while ($row = $result->fetch())
		{
			$category = new $category_class();
			$category->set_properties($row);
			$category->set_elements_number($categories_cache->get_category_elements_number($category->get_id()));
			
			if (!$category->has_special_authorizations())
			{
				$category->set_authorizations($root_category->get_authorizations());
			}
			
			$this->categories[$row['id']] = $category;
			
			if ($category->get_id_parent() != Category::ROOT_CATEGORY)
			{
				$current_category_elements_number = $category->get_elements_number();
				$id_parent = $category->get_id_parent();
				while ($id_parent != Category::ROOT_CATEGORY)
				{
					$parent_elements_number = $this->categories[$id_parent]->get_elements_number();
					
					if (is_array($current_category_elements_number))
					{
						foreach ($current_category_elements_number as $element_id => $elements_number)
						{
							$parent_elements_number[$element_id] = $parent_elements_number[$element_id] + $elements_number;
						}
						$this->categories[$id_parent]->set_elements_number($parent_elements_number);
					}
					else
						$this->categories[$id_parent]->set_elements_number($parent_elements_number + $current_category_elements_number);
					
					$id_parent = $this->categories[$id_parent]->get_id_parent();
				}
			}
		}
		$result->dispose();
	}
	
	abstract public function get_table_name();
	
	abstract public function get_category_class();
	
	abstract public function get_module_identifier();
	
	abstract public function get_root_category();
	
	protected function get_category_elements_number($id_category)
	{
		return 0;
	}
	
	public function get_categories()
	{
		return $this->categories;
	}
	
	public function get_children($id_category, $allowed_categories_filter = array())
	{
		$children = array();
		foreach ($this->categories as $id => $category)
		{
			if ($category->get_id_parent() == $id_category && $id != Category::ROOT_CATEGORY)
			{
				if ((!empty($allowed_categories_filter) && in_array($id, $allowed_categories_filter)) || empty($allowed_categories_filter))
					$children[$id] = $category;
			}
			
			if (!empty($allowed_categories_filter) && !in_array($id, $allowed_categories_filter))
			{
				$current_category_elements_number = $category->get_elements_number();
				$id_parent = $category->get_id_parent();
				while ($id_parent != Category::ROOT_CATEGORY)
				{
					$parent_elements_number = $this->categories[$id_parent]->get_elements_number();
					
					if (is_array($current_category_elements_number))
					{
						foreach ($current_category_elements_number as $element_id => $elements_nbr)
						{
							$parent_elements_number[$element_id] = max(($parent_elements_number[$element_id] - $elements_nbr), 0);
						}
						$this->categories[$id_parent]->set_elements_number($parent_elements_number);
					}
					else
					{
						$this->categories[$id_parent]->set_elements_number(max(($parent_elements_number - $current_category_elements_number), 0));
					}
					
					$id_parent = $this->categories[$id_parent]->get_id_parent();
				}
			}
		}
		return $children;
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
		throw new CategoryNotFoundException($id);
	}
	
	public function has_categories()
	{
		return count($this->categories) > 1;
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
