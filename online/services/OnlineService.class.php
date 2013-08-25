<?php
/*##################################################
 *                        OnlineService.class.php
 *                            -------------------
 *   begin                : February 1, 2012
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

class OnlineService
{
	private static $querier;
	
	public static function __static()
	{
		self::$querier = PersistenceContext::get_querier();
	}
	
	public static function get_number_users_connected($condition, $parameters)
	{
		return self::$querier->count(DB_TABLE_SESSIONS, $condition, $parameters);
	}
	
	public static function get_online_users($condition, $parameters)
	{
		$users = array();
		
		$result = self::$querier->select("SELECT 
		s.user_id, s.level, s.session_time, s.session_script, s.session_script_get, s.session_script_title,
		m.login, m.user_groups,
		f.user_avatar
		FROM " . DB_TABLE_SESSIONS . " s
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = s.user_id 
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " f ON f.user_id = s.user_id "
		. $condition, $parameters);
		
		while ($row = $result->fetch())
		{
			$row['session_script_get'] = !empty($row['session_script_get']) ? '?' . $row['session_script_get'] : '';
			$user = new OnlineUser();
			$user->set_id($row['user_id']);
			$user->set_pseudo($row['login']);
			$user->set_level($row['level']);
			$user->set_groups(explode('|', $row['user_groups']));
			$user->set_last_update(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['session_time']));
			$user->set_location_script($row['session_script'] . $row['session_script_get']);
			$user->set_location_title(stripslashes($row['session_script_title']));
			$user->set_avatar($row['user_avatar']);
			$users[] = $user;
		}
		
		return $users;
	}
}
?>
