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
		return self::get_current_robot_name() !== null;
	}
	
	public static function get_current_robot_name()
	{
		$user_agent = AppContext::get_request()->get_user_agent();
		
		if (!empty($user_agent))
		{
			foreach (self::get_robots_list() as $robot)
			{
				if (TextHelper::stripos($user_agent, $robot) !== false)
					return $robot;
			}
			if (preg_match('`(http:\/\/|bot|spider|crawl)+`iu', $user_agent))
			{
				return 'unknow_bot';
			}
		}

		return null;
	}
	
	// Robots list from https://udger.com/resources/ua-list/crawlers?c=1
	private static function get_robots_list()
	{
		return array(
			'Googlebot',
			'Bingbot',
			'Yahoo!',
			'DuckDuckBot',
			'Baiduspider',
			'YandexBot',
			'Sogou',
			'Exabot',
			'Facebot',
			'Qwantify',
			'Applebot',
			'13TABS',
			'360Spider',
			'AntBot',
			'Apexoo Spider',
			'Barkrowler',
			'BehloolBot',
			'CarianBot',
			'Cliqzbot',
			'coccocbot',
			'Daumoa',
			'DeuSu',
			'Elefent',
			'exif-search',
			'Findxbot',
			'Gigabot',
			'glindahl-cocrawler',
			'Gowikibot',
			'IstellaBot',
			'KD Bot',
			'KOCMOHABT bot',
			'Laserlikebot',
			'LetsearchBot',
			'Mail.Ru bot',
			'MojeekBot',
			'NaverBot',
			'omgilibot',
			'parsijoo-bot',
			'Plukkie',
			'psbot',
			'Seekport Crawler',
			'SeznamBot',
			'SnowHaze SearcH',
			'SOLOFIELD bot',
			'Sosospider',
			'TarmotGezgin',
			'TeeRaidBot',
			'TinEye',
			'Toweyabot',
			'UptimeRobot',
			'vebidoobot',
			'WBSearchBot',
			'Wotbox',
			'yacybot',
			'YioopBot',
			'YisouSpider',
			'Yooo bot',
			'yoozBot'
		);
	}
}
?>