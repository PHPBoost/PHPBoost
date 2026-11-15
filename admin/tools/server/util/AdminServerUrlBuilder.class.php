<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 4.1 - 2015 05 20
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminServerUrlBuilder
{
	private static $dispatcher = '/admin/tools/server';

	public static function phpinfo(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/phpinfo/');
	}

	public static function system_report(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/report/');
	}
}
?>
