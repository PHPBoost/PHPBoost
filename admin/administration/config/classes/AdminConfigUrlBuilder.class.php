<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 3.0 - 2011 09 20
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminConfigUrlBuilder
{
	private static string $dispatcher = '/admin/administration/config';

	public static function general_config(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/general/');
	}

	public static function advanced_config(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/advanced/', true);
	}

	public static function mail_config(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/mail/');
	}
}
?>
