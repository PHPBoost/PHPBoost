<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 01 20
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminLangsUrlBuilder
{
	private static $dispatcher = '/admin/langs';

	public static function list_installed_langs()
	{
		return DispatchManager::get_url(self::$dispatcher, '/installed/');
	}

	public static function install()
	{
		return DispatchManager::get_url(self::$dispatcher, '/install/');
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

	public static function uninstall($id)
	{
		return DispatchManager::get_url(self::$dispatcher, $id . '/uninstall/');
	}
}
?>
