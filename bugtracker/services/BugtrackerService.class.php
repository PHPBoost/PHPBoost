<?php
/*##################################################
 *                        BugtrackerService.class.php
 *                            -------------------
 *   begin                : October 19, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
 * @author Julien BRISWALTER <julien.briswalter@gmail.com>
 * @desc Services of the bugtracker module
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
	public static function add(Bug $bug)
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
	 * @desc Update a bug.
	 * @param string[] $bug Bug to update
	 */
	public static function update(Bug $bug)
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
		$row = self::$db_querier->select_single_row(BugtrackerSetup::$bugtracker_table, array('*'), $condition, $parameters);
		$bug = new Bug();
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
		
		$updater_ids = self::$db_querier->select_rows(BugtrackerSetup::$bugtracker_history_table, array('updater_id'), "WHERE bug_id=:id GROUP BY updater_id", array(
			'id' => $bug_id
		), SelectQueryResult::FETCH_ASSOC);
		
		$updaters_list = array($bug->get_author_id());
		if ($bug->get_assigned_to_id() && $bug->get_assigned_to_id() != $bug->get_author_id())
			$updaters_list[] = $bug->get_assigned_to_id();
		
		while ($row = $updater_ids->fetch())
		{
			if ($row['updater_id'] != $bug->get_author_id() && $row['updater_id'] != $bug->get_assigned_to_id())
				$updaters_list[] = $row['updater_id'];
		}
		
		if (in_array($current_user->get_id(), $updaters_list))
			unset($updaters_list[array_search($current_user->get_id(), $updaters_list)]);
		
		return $updaters_list;
	}
}
?>