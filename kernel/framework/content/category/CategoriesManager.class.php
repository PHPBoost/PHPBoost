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
	 * @var string table name where are stocked the categories to manage.
	 */
	private $table_name;
	
	/**
	 * @var CategoriesCache The cached data class
	 */
	private $categories_cache;
	
	/**
	 * @var DBQuerier
	 */
	private $db_querier;
	
	const STANDARD_CATEGORY_CLASS = 'Category';
	const RICH_CATEGORY_CLASS = 'RichCategory';
	
	/**
	 * @param CategoriesCache $categories_cache
	 */
	public function __construct(CategoriesCache $categories_cache)
	{
		$this->table_name = PREFIX . $categories_cache->get_table_name();
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
		$max_order = NumberHelper::numeric($this->db_querier->select('SELECT MAX(c_order) FROM :table_name WHERE id_parent=:id_parent', array('table_name' => $this->table_name, 'id_parent' => $id_parent)));

		if ($this->get_categories_cache()->category_exists($id_parent))
		{
			$order = $category->get_order();
			if ($order <= 0 || $order > $max_order)
			{
				$category->incremente_order();
				$result = $this->db_querier->insert($this->table_name, $category->get_properties());
				$this->regenerate_cache();
				return $result->get_last_inserted_id();
			}
			else
			{
				// Check the request
				$this->db_querier->update($this->table_name, array('c_order' => "c_order + 1"), 'WHERE id_parent=:id_parent AND c_order >= :order', array('id_parent' => $id_parent, 'order' => $category->get_order()));
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
	 * @param unknown_type $id
	 */
	public function delete($id)
	{
		if ($this->get_categories_cache()->category_exists($id))
		{
			throw new CategoryNotFoundException();
		}

		$category = $this->get_categories_cache()->get_category($id);
		$this->db_querier->delete($this->table_name, 'WHERE id=:id', array('id' => $id));
		$this->db_querier->update($this->table_name, array('c_order' => "c_order - 1"), 'WHERE id_parent=:id_parent AND c_order > :order', array('id_parent' => $category->get_id_parent(), 'order' => $category->get_order()));
		$this->regenerate_cache();
	}

	/**
	 * @return CategoriesCache 
	 */
	public function get_categories_cache()
	{
		return $this->categories_cache;
	}
	
	private function regenerate_cache()
	{
		
	}
}
?>