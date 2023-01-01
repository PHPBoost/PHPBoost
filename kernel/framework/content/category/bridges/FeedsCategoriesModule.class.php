<?php
/**
 * @package     Content
 * @subpackage  Category\bridges
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 26
 * @since       PHPBoost 4.0 - 2013 01 31
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FeedsCategoriesModule
{
	/**
	 * @var CategoriesManager
	 */
	private $categories_manager;

	public function __construct(CategoriesManager $categories_manager)
	{
		$this->categories_manager = $categories_manager;
	}

	public function get_feed_list()
	{
		$module_id = $this->categories_manager->get_module_id();
		$list = new FeedsList();
		$cats_tree = new FeedsCat($module_id, 0, LangLoader::get_message('common.root', 'common-lang'));
		$categories = $this->categories_manager->get_categories_cache()->get_categories();
		$this->build_feeds_sub_list($module_id, $categories, $cats_tree, 0);
		$list->add_feed($cats_tree, Feed::DEFAULT_FEED_NAME);

		return $list;
	}

	public function build_feeds_sub_list($module_id, $categories, FeedsCat $tree, $parent_id)
	{
		$id_categories = array_keys($categories);
		$num_cats =	count($id_categories);

		for ($i = 0; $i < $num_cats; $i++)
		{
			$id = $id_categories[$i];
			$category =& $categories[$id];
			if ($id != 0 && $category->get_id_parent() == $parent_id)
			{
				$sub_tree = new FeedsCat($module_id, $id, $category->get_name());
				$this->build_feeds_sub_list($module_id, $categories, $sub_tree, $id);
				$tree->add_child($sub_tree);
			}
		}
	}
}
?>
