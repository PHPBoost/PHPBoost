<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 3.0 - 2009 10 25
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MenuUrlBuilder
{
    private static string $dispatcher = '/admin/content/menus/dispatcher.php';

    public static function menu_configuration_list(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/list/');
	}

	public static function menu_configuration_create(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/create/');
	}

	public static function menu_configuration_create_valid(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/create/valid/?token=' .
		AppContext::get_session()->get_token());
	}

	public static function menu_configuration_edit(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/' . $id . '/edit/');
	}

	public static function menu_configuration_edit_valid(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/' . $id . '/edit/valid/?token=' .
		AppContext::get_session()->get_token());
	}

	public static function menu_configuration_edit_delete(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/' . $id . '/delete/?token=' .
		AppContext::get_session()->get_token());
	}

	public static function menu_configuration_configure(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/' . $id . '/configure/');
	}

	public static function menu_configuration_configure_valid(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/configs/' . $id . '/configure/valid/');
	}

	public static function menu_list(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/menus/list/');
	}
}
?>
