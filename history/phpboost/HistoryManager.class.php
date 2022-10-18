<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 22
 * @since       PHPBoost 6.0 - 2021 10 22
*/

class HistoryManager
{
	private static $db_querier;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	/**
	 * @desc Count history lines number.
	 * @param string $condition (optional) : Restriction to apply to the history list
	 * @param array $parameters (optional) : Parameters list to apply to the condition
	 */
	public static function count($condition = '', $parameters = array())
	{
		return self::$db_querier->count(HistorySetup::$history_table, $condition, $parameters);
	}

	/**
	 * @desc Create a line in the history list.
	 * @param string[] $columns Values of the history
	 */
	public static function add(array $columns)
	{
		$result = self::$db_querier->insert(HistorySetup::$history_table, $columns);

		return $result->get_last_inserted_id();
	}

	/**
	 * @desc Update a history line.
	 * @param string $condition Restriction to apply to the list
	 * @param string[] $parameters Parameters of the condition
	 */
	public static function update(array $columns, $condition, array $parameters)
	{
		self::$db_querier->update(HistorySetup::$history_table, $columns, $condition, $parameters);
	}

	/**
	 * @desc Delete a line in the history list.
	 * @param string $condition Restriction to apply to the list
	 * @param string[] $parameters Parameters of the condition
	 */
	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(HistorySetup::$history_table, $condition, $parameters);
	}
}
?>
