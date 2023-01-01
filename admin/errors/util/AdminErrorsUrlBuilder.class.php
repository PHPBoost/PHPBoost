<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 05 28
 * @since       PHPBoost 3.0 - 2009 12 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminErrorsUrlBuilder
{
	private static $dispatcher = '/admin/errors';

	/**
	 * @return Url
	 */
	public static function list_404_errors()
	{
		return DispatchManager::get_url(self::$dispatcher, '/404/list/');
	}

	/**
	 * @return Url
	 */
	public static function clear_404_errors()
	{
		return DispatchManager::get_url(self::$dispatcher, '/404/clear/?token=' . AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function delete_404_error($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/404/' . $id . '/delete/?token=' . AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function logged_errors()
	{
		return DispatchManager::get_url(self::$dispatcher, '/list/');
	}

	/**
	 * @return Url
	 */
	public static function clear_logged_errors()
	{
		return DispatchManager::get_url(self::$dispatcher, '/clear/?token=' . AppContext::get_session()->get_token());
	}
}
?>
