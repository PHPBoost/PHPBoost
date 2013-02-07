<?php
/*##################################################
 *                             CategoriesManager.class.php
 *                            -------------------
 *   begin                : January 29, 2013
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

/**
 * @package {@package}
 * @author Kévin MASSY
 * @desc
 */
class CategoriesManager
{
	/**
	 * @var string module identifier.
	 */
	private $module_id;
	
	/**
	 * @var string table name where are stocked the categories to manage.
	 */
	private $table_name;
	
	/**
	 * @var CategoriesCache The cached data class.
	 */
	private $categories_cache;
	
	/**
	 * @var DBQuerier
	 */
	private $db_querier;
	
	const STANDARD_CATEGORY_CLASS = 'Category';
	const RICH_CATEGORY_CLASS = 'RichCategory';
	
	/**
	 * @param string $id_module
	 * @param string $table_name
	 * @param CategoriesCache $categories_cache
	 */
	public function __construct(CategoriesCache $categories_cache)
	{
		$this->module_id = $categories_cache->get_module_identifier();
		$this->table_name = $categories_cache->get_table_name();
		$this->categories_cache = $categories_cache;
	
		$this->db_querier = PersistenceContext::get_querier();
	}
	
	/**
	 * @param Category $category
	 */
	public function add(Category $category)
	{
		$id_parent = $category->get_id_parent();
		$max_order = $this->db_querier->select_single_row_query('SELECT MAX(c_order) FROM '. $this->table_name .' WHERE id_parent=:id_parent', array('id_parent' => $id_parent));
		$max_order = NumberHelper::numeric($max_order);
		
		if ($this->get_categories_cache()->category_exists($id_parent))
		{
			$order = $category->get_order();
			if ($order <= 0 || $order > $max_order)
			{
				$category->set_order(($max_order + 1));
				
				$result = $this->db_querier->insert($this->table_name, $category->get_properties());
				$this->regenerate_cache();
				return $result->get_last_inserted_id();
			}
			else
			{
				$result = PersistenceContext::get_querier()->select_rows($this->table_name, array('id', 'c_order'), 'WHERE id_parent=:id_parent AND c_order >= :order', array('id_parent' => $id_parent, 'order' => $category->get_order()));
				while ($row = $result->fetch())
				{
					$this->db_querier->update($this->table_name, array('c_order' => ($row['c_order'] + 1), 'WHERE id=:id', array('id' => $row['id'])));
				}

				$result = $this->db_querier->insert($this->table_name, $category->get_properties());
				$this->regenerate_cache();
				return $result->get_last_inserted_id();
			}
		}
		else
		{
			throw new CategoryNotFoundException($id_parent);
		}
	}
	
	/**
	 * @param Category $category
	 */
	public function update(Category $category)
	{
		$id = $category->get_id();
		$id_parent = $category->get_id_parent();
		if ($this->get_categories_cache()->category_exists($id))
		{
			$last_id_parent = $this->get_categories_cache()->get_category($id)->get_id_parent();
			if ($id_parent != $last_id_parent)
			{
				//TODO
				//$this->move_into_another($id_cat, $id_parent);
			}
			
			$this->db_querier->update($this->table_name, $category->get_properties(), 'WHERE id=:id', array('id' => $id));
			$this->regenerate_cache();
		}
		else
		{
			throw new CategoryNotFoundException($id_parent);
		}
	}
	
	/**
	 * @desc Deletes a category.
	 * @param int $id Id of the category to delete.
	 */
	public function delete($id)
	{
		if (!$this->get_categories_cache()->category_exists($id) && $id == Category::ROOT_CATEGORY)
		{
			throw new CategoryNotFoundException($id);
		}

		$category = $this->get_categories_cache()->get_category($id);
		$this->db_querier->delete($this->table_name, 'WHERE id=:id', array('id' => $id));
		
		$result = PersistenceContext::get_querier()->select_rows($this->table_name, array('id', 'c_order'), 'WHERE id_parent=:id_parent AND c_order > :order', array('id_parent' => $category->get_id_parent(), 'order' => $category->get_order()));
		while ($row = $result->fetch())
		{
			$this->db_querier->update($this->table_name, array('c_order' => ($row['c_order'] - 1)), 'WHERE id=:id', array('id' => $row['id']));
		}
		$this->regenerate_cache();
	}
	
	public function get_childrens($id_category, SearchCategoryChildrensOptions $search_category_children_options)
	{
		$all_categories = $this->categories_cache->get_categories();
		$root_category = $all_categories[Category::ROOT_CATEGORY];
		$categories = array();
		
		if (($search_category_children_options->is_excluded_categories_recursive() && $search_category_children_options->category_is_excluded($root_category)) || !$search_category_children_options->check_authorizations($root_category))
		{
			return array();
		}
		
		if (!$search_category_children_options->category_is_excluded($root_category))
		{
			$categories[Category::ROOT_CATEGORY] = $root_category;
		}

		return $this->build_children_map($id_category, $all_categories, $id_category, $search_category_children_options, $categories);
	}
	
	public function get_parents($id_category, $add_this = false)
	{
		$list = array();
		if ($add_this)
			$list[] = $id_category;

		if ($id_category > 0)
		{
			while ((int)$this->categories_cache->get_category($id_category)->get_id_parent() != Category::ROOT_CATEGORY)
			{
				$id_parent = $this->categories_cache->get_category($id_category)->get_id_parent();
			    $list[$id_parent] = $this->categories_cache->get_category($id_parent);
			    $id_category = $id_parent;
			}
			$list[Category::ROOT_CATEGORY] = $this->categories_cache->get_category(Category::ROOT_CATEGORY);
		}
		return $list;
	}
	
	public function get_select_categories_form_field($id, $label, $value, SearchCategoryChildrensOptions $search_category_children_options, array $field_options = array())
	{
		return new FormFieldCategoriesSelect($id, $label, $value, $search_category_children_options, $field_options, $this->get_categories_cache());
	}
	
	/**
	 * @return FeedsCategoriesModule
	 */
	public function get_feeds_categories_module()
	{
		return new FeedsCategoriesModule($this);
	}

	private function regenerate_cache()
	{
		$class = get_class($this->get_categories_cache());
		call_user_func(array($class, 'invalidate'));
	}
	
	/**
	 * @return CategoriesCache 
	 */
	public function get_categories_cache() { return $this->categories_cache; }
	
	/**
	 * @return string module identifier.
	 */
	public function get_module_id() { return $this->module_id; }
	
	
	private function build_children_map($id_category, $all_categories, $id_parent, $search_category_children_options, &$categories = array(), $node = 1)
	{
		foreach ($all_categories as $id => $category)
		{
			if ($category->get_id_parent() == $id_parent && $id != Category::ROOT_CATEGORY)
			{
				if ($search_category_children_options->check_authorizations($category) && !$search_category_children_options->category_is_excluded($category))
					$categories[$id] = $category;
				
				if ($search_category_children_options->check_authorizations($category) && ($search_category_children_options->is_excluded_categories_recursive() ? !$search_category_children_options->category_is_excluded($category) : true) && $search_category_children_options->is_enabled_recursive_exploration())
					$this->build_children_map($id_category, $all_categories, $id, $search_category_children_options, $categories, ($node+1));
			}
		}
		return $categories;
	}
}
?>