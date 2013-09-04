<?php
/*##################################################
 *                        CalendarService.class.php
 *                            -------------------
 *   begin                : November 20, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
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
	 * @desc Count the events list.
	 * @param string $condition (optional) Restriction to apply to the list
	 */
	public static function count($condition = '')
	{
		return self::$db_querier->count(CalendarSetup::$calendar_table, $condition);
	}
	
	 /**
	 * @desc Count the the number of registered members for an event.
	 * @param string $event_id id of the event
	 */
	public static function count_registered_members($event_id)
	{
		return self::$db_querier->count(CalendarSetup::$calendar_users_relation_table, 'WHERE event_id=:event_id', array(
			'event_id' => $event_id
		));
	}
	
	 /**
	 * @desc Create a new event.
	 * @param string[] $event new CalendarEvent
	 */
	public static function add(CalendarEvent $event)
	{
		$result = self::$db_querier->insert(CalendarSetup::$calendar_table, $event->get_properties());
		
		return $result->get_last_inserted_id();
	}
	
	 /**
	 * @desc Add a participant of an event.
	 * @param int $event_id id of the event
	 * @param int $user_id id of the participant to add
	 */
	public static function add_participant($event_id, $user_id)
	{
		self::$db_querier->insert(CalendarSetup::$calendar_users_relation_table, array(
			'event_id' => $event_id,
			'user_id' => $user_id
		));
	}
	
	 /**
	 * @desc Update an event.
	 * @param string[] $event CalendarEvent to update
	 */
	public static function update(CalendarEvent $event)
	{
		self::$db_querier->update(CalendarSetup::$calendar_table, $event->get_properties(), 'WHERE id=:id', array(
			'id' => $event->get_id()
		));
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
	 * @desc Delete the participants of an event.
	 * @param int $event_id id of the event
	 */
	public static function delete_participants($event_id)
	{
		self::$db_querier->delete(CalendarSetup::$calendar_users_relation_table, 'WHERE event_id=:id', array(
			'id' => $event_id
		));
	}
	
	 /**
	 * @desc Delete a participant of an event.
	 * @param int $event_id id of the event
	 * @param int $user_id id of the participant to delete
	 */
	public static function delete_participant($event_id, $user_id)
	{
		self::$db_querier->delete(CalendarSetup::$calendar_users_relation_table, 'WHERE event_id=:event_id AND user_id=:user_id', array(
			'event_id' => $event_id,
			'user_id' => $user_id
		));
	}
	
	 /**
	 * @desc Return the content of an event.
	 * @param string $condition Restriction to apply to the list of events
	 * @param string[] $parameters Parameters of the condition
	 */
	public static function get_event($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT *
		FROM ' . CalendarSetup::$calendar_table . ' calendar
		LEFT JOIN ' . DB_TABLE_MEMBER . ' author ON author.user_id = calendar.author_id
		' . $condition, $parameters);
		
		$event = new CalendarEvent();
		$event->set_properties($row);
		return $event;
	}
	
	 /**
	 * @desc Return the participants of an event.
	 * @param int $event_id id of the event
	 */
	public static function get_event_participants($event_id)
	{
		$participants = array();
		
		$result = self::$db_querier->select('SELECT *
		FROM ' . CalendarSetup::$calendar_users_relation_table . ' participants
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = participants.user_id
		WHERE event_id = :id', array(
			'id' => $event_id
		));
		
		while($row = $result->fetch())
		{
			$user = new User();
			$user->set_properties($row);
			$participants[$user->get_id()] = $user;
		}
		
		return $participants;
	}
	
	 /**
	 * @desc Return all the events of the requested month.
	 * @param int $month Month of the request
	 * @param int $year Year of the request
	 * @param int $month_days Number of days in the requested month
	 */
	public static function get_all_current_month_events($month, $year, $month_days, $id_category = Category::ROOT_CATEGORY)
	{
		return self::$db_querier->select((CalendarConfig::load()->is_members_birthday_enabled() ? "
		(SELECT user_born AS start_date, user_born AS end_date, login AS title, 'BIRTHDAY' AS type, 0 AS id_category, '" . CalendarEvent::YEARLY . "' AS repeat_type, 130 AS repeat_number
		FROM " . DB_TABLE_MEMBER . " member
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " member_extended_fields ON member_extended_fields.user_id = member.user_id
		WHERE MONTH(FROM_UNIXTIME(user_born)) = :month)
		UNION
		" : "") . "(SELECT start_date, end_date, title, 'EVENT' AS type, id_category, repeat_type, repeat_number
		FROM " . CalendarSetup::$calendar_table. "
		WHERE start_date BETWEEN :first_month_day AND :last_month_day)
		ORDER BY start_date", array(
			'month' => $month,
			'first_month_day' => mktime(0, 0, 0, $month, 1, $year),
			'last_month_day' => mktime(23, 59, 59, $month, $month_days, $year)
		));
	}
	
	 /**
	 * @desc Return the authorized categories.
	 */
	public static function get_authorized_categories($current_id_category)
	{
		$authorized_categories = array();
		if ($current_id_category !== Category::ROOT_CATEGORY)
		{
			$authorized_categories[] = $current_id_category;
		}
		else
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
			$categories = self::get_categories_manager()->get_childrens($current_id_category, $search_category_children_options);
			$authorized_categories = array_keys($categories);
		}
		return $authorized_categories;
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
