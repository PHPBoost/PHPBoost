<?php
/*##################################################
 *                               FaqService.class.php
 *                            -------------------
 *   begin                : September 2, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class FaqService
{
	private static $db_querier;
	
	private static $categories_manager;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}
	
	 /**
	 * @desc Count items number.
	 * @param string $condition (optional) : Restriction to apply to the list of items
	 */
	public static function count($condition = '', $parameters = array())
	{
		return self::$db_querier->count(FaqSetup::$faq_table, $condition, $parameters);
	}
	
	 /**
	 * @desc Create a new entry in the database table.
	 * @param string[] $faq_question : new FaqQuestion
	 */
	public static function add(FaqQuestion $faq_question)
	{
		$result = self::$db_querier->insert(FaqSetup::$faq_table, $faq_question->get_properties());
		
		return $result->get_last_inserted_id();
	}
	
	 /**
	 * @desc Update an entry.
	 * @param string[] $faq_question : FaqQuestion to update
	 */
	public static function update(FaqQuestion $faq_question)
	{
		self::$db_querier->update(FaqSetup::$faq_table, $faq_question->get_properties(), 'WHERE id=:id', array('id' => $faq_question->get_id()));
	}
	
	 /**
	 * @desc Delete an entry.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	 */
	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(FaqSetup::$faq_table, $condition, $parameters);
	}
	
	 /**
	 * @desc Return the properties of a faq question.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	 */
	public static function get_question($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT *
		FROM ' . FaqSetup::$faq_table . ' faq
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = faq.author_user_id
		' . $condition, $parameters);
		
		$faq_question = new FaqQuestion();
		$faq_question->set_properties($row);
		return $faq_question;
	}
	
	 /**
	 * @desc Update the position of a question.
	 * @param string[] $id_question : id of the question to update
	 * @param string[] $position : new question position
	 */
	public static function update_position($id_question, $position)
	{
		self::$db_querier->update(FaqSetup::$faq_table, array('q_order' => $position), 'WHERE id=:id', array('id' => $id_question));
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
			$categories_items_parameters->set_table_name_contains_items(FaqSetup::$faq_table);
			self::$categories_manager = new CategoriesManager(FaqCategoriesCache::load(), $categories_items_parameters);
		}
		return self::$categories_manager;
	}
}
?>
