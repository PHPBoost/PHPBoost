<?php
/**
 * @package     Content
 * @subpackage  Category\cache
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 10
 * @since       PHPBoost 4.0 - 2013 01 31
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor janus57 <janus57@phpboost.com>
*/

abstract class CategoriesCache implements CacheData
{
	/**
	 * @var string the module identifier
	 */
	protected static $module_id;

	protected static $module;
	protected static $module_category;

	protected $categories;

	public static function __static()
	{
		$module_id = Environment::get_running_module_name();
		if (!in_array($module_id, array('admin', 'kernel', 'user')))
		{
			self::$module_id       = $module_id;
			self::$module          = ModulesManager::get_module(self::$module_id);
			$category_class        = TextHelper::ucfirst(self::$module_id) . 'Category';
			self::$module_category = (class_exists($category_class) && is_subclass_of($category_class, 'Category') ? $category_class : '');
		}
	}

	public function __construct($module_id = '')
	{
		if ($module_id)
		{
			self::$module_id       = $module_id;
			self::$module          = ModulesManager::get_module(self::$module_id);
			$category_class        = TextHelper::ucfirst(self::$module_id) . 'Category';
			self::$module_category = (class_exists($category_class) && is_subclass_of($category_class, 'Category') ? $category_class : '');
		}
		else
		{
			self::__static();
		}
	}

	public function synchronize()
	{
		$categories_cache = self::get_class($this->get_module_identifier());
		$category_class = $categories_cache->get_category_class();

		$root_category = $categories_cache->get_root_category();
		$root_category->set_elements_number($categories_cache->get_category_elements_number($root_category->get_id()));
		$this->categories[Category::ROOT_CATEGORY] = $root_category;
		$result = PersistenceContext::get_querier()->select_rows($categories_cache->get_table_name(), array('*'), 'ORDER BY id_parent, c_order');
		while ($row = $result->fetch())
		{
			$category = new $category_class();
			$category->set_properties($row);
			$this->categories[$row['id']] = $category;
		}
		$result->dispose();

		foreach ($this->categories as &$category)
		{
			if (!$category->has_special_authorizations())
				$category->set_authorizations($this->categories[$category->get_id_parent()]->get_authorizations());

			$category->set_elements_number($categories_cache->get_category_elements_number($category->get_id()));

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
						$this->categories[$id_parent]->set_elements_number((int)$this->categories[$id_parent]->get_elements_number() + (int)$category->get_elements_number());

					$id_parent = $this->categories[$id_parent]->get_id_parent();
				}
			}
		}
	}

	public function get_module_identifier()
	{
		return self::$module_id;
	}

	abstract public function get_table_name();

	abstract public function get_table_name_containing_items();

	abstract public function get_category_class();

	abstract public function get_root_category();

	public function is_contribution_enabled()
	{
		return ModulesManager::get_module(self::get_class()->get_module_identifier())->get_configuration()->has_contribution();
	}

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
	public static function load($module_id = '')
	{
		if (!in_array($module_id, array('admin', 'kernel', 'user')))
			return CacheManager::load(get_called_class(), self::get_class($module_id)->get_module_identifier(), 'categories');
	}

	/**
	 * Invalidates categories cached data.
	 */
	public static function invalidate($module_id = '')
	{
		if (!in_array($module_id, array('admin', 'kernel', 'user')))
			CacheManager::invalidate(self::get_class($module_id)->get_module_identifier(), 'categories');
	}

	public static function get_class($module_id = '')
	{
		$class_name = get_called_class();
		return new $class_name($module_id);
	}
}
?>
