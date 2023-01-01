<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 14
 * @since       PHPBoost 3.0 - 2012 11 20
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarService
{
	private static $db_querier;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	/**
	 * @desc Create a new item.
	 * @param string[] $item new CalendarItem
	 */
	public static function add_item(CalendarItem $item)
	{
		$result = self::$db_querier->insert(CalendarSetup::$calendar_events_table, $item->get_properties());

		return $result->get_last_inserted_id();
	}

	/**
	 * @desc Create a new item content.
	 * @param string[] $item_content new CalendarItemContent
	 */
	public static function add_item_content(CalendarItemContent $item_content)
	{
		$result = self::$db_querier->insert(CalendarSetup::$calendar_events_content_table, $item_content->get_properties());

		return $result->get_last_inserted_id();
	}

	/**
	 * @desc Add a participant of an item.
	 * @param int $item_id id of the item
	 * @param int $user_id id of the participant to add
	 */
	public static function add_participant($item_id, $user_id)
	{
		self::$db_querier->insert(CalendarSetup::$calendar_users_relation_table, array(
			'event_id' => $item_id,
			'user_id' => $user_id
		));
	}

	/**
	 * @desc Update an item.
	 * @param string[] $item CalendarItem to update
	 */
	public static function update_item(CalendarItem $item)
	{
		self::$db_querier->update(CalendarSetup::$calendar_events_table, $item->get_properties(), 'WHERE id_event = :id', array(
			'id' => $item->get_id()
		));

		return $item->get_id();
	}

	/**
	 * @desc Update the content of an item.
	 * @param string[] $item_content CalendarItemContent to update
	 */
	public static function update_item_content(CalendarItemContent $item_content)
	{
		self::$db_querier->update(CalendarSetup::$calendar_events_content_table, $item_content->get_properties(), 'WHERE id = :id', array(
			'id' => $item_content->get_id()
		));
	}

	/**
	 * @desc Delete an item.
	 * @param int $id id of the item
	 * @param bool $has_parent Complete delete if event doesn't have a parent (complete delete), false per default
	 * @param string $id_label table column to take into consideration. id_event per default
	 */
	public static function delete_item(int $id, bool $has_parent = false, $id_label = 'id_event')
	{
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}

		self::$db_querier->delete(CalendarSetup::$calendar_events_table, 'WHERE ' . $id_label . '=:id', array('id' => $id));

		if (!$has_parent)
			PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'calendar', 'id' => $id));

		CommentsService::delete_comments_topic_module('calendar', $id);

		//Delete participants
		self::delete_all_participants($id);
	}

	/**
	 * @desc Delete the content of an item.
	 * @param int $id id of the content of the item
	 */
	public static function delete_item_content($id)
	{
		self::$db_querier->delete(CalendarSetup::$calendar_events_content_table, 'WHERE id = :id', array('id' => $id));
	}

	/**
	 * @desc Delete a serie of items.
	 * @param int $content_id id of the content of the item
	 */
	public static function delete_all_serie_items($content_id)
	{
		self::delete_item_content($content_id);
		self::delete_item($content_id, false, 'content_id');
	}

	/**
	 * @desc Delete the participants of an item.
	 * @param int $item_id id of the item
	 */
	public static function delete_all_participants($item_id)
	{
		self::$db_querier->delete(CalendarSetup::$calendar_users_relation_table, 'WHERE event_id = :id', array(
			'id' => $item_id
		));
	}

	/**
	 * @desc Delete a participant of an item.
	 * @param int $item_id id of the item
	 * @param int $user_id id of the participant to delete
	 */
	public static function delete_participant($item_id, $user_id)
	{
		self::$db_querier->delete(CalendarSetup::$calendar_users_relation_table, 'WHERE event_id = :event_id AND user_id = :user_id', array(
			'event_id' => $item_id,
			'user_id' => $user_id
		));
	}

	/**
	 * @desc Return the content of an item.
	 * @param int $id Item identifier
	 */
	public static function get_item(int $id)
	{
		$row = self::$db_querier->select_single_row_query('SELECT *
		FROM ' . CalendarSetup::$calendar_events_table . ' event
		LEFT JOIN ' . CalendarSetup::$calendar_events_content_table . ' event_content ON event_content.id = event.content_id
		LEFT JOIN ' . DB_TABLE_MEMBER . ' author ON author.user_id = event_content.author_user_id
		WHERE event.id_event=:id', array(
			'id' => $id
		));

		$item = new CalendarItem();
		$item->set_properties($row);
		$item->set_participants(self::get_item_participants($item->get_id()));

		return $item;
	}

	/**
	 * @desc Return the participants of an item.
	 * @param int $item_id id of the item
	 */
	public static function get_item_participants($item_id)
	{
		$participants = array();

		$result = self::$db_querier->select('SELECT event_id, member.user_id, display_name, level, user_groups
		FROM ' . CalendarSetup::$calendar_users_relation_table . ' participants
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = participants.user_id
		WHERE event_id = :id', array(
			'id' => $item_id
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
	 * @desc Return the items of a serie.
	 * @param int $content_id id of the content of the item
	 */
	public static function get_serie_items($content_id)
	{
		$items = array();

		$result = self::$db_querier->select('SELECT *
		FROM ' . CalendarSetup::$calendar_events_table . ' event
		LEFT JOIN ' . CalendarSetup::$calendar_events_content_table . ' event_content ON event_content.id = event.content_id
		LEFT JOIN ' . DB_TABLE_MEMBER . ' author ON author.user_id = event_content.author_user_id
		WHERE content_id = :id', array(
			'id' => $content_id
		));

		while($row = $result->fetch())
		{
			$item = new CalendarItem();
			$item->set_properties($row);
			$items[$item->get_id()] = $item;
		}
		$result->dispose();

		return $items;
	}

	/**
	 * @desc Clears all module elements in cache.
	 */
	public static function clear_cache()
	{
		Feed::clear_cache('calendar');
		CalendarCache::invalidate();
	}

	/**
	 * @desc Return all the items of the requested month.
	 * @param int $month Month of the request
	 * @param int $year Year of the request
	 * @param int $month_days Number of days in the requested month
	 */
	public static function get_all_current_month_items($month, $year, $month_days, $id_category = Category::ROOT_CATEGORY)
	{
		$items = array();
		$authorized_categories = $id_category == Category::ROOT_CATEGORY ? CategoriesService::get_authorized_categories($id_category) : array($id_category);

		$first_month_day = DateTime::createFromFormat('Y-m-d H:i:s', $year . '-' . $month . '-' . 1 . ' 00:00:00', Timezone::get_timezone(Timezone::USER_TIMEZONE));
		$last_month_day = DateTime::createFromFormat('Y-m-d H:i:s', $year . '-' . $month . '-' . $month_days . ' 23:59:59', Timezone::get_timezone(Timezone::USER_TIMEZONE));
		$result = self::$db_querier->select((CalendarConfig::load()->is_members_birthday_enabled() ? "
		(SELECT member_extended_fields.user_born AS start_date, member_extended_fields.user_born AS end_date, display_name AS title, 'BIRTHDAY' AS type, 0 AS id_category, '" . CalendarItemContent::YEARLY . "' AS repeat_type, 100 AS repeat_number
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
			'first_month_day' => $first_month_day->getTimestamp(),
			'last_month_day' => $last_month_day->getTimestamp(),
			'authorized_categories' => $authorized_categories
		));
		
		while ($row = $result->fetch())
		{
			$items[] = $row;
		}
		$result->dispose();

		return $items;
	}
}
?>
