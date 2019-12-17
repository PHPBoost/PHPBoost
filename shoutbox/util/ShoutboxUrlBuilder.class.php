<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 02 24
 * @since       PHPBoost 4.1 - 2014 10 14
*/

class ShoutboxUrlBuilder
{
	private static $dispatcher = '/shoutbox';

	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}

	/**
	 * @return Url
	 */
	public static function home($page = 1, $id = null)
	{
		$page = $page !== 1 ? $page : '';
		$id = $id !== null ? '#m' . $id : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $page . $id);
	}

	/**
	 * @return Url
	 */
	public static function add()
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/');
	}

	/**
	 * @return Url
	 */
	public static function edit($id, $page = 1)
	{
		$page = $page !== 1 ? $page : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/' . $page);
	}

	/**
	 * @return Url
	 */
	public static function delete($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?token=' . AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function ajax_add()
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax_add/');
	}

	/**
	 * @return Url
	 */
	public static function ajax_delete()
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax_delete/');
	}

	/**
	 * @return Url
	 */
	public static function ajax_refresh()
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax_refresh/');
	}
}
?>
