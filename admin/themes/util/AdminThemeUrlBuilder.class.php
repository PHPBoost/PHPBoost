<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 09 20
*/

class AdminThemeUrlBuilder
{
	private static $dispatcher = '/admin/themes';

	/*
	 * @ return Url
	 */
	public static function list_installed_theme()
	{
		return DispatchManager::get_url(self::$dispatcher, '/installed/');
	}

	/*
	 * @ return Url
	 */
	public static function add_theme()
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/');
	}

	/*
	 * @ return Url
	 */
	public static function delete_theme($theme_id)
	{
		return DispatchManager::get_url(self::$dispatcher, $theme_id . '/delete/');
	}
}
?>
