<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 3.0 - 2012 11 30
*/

class GuestbookService
{
	private static $db_querier;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	public static function count($condition = '')
	{
		return self::$db_querier->count(GuestbookSetup::$guestbook_table, $condition);
	}

	public static function add(GuestbookMessage $message)
	{
		$result = self::$db_querier->insert(GuestbookSetup::$guestbook_table, $message->get_properties());

		return $result->get_last_inserted_id();
	}

	public static function update(GuestbookMessage $message)
	{
		self::$db_querier->update(GuestbookSetup::$guestbook_table, $message->get_properties(), 'WHERE id=:id', array('id' => $message->get_id()));
	}

	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(GuestbookSetup::$guestbook_table, $condition, $parameters);
	}

	public static function get_message($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT member.*, guestbook.*, guestbook.login as glogin
		FROM ' . GuestbookSetup::$guestbook_table . ' guestbook
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = guestbook.user_id
		' . $condition, $parameters);

		$message = new GuestbookMessage();
		$message->set_properties($row);
		return $message;
	}

	public static function get_last_message_timestamp_from_user($user_id)
	{
		return self::$db_querier->get_column_value(GuestbookSetup::$guestbook_table, 'MAX(timestamp) as timestamp', 'WHERE user_id=:user_id', array(
			'user_id' => $user_id
		));
	}
}
?>
