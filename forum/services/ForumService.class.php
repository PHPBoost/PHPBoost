<?php
/*##################################################
 *                        ForumService.class.php
 *                            -------------------
 *   begin                : May 15, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 * @desc Services of the forum module
 */
class ForumService
{
	private static $db_querier;
	
	private static $categories_manager;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}
	
	 /**
	 * @desc Count topics number.
	 * @param string $condition (optional) : Restriction to apply to the list of topics
	 */
	public static function count_topics($condition = '', $parameters = array())
	{
		return self::$db_querier->count(ForumSetup::$forum_topics_table, $condition, $parameters);
	}
	
	 /**
	 * @desc Count messages number.
	 * @param string $condition (optional) : Restriction to apply to the list of messages
	 */
	public static function count_messages($condition = '', $parameters = array())
	{
		$messages_number = 0;
		try {
			$messages_number = PersistenceContext::get_querier()->get_column_value(ForumSetup::$forum_topics_table, 'SUM(nbr_msg)', $condition, $parameters);
		} catch (RowNotFoundException $e) {}
		
		return $messages_number;
	}
	
	 /**
	 * @desc Return the authorized categories.
	 */
	public static function get_authorized_categories($current_id_category)
	{
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
		$categories = self::get_categories_manager()->get_children($current_id_category, $search_category_children_options, true);
		return array_keys($categories);
	}
	
	 /**
	 * @desc Return the categories manager.
	 */
	public static function get_categories_manager()
	{
		if (self::$categories_manager === null)
		{
			$categories_items_parameters = new CategoriesItemsParameters();
			$categories_items_parameters->set_table_name_contains_items(ForumSetup::$forum_topics_table);
			$categories_items_parameters->set_field_name_id_category('idcat');
			self::$categories_manager = new CategoriesManager(ForumCategoriesCache::load(), $categories_items_parameters);
		}
		return self::$categories_manager;
	}
}
?>
