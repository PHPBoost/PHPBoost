<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 14
 * @since       PHPBoost 3.0 - 2009 12 13
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminErrorsUrlBuilder
{
	private static $dispatcher = '/admin/tools/errors';

	public static function list_404_errors(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/404/list/');
	}

	public static function clear_404_errors(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/404/clear/?token=' . AppContext::get_session()->get_token());
	}

	public static function delete_404_error(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/404/' . $id . '/delete/?token=' . AppContext::get_session()->get_token());
	}

	public static function logged_errors(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/list/');
	}

	public static function clear_logged_errors(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/clear/?token=' . AppContext::get_session()->get_token());
	}
}
?>
