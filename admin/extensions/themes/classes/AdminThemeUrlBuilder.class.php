<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 3.0 - 2011 09 20
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminThemeUrlBuilder
{
	private static $dispatcher = '/admin/extensions/themes';

	public static function list_installed_theme(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/installed/');
	}

	public static function add_theme(): Url
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

	public static function delete_theme(string $theme_id, int $level = 0): Url
	{
		return DispatchManager::get_url(self::$dispatcher, $theme_id . '/delete/' . $level);
	}
}
?>
