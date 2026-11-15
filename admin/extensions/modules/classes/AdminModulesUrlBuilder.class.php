<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 3.0 - 2011 09 20
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminModulesUrlBuilder
{
	private static $dispatcher = '/admin/extensions/modules';

	public static function list_installed_modules(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/installed/');
	}

	public static function add_module(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/');
	}

	public static function ajax_github_list(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax/github/list/');
	}

	public static function ajax_website_list(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax/website/list/');
	}

	public static function ajax_install(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax/install/');
	}

	public static function update_module(string $module_id = ''): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/update/' . $module_id);
	}

	public static function delete_module(string $module_id, int $level = 0): Url
	{
		return DispatchManager::get_url(self::$dispatcher, $module_id . '/delete/' . $level);
	}
}
?>
