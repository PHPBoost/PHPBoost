<?php
/*##################################################
 *                              NotationDAO.class.php
 *                            -------------------
 *   begin                : September 04, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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
 
 /**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @package {@package}
 */
class NotationDAO
{
	private static $db_querier;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}
	
	public static function insert_note(Notation $notation)
	{
		self::$db_querier->insert(DB_TABLE_NOTE, array(
			'module_name' => $notation->get_module_name(),
			'id_in_module' => $notation->get_id_in_module(),
			'user_id' => $notation->get_user_id(),
			'note' => $notation->get_note()
		));
	}
	
	public static function insert_average_notes(Notation $notation)
	{
		self::$db_querier->insert(DB_TABLE_AVERAGE_NOTES, array(
			'module_name' => $notation->get_module_name(),
			'id_in_module' => $notation->get_id_in_module(),
			'average_notes' => self::calculates_average_notes($notation),
			'number_notes' => 1
		));
	}
	
	public static function update_average_notes(Notation $notation)
	{
		$former_nbr_notes = NotationService::get_number_notes($notation);
		self::$db_querier->update(DB_TABLE_AVERAGE_NOTES, array(
			'average_notes' => self::calculates_average_notes($notation),
			'number_notes' => $former_nbr_notes + 1
		), 'WHERE module_name=:module_name AND id_in_module=:id_in_module', array(
			'module_name' => $notation->get_module_name(),
			'id_in_module' => $notation->get_id_in_module()
		));
	}

	public static function calculates_average_notes(Notation $notation)
	{
		try {
			$result = self::$db_querier->select_rows(DB_TABLE_NOTE, array('note'), 'WHERE module_name=:module_name AND id_in_module=:id_in_module', 
			array('module_name' => $notation->get_module_name(), 'id_in_module' => $notation->get_id_in_module()));
			
			$notes = 0;
			$nbr_notes = 0;
			while ($row = $result->fetch())
			{
				$notes += $row['note'];
				$nbr_notes++;
			}

			return (round(($notes / $nbr_notes) / 0.25) * 0.25);
		} catch (Exception $e) {
			return 0;
		}
	}

	public static function get_count_notes_by_id_in_module(Notation $notation)
	{
		return self::$db_querier->count(DB_TABLE_NOTE, 'WHERE module_name=:module_name AND id_in_module=:id_in_module', array(
			'module_name' => $notation->get_module_name(), 
			'id_in_module' => $notation->get_id_in_module()
		));
	}
	
	public static function get_count_average_notes_by_id_in_module(Notation $notation)
	{
		return self::$db_querier->count(DB_TABLE_AVERAGE_NOTES, 'WHERE module_name=:module_name AND id_in_module=:id_in_module', array(
			'module_name' => $notation->get_module_name(), 
			'id_in_module' => $notation->get_id_in_module()
		));
	}
	
	public static function get_count_average_notes_by_module(Notation $notation)
	{
		return self::$db_querier->count(DB_TABLE_AVERAGE_NOTES, 'WHERE module_name=:module_name', array(
			'module_name' => $notation->get_module_name()
		));
	}
	
	public static function get_member_already_noted(Notation $notation)
	{
		return self::$db_querier->count(DB_TABLE_NOTE, 'WHERE user_id=:user_id AND module_name=:module_name AND id_in_module=:id_in_module', array(
			'module_name' => $notation->get_module_name(), 
			'id_in_module' => $notation->get_id_in_module(),
			'user_id' => $notation->get_user_id()
		));
	}
	
	public static function delete_average_notes_by_id_in_module($module_name, $id_in_module)
	{
		self::$db_querier->delete(DB_TABLE_AVERAGE_NOTES, 'WHERE module_name=:module_name AND id_in_module=:id_in_module', array(
			'module_name' => $module_name, 
			'id_in_module' => $id_in_module
		));
	}
	
	public static function delete_notes_by_id_in_module($module_name, $id_in_module)
	{
		self::$db_querier->delete(DB_TABLE_NOTE, 'WHERE module_name=:module_name AND id_in_module=:id_in_module', array(
			'module_name' => $module_name, 
			'id_in_module' => $id_in_module
		));
	}
	
	public static function delete_all_average_notes_by_module($module_name)
	{
		return self::$db_querier->delete(DB_TABLE_AVERAGE_NOTES, 'WHERE module_name=:module_name', array(
			'module_name' => $module_name
		));
	}
	
	public static function delete_all_notes_by_module($module_name)
	{
		return self::$db_querier->delete(DB_TABLE_NOTE, 'WHERE module_name=:module_name', array(
			'module_name' => $module_name
		));
	}
}
?>