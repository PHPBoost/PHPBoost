<?php
/*##################################################
 *                          FeedsCategoriesModule.class.php
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
	    $cats_tree = new FeedsCat($module_id, 0, LangLoader::get_message('root', 'main'));
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