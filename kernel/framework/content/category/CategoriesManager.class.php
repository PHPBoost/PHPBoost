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
	 * @var CategoriesItemsParameters
	 */
	private $categories_items_parameters;
	
	/**
	 * @var DBQuerier
	 */
	private $db_querier;
	
	const STANDARD_CATEGORY_CLASS = 'Category';
	const RICH_CATEGORY_CLASS = 'RichCategory';
	
	/**
	 * @param CategoriesCache $categories_cache
	 * @param CategoriesItemsParameters $categories_items_parameters
	 */
	public function __construct(CategoriesCache $categories_cache, CategoriesItemsParameters $categories_items_parameters)
	{
		$this->module_id = $categories_cache->get_module_identifier();
		$this->table_name = $categories_cache->get_table_name();
		$this->categories_cache = $categories_cache;
		$this->categories_items_parameters = $categories_items_parameters;
	
		$this->db_querier = PersistenceContext::get_querier();
	}
	
	/**
	 * @param Category $category
	 */
	public function add(Category $category)
	{
		$id_parent = $category->get_id_parent();
		$max_order = $this->db_querier->select_single_row_query('SELECT MAX(c_order) FROM '. $this->table_name .' WHERE id_parent=:id_parent', array('id_parent' => $id_parent));
		$max_order = NumberHelper::numeric($max_order['MAX(c_order)']);
		
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
				$this->move_into_another($category, $id_parent);
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
	 * @desc Moves a category and items into another category. You can specify its future position in its future parent category.
	 * @param Category $category
	 * @param int $id_parent
	 * @param int $position
	 */
	public function move_into_another(Category $category, $id_parent, $position = 0)
	{
		$id = $category->get_id();
		if (($id == Category::ROOT_CATEGORY || $this->get_categories_cache()->category_exists($id)) && ($id_parent == Category::ROOT_CATEGORY || $this->get_categories_cache()->category_exists($id_parent)))
		{
			$options = new SearchCategoryChildrensOptions();
			$childrens = $this->get_childrens($id, $options);
			$childrens[$id] = $category;
			if (!array_key_exists($id_parent, $childrens))
			{
				$max_order = $this->db_querier->select_single_row_query('SELECT MAX(c_order) FROM '. $this->table_name .' WHERE id_parent=:id_parent', array('id_parent' => $id_parent));
				$max_order = NumberHelper::numeric($max_order['MAX(c_order)']);

				if ($position <= 0 || $position > $max_order)
				{
					$this->db_querier->update($this->table_name, array('id_parent' => $id_parent, 'c_order' => ($max_order + 1)), 'WHERE id=:id', array('id' => $id));

					//Update items
					$this->db_querier->update($this->categories_items_parameters->get_table_name_contains_items(), array($this->categories_items_parameters->get_field_name_id_category() => $id_parent), 'WHERE '.$this->categories_items_parameters->get_field_name_id_category().'=:id_category', array('id_category' => $category->get_id_parent()));
					
					$result = PersistenceContext::get_querier()->select_rows($this->table_name, array('id', 'c_order'), 'WHERE id_parent=:id_parent AND c_order > :order', array('id_parent' => $category->get_id_parent(), 'order' => $category->get_order()));
					while ($row = $result->fetch())
					{
						$this->db_querier->update($this->table_name, array('c_order' => ($row['c_order'] - 1)), 'WHERE id=:id', array('id' => $row['id']));
					}
				}
				else
				{
					$result = PersistenceContext::get_querier()->select_rows($this->table_name, array('id', 'c_order'), 'WHERE id_parent=:id_parent AND c_order >= :order', array('id_parent' => $id_parent, 'order' => $position));
					while ($row = $result->fetch())
					{
						$this->db_querier->update($this->table_name, array('c_order' => ($row['c_order'] + 1)), 'WHERE id=:id', array('id' => $row['id']));
					}
					
					$this->db_querier->update($this->table_name, array('id_parent' => $id_parent, 'c_order' => $position), 'WHERE id=:id', array('id' => $id));
					
					//Update items
					$this->db_querier->update($this->categories_items_parameters->get_table_name_contains_items(), array($this->categories_items_parameters->get_field_name_id_category() => $id_parent), 'WHERE '.$this->categories_items_parameters->get_field_name_id_category().'=:id_category', array('id_category' => $category->get_id_parent()));
					
					$result = PersistenceContext::get_querier()->select_rows($this->table_name, array('id', 'c_order'), 'WHERE id_parent=:id_parent AND c_order > :order', array('id_parent' => $category->get_id_parent(), 'order' => $category->get_order()));
					while ($row = $result->fetch())
					{
						$this->db_querier->update($this->table_name, array('c_order' => ($row['c_order'] - 1)), 'WHERE id=:id', array('id' => $row['id']));
					}
				}

				$this->regenerate_cache();
			}
			else
			{
				// TODO error new id parent is it children
			}
		}
		else
		{
			if ($id_parent != Category::ROOT_CATEGORY && !$this->get_categories_cache()->category_exists($id_parent))
			{}
				//TODO new parent doesn't exists
				
			if ($id != Category::ROOT_CATEGORY && !$this->get_categories_cache()->category_exists($id))
			{}
				//TODO category doesn't exists
		}
	}
	
	/**
	 * @desc Update category and items position.
	 * @param Category $category
	 * @param int $id_parent
	 * @param int $position
	 */
	public function update_position(Category $category, $id_parent, $position)
	{
		$id = $category->get_id();
		if (($id == Category::ROOT_CATEGORY || $this->get_categories_cache()->category_exists($id)) && ($id_parent == Category::ROOT_CATEGORY || $this->get_categories_cache()->category_exists($id_parent)) && !($category->get_id_parent == $id_parent && $category->get_order() == $position))
		{
			$options = new SearchCategoryChildrensOptions();
			$childrens = $this->get_childrens($id, $options);
			$childrens[$id] = $category;
			if (!array_key_exists($id_parent, $childrens))
			{
				$max_order = $this->db_querier->select_single_row_query('SELECT MAX(c_order) FROM '. $this->table_name .' WHERE id_parent=:id_parent', array('id_parent' => $id_parent));
				$max_order = NumberHelper::numeric($max_order['MAX(c_order)']);

				if ($position <= 0 || $position > $max_order)
				{
					$this->db_querier->update($this->table_name, array('id_parent' => $id_parent, 'c_order' => ($max_order + 1)), 'WHERE id=:id', array('id' => $id));

					//Update items
					$this->db_querier->update($this->categories_items_parameters->get_table_name_contains_items(), array($this->categories_items_parameters->get_field_name_id_category() => $id_parent), 'WHERE '.$this->categories_items_parameters->get_field_name_id_category().'=:id_category', array('id_category' => $category->get_id_parent()));
				}
				else
				{
					$this->db_querier->update($this->table_name, array('id_parent' => $id_parent, 'c_order' => $position), 'WHERE id=:id', array('id' => $id));
					
					//Update items
					$this->db_querier->update($this->categories_items_parameters->get_table_name_contains_items(), array($this->categories_items_parameters->get_field_name_id_category() => $id_parent), 'WHERE '.$this->categories_items_parameters->get_field_name_id_category().'=:id_category', array('id_category' => $category->get_id_parent()));
				}

				$this->regenerate_cache();
			}
		}
	}
	
	/**
	 * @desc Deletes a category and items.
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
		
		//Delete items
		$this->db_querier->delete($this->categories_items_parameters->get_table_name_contains_items(), 'WHERE '.$this->categories_items_parameters->get_field_name_id_category().'=:id_category', array('id_category' => $id));
		
		$result = PersistenceContext::get_querier()->select_rows($this->table_name, array('id', 'c_order'), 'WHERE id_parent=:id_parent AND c_order > :order', array('id_parent' => $category->get_id_parent(), 'order' => $category->get_order()));
		while ($row = $result->fetch())
		{
			$this->db_querier->update($this->table_name, array('c_order' => ($row['c_order'] - 1)), 'WHERE id=:id', array('id' => $row['id']));
		}
		$this->regenerate_cache();
	}
	
	/**
	 * @desc Category[string] the childrens Categories map (id => category) for category id
	 * @param int $id_category
	 * @param SearchCategoryChildrensOptions $search_category_children_options
	 */
	public function get_childrens($id_category, SearchCategoryChildrensOptions $search_category_children_options)
	{
		$all_categories = $this->categories_cache->get_categories();
		$root_category = $all_categories[Category::ROOT_CATEGORY];
		$categories = array();
		
		if (($search_category_children_options->is_excluded_categories_recursive() && $search_category_children_options->category_is_excluded($root_category)) || !$search_category_children_options->check_authorizations($root_category))
		{
			return array();
		}
		
		if ($id_category == Category::ROOT_CATEGORY && !$search_category_children_options->category_is_excluded($root_category))
		{
			$categories[Category::ROOT_CATEGORY] = $root_category;
		}

		return $this->build_children_map($id_category, $all_categories, $id_category, $search_category_children_options, $categories);
	}
	
	/**
	 * @desc Category[string] the parents Categories map (id => category) for category id
	 * @param int $id_category
	 * @param bool $add_this Add category in the map
	 */
	public function get_parents($id_category, $add_this = false)
	{
		$list = array();
		if ($add_this)
			$list[$id_category] = $this->categories_cache->get_category($id_category);

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
	
	/**
	 * @desc Computes the global authorization level of the whole parent categories. The result corresponds to all the category's parents merged.
	 * @param int $id_category Id of the category for which you want to know what is the global authorization
	 * @param int $bit The autorization bit you want to check
	 * @param int $mode Merge mode. If it corresponds to a read autorization, use Authorizations::AUTH_PARENT_PRIORITY which will disallow for example all the subcategories of a category to which you can't access, or Authorizations::AUTH_CHILD_PRIORITY if you want to work in write mode, each child will be able to redifine the authorization.
	 * @return mixed[] The merged array that you can use only for the bit $bit.
	 */
	public function get_heritated_authorizations($id_category, $bit, $mode)
	{
		$categories = array_reverse($this->get_parents($id_category, true));
		$root_authorizations = $this->categories_cache->get_root_category()->get_authorizations();

		$result = array();
		
		if (!empty($categories))
		{	
			foreach ($categories as $category)
			{
				if (!$category->auth_is_equals($root_authorizations) || $category->get_id() == Category::ROOT_CATEGORY)
				{
					$result = Authorizations::merge_auth($result, $category->get_authorizations(), $bit, $mode);
				}
			}
		}
		return $result;
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

	public function regenerate_cache()
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
	
	/**
	 * @return CategoriesItemsParameters
	 */
	public function get_categories_items_parameters() { return $this->categories_items_parameters; }
	
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