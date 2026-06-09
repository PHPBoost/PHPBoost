<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2011 09 20
*/

class AdminConfigUrlBuilder
{
	private static string $dispatcher = '/admin/config';

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

	public static function addons_config(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/addons/');
	}
}
?>
