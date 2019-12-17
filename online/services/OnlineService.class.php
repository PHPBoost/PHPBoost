<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 3.0 - 2012 02 01
*/

class OnlineService
{
	private static $querier;

	public static function __static()
	{
		self::$querier = PersistenceContext::get_querier();
	}

	public static function get_number_users_connected($condition, $parameters, $hide_visitors = false)
	{
		if ($hide_visitors)
		{
			$number_users = 0;

			$result = self::$querier->select("SELECT s.user_id, s.cached_data, m.level
			FROM " . DB_TABLE_SESSIONS . " s
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = s.user_id "
			. $condition, $parameters);

			while ($row = $result->fetch())
			{
				if ($row['user_id'] == Session::VISITOR_SESSION_ID)
				{
					$cached_data = TextHelper::unserialize($row['cached_data']);
					$row['level'] = $cached_data['level'];
				}

				if ($row['level'] != User::VISITOR_LEVEL)
					$number_users++;
			}

			return $number_users;
		}
		else
			return self::$querier->count(DB_TABLE_SESSIONS, $condition, $parameters);
	}

	public static function get_online_users($condition, $parameters, $hide_visitors = false)
	{
		$users = array();

		$result = self::$querier->select("SELECT
		s.user_id, s.timestamp, s.location_script, s.location_title, s.cached_data,
		m.display_name, m.level, m.groups,
		f.user_avatar
		FROM " . DB_TABLE_SESSIONS . " s
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = s.user_id
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " f ON f.user_id = s.user_id "
		. $condition, $parameters);

		while ($row = $result->fetch())
		{
			if ($row['user_id'] == Session::VISITOR_SESSION_ID)
			{
				$cached_data = TextHelper::unserialize($row['cached_data']);
				$row['level'] = $cached_data['level'];
				$row['display_name'] = $cached_data['display_name'];
			}

			if (!$hide_visitors || ($row['level'] != User::VISITOR_LEVEL))
			{
				$user = new OnlineUser();
				$user->set_id($row['user_id']);
				$user->set_display_name($row['display_name']);
				$user->set_level($row['level']);
				$user->set_groups(explode('|', $row['groups']));
				$user->set_last_update(new Date($row['timestamp'], Timezone::SERVER_TIMEZONE));
				$user->set_location_script($row['location_script']);
				$user->set_location_title(stripslashes($row['location_title']));
				$user->set_avatar($row['user_avatar']);
				$users[] = $user;
			}
		}
		$result->dispose();

		return $users;
	}
}
?>
