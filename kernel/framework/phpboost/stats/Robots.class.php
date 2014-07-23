<?php 
/*##################################################
 *                                Robots.php
 *                            -------------------
 *   begin                : January 06, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class Robots
{
	public static function is_robot()
	{
		return self::get_current_robot() !== null;
	}
	
	public static function get_robot_by_ip($user_ip)
	{
		return self::detect_robot_by_ip($user_ip);
	}
	
	public static function get_current_robot()
	{
		return self::detect_robot();
	}
	
	private static function detect_robot()
	{
		$robot_by_user_agent = self::detect_robot_by_user_agent();
		$robot_by_ip = self::detect_robot_by_ip();
		
		if ($robot_by_user_agent !== null)
		{
			return $robot_by_user_agent;
		}	
		else if ($robot_by_ip !== null)
		{
			return $robot_by_ip;
		}
		else
		{
			return 'unknow_bot';
		}
	}
	
	private static function detect_robot_by_user_agent()
	{
		$_SERVER['HTTP_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

		if (strpos($_SERVER['HTTP_USER_AGENT'], 'Google') !== false)
		{
			return 'Google bot';
		}
		elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Bing') !== false)
		{
			return 'Bing bot';
		}
		elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Yahoo') !== false)
		{
			return 'Yahoo Slurp';
		}
		
		return null;
	}
	
	private static function detect_robot_by_ip($user_ip = '')
	{
		$plage_ip = array(
			'66.249.64.0' => '66.249.95.255',
			'209.85.128.0' => '209.85.255.255',
			'65.52.0.0' => '65.55.255.255',
			'207.68.128.0' => '207.68.207.255',
			'66.196.64.0' => '66.196.127.255',
			'68.142.192.0' => '68.142.255.255',
			'72.30.0.0' => '72.30.255.255',
			'193.252.148.0' => '193.252.148.255',
			'66.154.102.0' => '66.154.103.255',
			'209.237.237.0' => '209.237.238.255',
			'193.47.80.0' => '193.47.80.255'
		);
		
		$array_robots = array(
			'Google bot',
			'Google bot',
			'Bing bot',
			'Bing bot',
			'Yahoo Slurp',
			'Yahoo Slurp',
			'Yahoo Slurp',
			'Voila',
			'Gigablast',
			'Ia archiver',
			'Exalead'
		);
		
		//Ip de l'utilisateur au format numérique.
		$user_ip = !empty($user_ip) ? $user_ip : AppContext::get_current_user()->get_ip();
		$user_ip = ip2long($user_ip);

		//On explore le tableau pour identifier les robots
		$i = 0;
		foreach ($plage_ip as $start_ip => $end_ip)
		{
			$start_ip = ip2long($start_ip);
			$end_ip = ip2long($end_ip);
			
			//Comparaison pour chaque partie de l'ip, si l'une d'entre elle est fausse l'instruction est stopée.
			if ($user_ip >= $start_ip && $user_ip <= $end_ip)
			{
				return $array_robots[$i]; //On retourne le nom du robot d'exploration.
			}
			$i++;
		}
		return null;
	}
}
?>