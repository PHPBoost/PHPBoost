<?php
/**
 * @package     PHPBoost
 * @subpackage  Stats
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 27
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
					$user_agent = $robot;
			}
			if (preg_match('`(http:\/\/|bot|spider|crawl)+`iu', $user_agent))
			{
				$result = (preg_match('`x86_64; ([^/]+)`iu', $user_agent, $matches) || preg_match('`x64; ([^/]+)`iu', $user_agent, $matches) || preg_match('`compatible; ([^/]+)`iu', $user_agent, $matches)) ? $matches[1] : $user_agent;
				$result = preg_split('`(\ |;|\/|\+)`', $result);
				$result = TextHelper::ucfirst(str_replace(array('(', ')'), '', $result[0]));
				$result = preg_replace('`bot[0-9\.]+$`iu', 'Bot', $result);
				$result = (preg_match('`bot`iu', $result) && !preg_match('`robot`iu', $result)) ? preg_replace('`bot$`iu', 'Bot', $result) : $result;
				$result = preg_replace('`spider$`iu', 'Spider', $result);
				$result = ($result == "Sogou" ? 'Sogou Spider' : $result);
				return ($result == "Mozilla" ? 'unknow_bot' : $result);
			}
		}

		return null;
	}

	// Robots with user-agents that don't match the regex
	private static function get_robots_list()
	{
		return array(
			'360Spider',
			'Applebot',
			'AppEngine-Google',
			'YisouSpider',
		);
	}
}
?>
