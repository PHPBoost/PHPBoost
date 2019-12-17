<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 25
*/

class MenuUrlBuilder
{
    private static $dispatcher = '/admin/menus/dispatcher.php';

	/**
	 * @return Url
	 */
    public static function menu_configuration_list()
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/list/');
	}

	/**
	 * @return Url
	 */
	public static function menu_configuration_create()
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/create/');
	}

	/**
	 * @return Url
	 */
	public static function menu_configuration_create_valid()
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/create/valid/?token=' .
		AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function menu_configuration_edit($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/' . $id . '/edit/');
	}

	/**
	 * @return Url
	 */
	public static function menu_configuration_edit_valid($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/' . $id . '/edit/valid/?token=' .
		AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function menu_configuration_edit_delete($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/' . $id . '/delete/?token=' .
		AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function menu_configuration_configure($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/' . $id . '/configure/');
	}

	/**
	 * @return Url
	 */
	public static function menu_configuration_configure_valid($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/' . $id . '/configure/valid/');
	}

	/**
	 * @return Url
	 */
	public static function menu_list()
	{
		return DispatchManager::get_url(self::$dispatcher, '/menus/list/');
	}
}
?>
