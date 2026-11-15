<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 3.0 - 2012 05 05
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminMembersUrlBuilder
{
	private static $dispatcher = '/admin/users/member';

	public static function management(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/management/');
	}

	public static function add(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/');
	}

	public static function delete(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, $id. '/delete/');
	}

	public static function configuration(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/config/');
	}
}
?>
