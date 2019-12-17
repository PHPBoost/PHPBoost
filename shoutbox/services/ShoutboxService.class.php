<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2014 10 15
*/

class ShoutboxService
{
	private static $db_querier;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	public static function count($condition = '')
	{
		return self::$db_querier->count(ShoutboxSetup::$shoutbox_table, $condition);
	}

	public static function add(ShoutboxMessage $message)
	{
		$result = self::$db_querier->insert(ShoutboxSetup::$shoutbox_table, $message->get_properties());

		return $result->get_last_inserted_id();
	}

	public static function update(ShoutboxMessage $message)
	{
		self::$db_querier->update(ShoutboxSetup::$shoutbox_table, $message->get_properties(), 'WHERE id=:id', array('id' => $message->get_id()));
	}

	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(ShoutboxSetup::$shoutbox_table, $condition, $parameters);
	}

	public static function get_message($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT member.*, shoutbox.*
		FROM ' . ShoutboxSetup::$shoutbox_table . ' shoutbox
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = shoutbox.user_id
		' . $condition, $parameters);

		$message = new ShoutboxMessage();
		$message->set_properties($row);
		return $message;
	}

	public static function get_last_message_timestamp_from_user($user_id)
	{
		return self::$db_querier->get_column_value(ShoutboxSetup::$shoutbox_table, 'MAX(timestamp) as timestamp', 'WHERE user_id=:user_id', array(
			'user_id' => $user_id
		));
	}
}
?>
