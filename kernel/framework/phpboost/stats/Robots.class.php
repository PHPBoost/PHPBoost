<?php
/**
 * @package     PHPBoost
 * @subpackage  Stats
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 22
 * @since       PHPBoost 4.0 - 2013 01 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

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
			'Yahoo',
			'DuckDuckBot',
			'Baiduspider',
			'YandexBot',
			'Sogou',
			'Exabot',
			'Exalead',
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
