<?php
/*##################################################
 *                        CalendarService.class.php
 *                            -------------------
 *   begin                : November 20, 2012
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
 * @desc Services of the calendar module
 */
class CalendarService
{
	private static $db_querier;
	
	private static $categories_manager;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}
	
	 /**
	 * @desc Create a new event.
	 * @param string[] $event new Event
	 */
	public static function add(Event $event)
	{
		$result = self::$db_querier->insert(CalendarSetup::$calendar_table, $event->get_properties());
		
		return $result->get_last_inserted_id();
	}
	
	 /**
	 * @desc Update an event.
	 * @param string[] $event Event to update
	 */
	public static function update(Event $event)
	{
		self::$db_querier->update(NewsSetup::$news_table, $event->get_properties(), 'WHERE id=:id', array('id' => $event->get_id()));
	}
	
	 /**
	 * @desc Delete an event.
	 * @param string $condition Restriction to apply to the list of events
	 * @param string[] $parameters Parameters of the condition
	 */
	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(CalendarSetup::$calendar_table, $condition, $parameters);
	}
	
	 /**
	 * @desc Return the content of an event.
	 * @param string $condition Restriction to apply to the list of events
	 * @param string[] $parameters Parameters of the condition
	 */
	public static function get_event($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row(CalendarSetup::$calendar_table, array('*'), $condition, $parameters);
		$event = new Event();
		$event->set_properties($row);
		return $event;
	}
	
	 /**
	 * @desc Return the categories manager.
	 */
	public static function get_categories_manager()
	{
		if (self::$categories_manager === null)
		{
			$categories_items_parameters = new CategoriesItemsParameters();
			$categories_items_parameters->set_table_name_contains_items(CalendarSetup::$calendar_table);
			self::$categories_manager = new CategoriesManager(CalendarCategoriesCache::load(), $categories_items_parameters);
		}
		return self::$categories_manager;
	}
}
?>