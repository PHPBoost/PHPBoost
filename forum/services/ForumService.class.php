<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 11 11
 * @since       PHPBoost 4.1 - 2015 02 25
*/

class ForumService
{
	private static $db_querier;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	/**
	 * @desc Count topics number.
	 * @param string $condition (optional) : Restriction to apply to the list of topics
	 */
	public static function count_topics($condition = '', $parameters = array())
	{
		return self::$db_querier->count(ForumSetup::$forum_topics_table, $condition, $parameters);
	}

	/**
	 * @desc Count messages number.
	 * @param string $condition (optional) : Restriction to apply to the list of messages
	 */
	public static function count_messages($condition = '', $parameters = array())
	{
		$messages_number = 0;
		try {
			$messages_number = PersistenceContext::get_querier()->get_column_value(ForumSetup::$forum_topics_table, 'SUM(nbr_msg)', $condition, $parameters);
		} catch (RowNotFoundException $e) {}

		return $messages_number;
	}
}
?>
