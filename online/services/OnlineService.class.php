<?php
/*##################################################
 *                        NewsletterService.class.php
 *                            -------------------
 *   begin                : February 01, 2012
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

class OnlineService
{
	private static $querier;
	
	public static function __static()
	{
		self::$querier = PersistenceContext::get_querier();
	}
	
	public static function get_nbr_users_connected($condition, $parameters)
	{
		$row = self::$querier->select_single_row(DB_TABLE_SESSIONS, array('count(*) as total'), $condition, $parameters);
		return $row['total'];
	}
	
	public static function get_online_users_list($condition, $parameters)
	{
		$display_order = OnlineConfig::load()->get_display_order();
		$user_list[] = '';
		
		$result = self::$querier->select("SELECT s.user_id as id, s.level, s.session_time, s.session_script, s.session_script_get, s.session_script_title, m.login, m.user_groups
		FROM " . DB_TABLE_SESSIONS . " s
		JOIN " . DB_TABLE_MEMBER . " m ON (m.user_id = s.user_id) "
		. $condition, $parameters, SelectQueryResult::FETCH_ASSOC);
		
		$i = 0;
		while ($row = $result->fetch())
		{
			$row['session_script_get'] = !empty($row['session_script_get']) ? '?' . $row['session_script_get'] : '';
			$user = new OnlineUser();
			$user->set_id($row['id']);
			$user->set_pseudo($row['login']);
			$user->set_level($row['level']);
			$user->set_groups(explode('|', $row['user_groups']));
			$user->set_last_update(gmdate_format('date_format_long', $row['session_time']));
			$user->set_location_script(HOST . DIR . $row['session_script'] . $row['session_script_get']);
			$user->set_location_title(stripslashes($row['session_script_title']));
			$user_list[$i] = $user;
			$i++;
		}
		
		return $user_list;
	}
}

?>