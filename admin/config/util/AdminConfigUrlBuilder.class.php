<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 09 20
*/

class AdminConfigUrlBuilder
{
	private static $dispatcher = '/admin/config';

	/*
	 * @ return Url
	 */
	public static function general_config()
	{
		return DispatchManager::get_url(self::$dispatcher, '/general/');
	}

	/*
	 * @ return Url
	 */
	public static function advanced_config()
	{
		return DispatchManager::get_url(self::$dispatcher, '/advanced/', true);
	}

	/*
	 * @ return Url
	 */
	public static function mail_config()
	{
		return DispatchManager::get_url(self::$dispatcher, '/mail/');
	}
}
?>
