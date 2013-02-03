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
	public function __construct($module_id, $table_name, CategoriesCache $categories_cache)
	{
		$this->module_id = $module_id;
		$this->table_name = $table_name;
		$this->categories_cache = $categories_cache;
	
		$this->db_querier = PersistenceContext::get_querier();
	}
	
	/**
	 * @param int $id_parent
	 * @param Category $category
	 */
	public function add($id_parent, Category $category)
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
			// TODO Parent category doesn't exists
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
			// TODO Parent category doesn't exists
		}
	}
	
	/**
	 * @desc Deletes a category.
	 * @param int $id Id of the category to delete.
	 */
	public function delete($id)
	{
		if (!$this->get_categories_cache()->category_exists($id))
		{
			throw new CategoryNotFoundException();
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
}
?>