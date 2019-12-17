<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 16
 * @since       PHPBoost 3.0 - 2012 11 20
*/

class CalendarService
{
	private static $db_querier;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	 /**
	 * @desc Create a new event.
	 * @param string[] $event new CalendarEvent
	 */
	public static function add_event(CalendarEvent $event)
	{
		$result = self::$db_querier->insert(CalendarSetup::$calendar_events_table, $event->get_properties());

		return $result->get_last_inserted_id();
	}

	 /**
	 * @desc Create a new event content.
	 * @param string[] $event_content new CalendarEventContent
	 */
	public static function add_event_content(CalendarEventContent $event_content)
	{
		$result = self::$db_querier->insert(CalendarSetup::$calendar_events_content_table, $event_content->get_properties());

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
	public static function update_event(CalendarEvent $event)
	{
		self::$db_querier->update(CalendarSetup::$calendar_events_table, $event->get_properties(), 'WHERE id_event = :id', array(
			'id' => $event->get_id()
		));

		return $event->get_id();
	}

	 /**
	 * @desc Update the content of an event.
	 * @param string[] $event_content CalendarEventContent to update
	 */
	public static function update_event_content(CalendarEventContent $event_content)
	{
		self::$db_querier->update(CalendarSetup::$calendar_events_content_table, $event_content->get_properties(), 'WHERE id = :id', array(
			'id' => $event_content->get_id()
		));
	}

	 /**
	 * @desc Delete an event.
	 * @param string $condition Restriction to apply to the list of events
	 * @param string[] $parameters Parameters of the condition
	 */
	public static function delete_event(int $id, bool $has_parent)
	{
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
		
		self::$db_querier->delete(CalendarSetup::$calendar_events_table, 'WHERE id=:id', array('id' => $id));

		if (!$has_parent)
			PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'calendar', 'id' => $id));

		CommentsService::delete_comments_topic_module('calendar', $id);

		//Delete participants
		self::delete_all_participants($id);
	}

	 /**
	 * @desc Delete the content of an event.
	 * @param string $condition Restriction to apply to the list of events content
	 * @param string[] $parameters Parameters of the condition
	 */
	public static function delete_event_content($condition, array $parameters)
	{
		self::$db_querier->delete(CalendarSetup::$calendar_events_content_table, $condition, $parameters);
	}

	 /**
	 * @desc Delete a serie of events.
	 * @param int $content_id id of the content of the event
	 */
	public static function delete_all_serie_events($content_id)
	{
		self::delete_event('WHERE content_id = :id', array(
			'id' => $content_id
		));

		self::delete_event_content('WHERE id = :id', array(
			'id' => $content_id
		));
	}

	 /**
	 * @desc Delete the participants of an event.
	 * @param int $event_id id of the event
	 */
	public static function delete_all_participants($event_id)
	{
		self::$db_querier->delete(CalendarSetup::$calendar_users_relation_table, 'WHERE event_id = :id', array(
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
		self::$db_querier->delete(CalendarSetup::$calendar_users_relation_table, 'WHERE event_id = :event_id AND user_id = :user_id', array(
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
		FROM ' . CalendarSetup::$calendar_events_table . ' event
		LEFT JOIN ' . CalendarSetup::$calendar_events_content_table . ' event_content ON event_content.id = event.content_id
		LEFT JOIN ' . DB_TABLE_MEMBER . ' author ON author.user_id = event_content.author_id
		' . $condition, $parameters);

		$event = new CalendarEvent();
		$event->set_properties($row);
		$event->set_participants(self::get_event_participants($event->get_id()));

		return $event;
	}

	 /**
	 * @desc Return the participants of an event.
	 * @param int $event_id id of the event
	 */
	public static function get_event_participants($event_id)
	{
		$participants = array();

		$result = self::$db_querier->select('SELECT event_id, member.user_id, display_name, level, groups
		FROM ' . CalendarSetup::$calendar_users_relation_table . ' participants
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = participants.user_id
		WHERE event_id = :id', array(
			'id' => $event_id
		));

		while($row = $result->fetch())
		{
			if (!empty($row['display_name']))
			{
				$participant = new CalendarEventParticipant();
				$participant->set_properties($row);
				$participants[$participant->get_user_id()] = $participant;
			}
		}
		$result->dispose();

		return $participants;
	}

	 /**
	 * @desc Return the events of a serie.
	 * @param int $content_id id of the content of the event
	 */
	public static function get_serie_events($content_id)
	{
		$events = array();

		$result = self::$db_querier->select('SELECT *
		FROM ' . CalendarSetup::$calendar_events_table . ' event
		LEFT JOIN ' . CalendarSetup::$calendar_events_content_table . ' event_content ON event_content.id = event.content_id
		LEFT JOIN ' . DB_TABLE_MEMBER . ' author ON author.user_id = event_content.author_id
		WHERE content_id = :id', array(
			'id' => $content_id
		));

		while($row = $result->fetch())
		{
			$event = new CalendarEvent();
			$event->set_properties($row);
			$events[$event->get_id()] = $event;
		}
		$result->dispose();

		return $events;
	}

	 /**
	 * @desc Clears all module elements in cache.
	 */
	public static function clear_cache()
	{
		Feed::clear_cache('calendar');
		CalendarCurrentMonthEventsCache::invalidate();
	}

	 /**
	 * @desc Return all the events of the requested month.
	 * @param int $month Month of the request
	 * @param int $year Year of the request
	 * @param int $month_days Number of days in the requested month
	 */
	public static function get_all_current_month_events($month, $year, $month_days, $id_category = Category::ROOT_CATEGORY)
	{
		$authorized_categories = CategoriesService::get_authorized_categories($id_category);

		return self::$db_querier->select((CalendarConfig::load()->is_members_birthday_enabled() ? "
		(SELECT member_extended_fields.user_born AS start_date, member_extended_fields.user_born AS end_date, display_name AS title, 'BIRTHDAY' AS type, 0 AS id_category, '" . CalendarEventContent::YEARLY . "' AS repeat_type, 100 AS repeat_number
		FROM " . DB_TABLE_MEMBER . " member
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " member_extended_fields ON member_extended_fields.user_id = member.user_id
		WHERE member_extended_fields.user_born <> '' AND IF(member_extended_fields.user_born < 0, MONTH(DATE_ADD(FROM_UNIXTIME(0), INTERVAL member_extended_fields.user_born second)), MONTH(FROM_UNIXTIME(member_extended_fields.user_born))) = :month AND :year > IF(member_extended_fields.user_born < 0, YEAR(DATE_ADD(FROM_UNIXTIME(0), INTERVAL member_extended_fields.user_born second)), YEAR(FROM_UNIXTIME(member_extended_fields.user_born))))
		UNION
		" : "") . "(SELECT start_date, end_date, title, 'EVENT' AS type, id_category, repeat_type, repeat_number
		FROM " . CalendarSetup::$calendar_events_table . " event
		LEFT JOIN " . CalendarSetup::$calendar_events_content_table . " event_content ON event_content.id = event.content_id
		WHERE approved = 1
		AND ((start_date BETWEEN :first_month_day AND :last_month_day) OR (end_date BETWEEN :first_month_day AND :last_month_day) OR (:first_month_day BETWEEN start_date AND end_date))
		AND id_category IN :authorized_categories)
		ORDER BY type ASC, start_date ASC", array(
			'month' => $month,
			'year' => $year,
			'first_month_day' => mktime(0, 0, 0, $month, 1, $year),
			'last_month_day' => mktime(23, 59, 59, $month, $month_days, $year),
			'authorized_categories' => $authorized_categories
		));
	}
}
?>
