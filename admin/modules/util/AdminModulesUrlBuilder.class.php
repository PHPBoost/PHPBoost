<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2011 09 20
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminModulesUrlBuilder
{
	private static $dispatcher = '/admin/modules';

	public static function list_installed_modules()
	{
		return DispatchManager::get_url(self::$dispatcher, '/installed/');
	}

	public static function add_module()
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/');
	}

	public static function ajax_github_list()
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax/github/list/');
	}

	public static function ajax_website_list()
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax/website/list/');
	}

	public static function ajax_install()
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax/install/');
	}

	public static function update_module($id = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/update/' . $id);
	}

	public static function delete_module($id, $level = 0)
	{
		return DispatchManager::get_url(self::$dispatcher, $id . '/delete/' . $level);
	}
}
?>
