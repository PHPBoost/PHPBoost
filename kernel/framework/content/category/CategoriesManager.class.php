<?php
/**
 * This class allows you to easily integrate a complete system of categories in its script.
 * It needs to have its first instantiation of a parameter child object CategoriesCache and as the second parameter to have a CategoriesItemsParameters object.
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 31
 * @since       PHPBoost 4.0 - 2013 01 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class CategoriesManager
{
	/**
	 * @var string module identifier.
	 */
	protected $module_id;

	/**
	 * @var string table name where are stocked the categories to manage.
	 */
	protected $table_name;

	/**
	 * @var CategoriesCache The cached data class.
	 */
	protected $categories_cache;

	/**
	 * @var CategoriesItemsParameters
	 */
	protected $categories_items_parameters;

	/**
	 * @var DBQuerier
	 */
	protected $db_querier;

	const STANDARD_CATEGORY_CLASS = 'Category';
	const RICH_CATEGORY_CLASS = 'RichCategory';

	/**
	 * @param CategoriesCache $categories_cache A child object of the class CategoriesCache
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

		$max_order = $this->db_querier->get_column_value($this->table_name, 'MAX(c_order)', 'WHERE id_parent=:id_parent', array('id_parent' => $id_parent));
		$max_order = NumberHelper::numeric($max_order);

		if ($this->get_categories_cache()->category_exists($id_parent))
		{
			$order = $category->get_order();
			if ($order <= 0 || $order > $max_order)
			{
				$category->set_order(($max_order + 1));
			}
			else
			{
				$result = $this->db_querier->select_rows($this->table_name, array('id', 'c_order'), 'WHERE id_parent=:id_parent AND c_order >= :order', array('id_parent' => $id_parent, 'order' => $category->get_order()));
				while ($row = $result->fetch())
				{
					$this->db_querier->update($this->table_name, array('c_order' => ($row['c_order'] + 1), 'WHERE id=:id', array('id' => $row['id'])));
				}
				$result->dispose();
			}

			$result = $this->db_querier->insert($this->table_name, $category->get_properties());
			$this->regenerate_cache();
			return $result->get_last_inserted_id();
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
	 * Moves a category and items into another category. You can specify its future position in its future parent category.
	 * @param Category $category
	 * @param int $id_parent
	 * @param int $position
	 */
	public function move_into_another(Category $category, $id_parent, $position = 0)
	{
		$id = $category->get_id();
		if ($this->get_categories_cache()->category_exists($id) && $this->get_categories_cache()->category_exists($id_parent))
		{
			$options = new SearchCategoryChildrensOptions();
			$children = $this->get_children($id, $options);
			$children[$id] = $category;
			if (!array_key_exists($id_parent, $children))
			{
				$max_order = $this->db_querier->get_column_value($this->table_name, 'MAX(c_order)', 'WHERE id_parent=:id_parent', array('id_parent' => $id_parent));
				$max_order = NumberHelper::numeric($max_order);

				if ($position <= 0 || $position > $max_order)
				{
					$this->db_querier->update($this->table_name, array('id_parent' => $id_parent, 'c_order' => ($max_order + 1)), 'WHERE id=:id', array('id' => $id));

					//Update items
					$this->move_items_into_another($category, $id_parent);

					$result = $this->db_querier->select_rows($this->table_name, array('id', 'c_order'), 'WHERE id_parent=:id_parent AND c_order > :order', array('id_parent' => $category->get_id_parent(), 'order' => $category->get_order()));
					while ($row = $result->fetch())
					{
						$this->db_querier->update($this->table_name, array('c_order' => ($row['c_order'] - 1)), 'WHERE id=:id', array('id' => $row['id']));
					}
					$result->dispose();
				}
				else
				{
					$result = $this->db_querier->select_rows($this->table_name, array('id', 'c_order'), 'WHERE id_parent=:id_parent AND c_order >= :order', array('id_parent' => $id_parent, 'order' => $position));
					while ($row = $result->fetch())
					{
						$this->db_querier->update($this->table_name, array('c_order' => ($row['c_order'] + 1)), 'WHERE id=:id', array('id' => $row['id']));
					}
					$result->dispose();

					$this->db_querier->update($this->table_name, array('id_parent' => $id_parent, 'c_order' => $position), 'WHERE id=:id', array('id' => $id));

					//Update items
					$this->move_items_into_another($category, $id_parent);

					$result = $this->db_querier->select_rows($this->table_name, array('id', 'c_order'), 'WHERE id_parent=:id_parent AND c_order > :order', array('id_parent' => $category->get_id_parent(), 'order' => $category->get_order()));
					while ($row = $result->fetch())
					{
						$this->db_querier->update($this->table_name, array('c_order' => ($row['c_order'] - 1)), 'WHERE id=:id', array('id' => $row['id']));
					}
					$result->dispose();
				}

				$this->regenerate_cache();
			}
			else
			{
				throw new IllegalArgumentException('You can not move this category is one of its childs category !');
			}
		}
		else
		{
			if (!$this->get_categories_cache()->category_exists($id_parent))
			{
				throw new CategoryNotFoundException($id_parent);
			}
			elseif (!$this->get_categories_cache()->category_exists($id))
			{
				throw new CategoryNotFoundException($id);
			}
		}
	}

	/**
	 * Moves items into another category.
	 * @param Category $category
	 * @param int $id_parent
	 */
	public function move_items_into_another(Category $category, $id_parent)
	{
		if ($this->get_categories_cache()->category_exists($category->get_id()) && $this->get_categories_cache()->category_exists($id_parent))
		{
			$this->db_querier->update($this->categories_items_parameters->get_table_name_contains_items(), array($this->categories_items_parameters->get_field_name_id_category() => $id_parent), 'WHERE '.$this->categories_items_parameters->get_field_name_id_category().'=:old_id_category', array('old_id_category' => $category->get_id()));
		}
	}

	/**
	 * Update category and items position.
	 * @param Category $category
	 * @param int $id_parent
	 * @param int $position
	 */
	public function update_position(Category $category, $id_parent, $position)
	{
		$id = $category->get_id();
		if ($this->get_categories_cache()->category_exists($id) && $this->get_categories_cache()->category_exists($id_parent) && !($category->get_id_parent() == $id_parent && $category->get_order() == $position))
		{
			$options = new SearchCategoryChildrensOptions();
			$children = $this->get_children($id, $options);
			$children[$id] = $category;
			if (!array_key_exists($id_parent, $children))
			{
				$max_order = $this->db_querier->get_column_value($this->table_name, 'MAX(c_order)', 'WHERE id_parent=:id_parent', array('id_parent' => $id_parent));
				$max_order = NumberHelper::numeric($max_order);

				if ($position <= 0 || $position > $max_order)
				{
					$this->db_querier->update($this->table_name, array('id_parent' => $id_parent, 'c_order' => ($max_order + 1)), 'WHERE id=:id', array('id' => $id));
				}
				else
				{
					$this->db_querier->update($this->table_name, array('id_parent' => $id_parent, 'c_order' => $position), 'WHERE id=:id', array('id' => $id));
				}

				$this->regenerate_cache();
			}
		}
	}

	/**
	 * Deletes a category and items.
	 * @param int $id Id of the category to delete.
	 */
	public function delete($id)
	{
		if (!$this->get_categories_cache()->category_exists($id) || $id == Category::ROOT_CATEGORY)
		{
			throw new CategoryNotFoundException($id);
		}

		$category = $this->get_categories_cache()->get_category($id);
		$this->db_querier->delete($this->table_name, 'WHERE id=:id', array('id' => $id));

		//Delete items
		$module_has_items = ModulesManager::get_module($this->module_id)->get_configuration()->has_items();
		$items_manager = $module_has_items ? ItemsService::get_items_manager($this->module_id) : '';
		$result = $this->db_querier->select_rows($this->categories_items_parameters->get_table_name_contains_items(), array('id'), 'WHERE ' . $this->categories_items_parameters->get_field_name_id_category() . '=:id_category', array('id_category' => $id));
		while ($row = $result->fetch())
		{
			if ($module_has_items)
				$items_manager->delete_from_id($row['id']);
			else
			{
				$this->db_querier->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => $this->module_id, 'id' => $row['id']));

				CommentsService::delete_comments_topic_module($this->module_id, $row['id']);
				KeywordsService::get_keywords_manager($this->module_id)->delete_relations($row['id']);
				NotationService::delete_notes_id_in_module($this->module_id, $row['id']);
			}
		}
		$result->dispose();
		
		$this->db_querier->delete($this->categories_items_parameters->get_table_name_contains_items(), 'WHERE '.$this->categories_items_parameters->get_field_name_id_category().'=:id_category', array('id_category' => $id));
		
		$result = $this->db_querier->select_rows($this->table_name, array('id', 'c_order'), 'WHERE id_parent=:id_parent AND c_order > :order', array('id_parent' => $category->get_id_parent(), 'order' => $category->get_order()));
		while ($row = $result->fetch())
		{
			$this->db_querier->update($this->table_name, array('c_order' => ($row['c_order'] - 1)), 'WHERE id=:id', array('id' => $row['id']));
		}
		$result->dispose();

		$this->regenerate_cache();
	}

	/**
	 * Category[string] the children Categories map (id => category) for category id
	 * @param int $id_category
	 * @param SearchCategoryChildrensOptions $search_category_children_options
	 */
	public function get_children($id_category, SearchCategoryChildrensOptions $search_category_children_options, $add_this = false)
	{
		if (!$this->get_categories_cache()->category_exists($id_category))
		{
			throw new CategoryNotFoundException($id_category);
		}

		$categories = $this->categories_cache->get_categories();
		$root_category = $categories[Category::ROOT_CATEGORY];
		$children_categories = array();

		if ($add_this)
			$children_categories[$id_category] = $this->categories_cache->get_category($id_category);

		if (($search_category_children_options->is_excluded_categories_recursive() && $search_category_children_options->category_is_excluded($root_category)) || !$search_category_children_options->check_authorizations($root_category))
		{
			return array();
		}

		if ($id_category == Category::ROOT_CATEGORY && !$search_category_children_options->category_is_excluded($root_category))
		{
			$children_categories[Category::ROOT_CATEGORY] = $root_category;
		}

		return $this->build_children_map($id_category, $categories, $id_category, $search_category_children_options, $children_categories);
	}

	/**
	 * Category[string] the parents Categories map (id => category) for category id
	 * @param int $id_category
	 * @param bool $add_this Add category in the map
	 */
	public function get_parents($id_category, $add_this = false)
	{
		$parents_categories = array();

		if ($id_category != Category::ROOT_CATEGORY)
		{
			if (!$this->get_categories_cache()->category_exists($id_category))
			{
				throw new CategoryNotFoundException($id_category);
			}

			if ($add_this)
				$parents_categories[$id_category] = $this->categories_cache->get_category($id_category);

			if ($id_category > 0)
			{
				while ((int)$this->categories_cache->get_category($id_category)->get_id_parent() != Category::ROOT_CATEGORY)
				{
					$id_parent = $this->categories_cache->get_category($id_category)->get_id_parent();
					$parents_categories[$id_parent] = $this->categories_cache->get_category($id_parent);
					$id_category = $id_parent;
				}
				$parents_categories[Category::ROOT_CATEGORY] = $this->categories_cache->get_category(Category::ROOT_CATEGORY);
			}
		}
		else
		{
			if ($add_this)
				$parents_categories[$id_category] = $this->categories_cache->get_category($id_category);
		}

		return $parents_categories;
	}

	/**
	 * Computes the global authorization level of the whole parent categories. The result corresponds to all the category's parents merged.
	 * @param int $id_category Id of the category for which you want to know what is the global authorization
	 * @param int $bit The autorization bit you want to check
	 * @param int $mode Merge mode. If it corresponds to a read autorization, use Authorizations::AUTH_PARENT_PRIORITY which will disallow for example all the subcategories of a category to which you can't access, or Authorizations::AUTH_CHILD_PRIORITY if you want to work in write mode, each child will be able to redifine the authorization.
	 * @return mixed[] The merged array that you can use only for the bit $bit.
	 */
	public function get_heritated_authorizations($id_category, $bit, $mode)
	{
		$categories = array_reverse($this->get_parents($id_category, true));

		$result = $this->categories_cache->get_root_category()->get_authorizations();
		if (!empty($categories))
		{
			foreach ($categories as $category)
			{
				if ($category->get_id() !== Category::ROOT_CATEGORY)
				{
					$result = Authorizations::merge_auth($result, $category->has_special_authorizations() ? $category->get_authorizations() : $result, $bit, $mode);
				}
			}
		}
		return $result;
	}

	public function get_select_categories_form_field($id, $label, $value, SearchCategoryChildrensOptions $search_category_children_options, array $field_options = array(), array $select_options = array())
	{
		return new FormFieldCategoriesSelect($id, $label, $value, $search_category_children_options, $field_options, $this->get_categories_cache(), $select_options);
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
		call_user_func_array($class . '::invalidate', array($this->module_id));
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

	private function build_children_map($id_category, $categories, $id_parent, $search_category_children_options, &$children_categories = array(), $node = 1)
	{
		foreach ($categories as $id => $category)
		{
			if ($category->get_id_parent() == $id_parent && $id != Category::ROOT_CATEGORY)
			{
				if ($search_category_children_options->check_authorizations($category) && !$search_category_children_options->category_is_excluded($category))
					$children_categories[$id] = $category;

				if ($search_category_children_options->check_authorizations($category) && ($search_category_children_options->is_excluded_categories_recursive() ? !$search_category_children_options->category_is_excluded($category) : true) && $search_category_children_options->is_enabled_recursive_exploration())
					$this->build_children_map($id_category, $categories, $id, $search_category_children_options, $children_categories, ($node+1));
			}
		}
		return $children_categories;
	}
}
?>
