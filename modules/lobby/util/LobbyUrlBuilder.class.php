<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class LobbyUrlBuilder
{
	/**
	 * '/lobby' — no /modules/ prefix: lobby lives at the site root.
	 * DispatcherUrlMapping detects this via is_dir(PATH_TO_ROOT . '/lobby').
	 */
	private static string $dispatcher = '/lobby';

	public static function configuration(?string $anchor = null): Url
	{
		$anchor = $anchor !== null ? '#AdminLobbyConfigController_admin_' . $anchor : '';
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/' . $anchor);
	}

	public static function add_modules(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/add/');
	}

	public static function positions(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/positions/');
	}

	public static function change_display(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/positions/change_display/');
	}

	public static function home(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
