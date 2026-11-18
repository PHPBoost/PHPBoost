<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 3.0 - 2012 01 20
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminLangsUrlBuilder
{
	private static $dispatcher = '/admin/extensions/langs';

	public static function list_installed_langs(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/installed/');
	}

	public static function install(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/install/');
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

	public static function uninstall(string $lang_id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, $lang_id . '/uninstall/');
	}
}
?>
