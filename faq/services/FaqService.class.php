<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 14
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
	 * @param string[] $item : new FaqItem
	 */
	public static function add(FaqItem $item)
	{
		$result = self::$db_querier->insert(FaqSetup::$faq_table, $item->get_properties());

		return $result->get_last_inserted_id();
	}

	/**
	 * @desc Update an entry.
	 * @param string[] $item : FaqItem to update
	 */
	public static function update(FaqItem $item)
	{
		self::$db_querier->update(FaqSetup::$faq_table, $item->get_properties(), 'WHERE id=:id', array('id' => $item->get_id()));
	}

	/**
	 * @desc Delete an entry.
	 * @param int $id Item identifier
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
	 * @desc Return the item with all its properties from its id.
	 * @param int $id Item identifier
	 */
	public static function get_item(int $id)
	{
		$row = self::$db_querier->select_single_row_query('SELECT *
		FROM ' . FaqSetup::$faq_table . ' faq
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = faq.author_user_id
		WHERE faq.id=:id', array(
			'id' => $id
		));

		$item = new FaqItem();
		$item->set_properties($row);
		return $item;
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
