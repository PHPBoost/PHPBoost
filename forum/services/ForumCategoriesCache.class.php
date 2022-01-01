<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 30
 * @since       PHPBoost 4.1 - 2015 02 25
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ForumCategoriesCache extends CategoriesCache
{
	public function synchronize()
	{
		require_once(PATH_TO_ROOT . '/forum/forum_functions.php');

		$categories_cache = self::get_class();
		$category_class = $categories_cache->get_category_class();

		$root_category = $categories_cache->get_root_category();
		$this->categories[Category::ROOT_CATEGORY] = $root_category;
		$result = PersistenceContext::get_querier()->select_rows($categories_cache->get_table_name(), array('*'), 'ORDER BY id_parent, c_order');

		$categories = array();

		while ($row = $result->fetch())
		{
			$categories[] = $row;
		}
		$result->dispose();

		$categories = parentChildSort_r('id', 'id_parent', $categories);

		foreach ($categories as $row)
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
	}

	public function get_table_name()
	{
		return ForumSetup::$forum_cats_table;
	}

	public function get_table_name_containing_items()
	{
		return ForumSetup::$forum_topics_table;
	}

	public function get_category_class()
	{
		return 'ForumCategory';
	}

	public function get_module_identifier()
	{
		return 'forum';
	}

	protected function get_category_elements_number($id_category)
	{
		$topics_number = ForumService::count_topics('WHERE id_category = :id_category', array('id_category' => $id_category));
		$messages_number = ForumService::count_messages('WHERE id_category = :id_category', array('id_category' => $id_category));

		return array(
			'topics_number'   => (int)$topics_number,
			'messages_number' => (int)$messages_number
		);
	}

	public function get_root_category()
	{
		$root = new ForumCategory();
		$root->set_authorizations(ForumConfig::load()->get_authorizations());

		return $root;
	}
}
?>
