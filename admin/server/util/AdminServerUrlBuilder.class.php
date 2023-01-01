<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2015 05 20
*/

class AdminServerUrlBuilder
{
	private static $dispatcher = '/admin/server';

	/*
	 * @ return Url
	 */
	public static function phpinfo()
	{
		return DispatchManager::get_url(self::$dispatcher, '/phpinfo/');
	}

	/*
	 * @ return Url
	 */
	public static function system_report()
	{
		return DispatchManager::get_url(self::$dispatcher, '/report/');
	}
}
?>
