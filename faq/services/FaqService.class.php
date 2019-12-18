<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 18
 * @since       PHPBoost 4.0 - 2014 09 02
 * @contributor Mipel <mipel@phpboost.com>
*/

class FaqService
{
	private static $db_querier;

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
	public static function delete(int $id)
	{
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}

		self::$db_querier->delete(FaqSetup::$faq_table, 'WHERE id=:id', array('id' => $id));
        
		self::$db_querier->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'faq', 'id' => $id));
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
    
	public static function clear_cache()
	{
		Feed::clear_cache('faq');
		FaqCache::invalidate();
		FaqCategoriesCache::invalidate();
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
}
?>
