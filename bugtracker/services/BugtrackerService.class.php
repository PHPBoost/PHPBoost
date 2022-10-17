<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 11
 * @since       PHPBoost 3.0 - 2012 10 19
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BugtrackerService
{
	private static $db_querier;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	/**
	 * @desc Count the bug list.
	 * @param string $condition (optional) Restriction to apply to the list
	 */
	public static function count($condition = '')
	{
		return self::$db_querier->count(BugtrackerSetup::$bugtracker_table, $condition);
	}

	/**
	 * @desc Count the history list of the bug.
	 * @param int $bug_id ID of the bug which is concerned
	 */
	public static function count_history($bug_id)
	{
		return self::$db_querier->count(BugtrackerSetup::$bugtracker_history_table, "WHERE bug_id=:id", array('id' => $bug_id));
	}

	/**
	 * @desc Create a new bug.
	 * @param string[] $bug new Bug
	 */
	public static function add(BugtrackerItem $bug)
	{
		$result = self::$db_querier->insert(BugtrackerSetup::$bugtracker_table, $bug->get_properties());

		return $result->get_last_inserted_id();
	}

	/**
	 * @desc Create a line in the history list.
	 * @param string[] $columns Values of the history
	 */
	public static function add_history(array $columns)
	{
		$result = self::$db_querier->insert(BugtrackerSetup::$bugtracker_history_table, $columns);

		return $result->get_last_inserted_id();
	}

	/**
	 * @desc Create a line in the users_filters table.
	 * @param string[] $columns Values of the filters
	 */
	public static function add_filter(array $columns)
	{
		$result = self::$db_querier->insert(BugtrackerSetup::$bugtracker_users_filters_table, $columns);

		return $result->get_last_inserted_id();
	}

	/**
	 * @desc Update a paramater of a bug.
	 * @param string $condition Restriction to apply to the list
	 * @param string[] $parameters Parameters of the condition
	 */
	public static function update_parameter(array $columns, $condition, array $parameters)
	{
		self::$db_querier->update(BugtrackerSetup::$bugtracker_table, $columns, $condition, $parameters);
	}

	/**
	 * @desc Update a history line of a bug.
	 * @param string $condition Restriction to apply to the list
	 * @param string[] $parameters Parameters of the condition
	 */
	public static function update_history(array $columns, $condition, array $parameters)
	{
		self::$db_querier->update(BugtrackerSetup::$bugtracker_history_table, $columns, $condition, $parameters);
	}

	/**
	 * @desc Update a bug.
	 * @param string[] $bug Bug to update
	 */
	public static function update(BugtrackerItem $bug)
	{
		self::$db_querier->update(BugtrackerSetup::$bugtracker_table, $bug->get_properties(), 'WHERE id=:id', array('id' => $bug->get_id()));
	}

	/**
	 * @desc Delete a bug.
	 * @param string $condition Restriction to apply to the list
	 * @param string[] $parameters Parameters of the condition
	 */
	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(BugtrackerSetup::$bugtracker_table, $condition, $parameters);
	}

	/**
	 * @desc Delete a line in the history list.
	 * @param string $condition Restriction to apply to the list
	 * @param string[] $parameters Parameters of the condition
	 */
	public static function delete_history($condition, array $parameters)
	{
		self::$db_querier->delete(BugtrackerSetup::$bugtracker_history_table, $condition, $parameters);
	}

	/**
	 * @desc Delete a line in the users_filters table.
	 * @param string $condition Restriction to apply to the list
	 * @param string[] $parameters Parameters of the condition
	 */
	public static function delete_filter($condition, array $parameters)
	{
		self::$db_querier->delete(BugtrackerSetup::$bugtracker_users_filters_table, $condition, $parameters);
	}

	/**
	 * @desc Return the content of a bug.
	 * @param string $condition Restriction to apply to the list of bugs
	 * @param string[] $parameters Parameters of the condition
	 */
	public static function get_bug($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT bugtracker.*, author.*
		FROM ' . BugtrackerSetup::$bugtracker_table . ' bugtracker
		LEFT JOIN ' . DB_TABLE_MEMBER . ' author ON author.user_id = bugtracker.author_id
		' . $condition, $parameters);

		$bug = new BugtrackerItem();
		$bug->set_properties($row);
		return $bug;
	}

	/**
	 * @desc Return the list of members which updated the bug.
	 * @param int $bug_id ID of the bug which is concerned
	 */
	public static function get_updaters_list($bug_id)
	{
		$current_user = AppContext::get_current_user();
		$bug = self::get_bug('WHERE id=:id', array('id' => $bug_id));
		$updaters_list = array();

		if ($bug->get_author_user()->get_id() != $current_user->get_id())
			$updaters_list[] = $bug->get_author_user()->get_id();

		if ($bug->get_assigned_to_id() && $bug->get_assigned_to_id() != $bug->get_author_user()->get_id() && $bug->get_assigned_to_id() != $current_user->get_id())
			$updaters_list[] = $bug->get_assigned_to_id();

		$result = self::$db_querier->select_rows(BugtrackerSetup::$bugtracker_history_table, array('updater_id'), '
		WHERE bug_id = :id AND updater_id NOT IN (:current_user_id, :author_user_id, :assigned_user_id)
		GROUP BY updater_id', array(
			'id' => $bug_id,
			'current_user_id' => $current_user->get_id(),
			'author_user_id' => $bug->get_author_user()->get_id(),
			'assigned_user_id' => $bug->get_assigned_to_id(),
		));

		while ($row = $result->fetch())
		{
			$updaters_list[] = $row['updater_id'];
		}
		$result->dispose();

		return $updaters_list;
	}
}
?>
