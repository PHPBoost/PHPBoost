<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2012 01 20
*/

class AdminLangsUrlBuilder
{
	private static $dispatcher = '/admin/langs';

	/*
	 * @ return Url
	 */
	public static function list_installed_langs()
	{
		return DispatchManager::get_url(self::$dispatcher, '/installed/');
	}

	/*
	 * @ return Url
	 */
	public static function install()
	{
		return DispatchManager::get_url(self::$dispatcher, '/install/');
	}

	/*
	 * @ return Url
	 */
	public static function uninstall($id)
	{
		return DispatchManager::get_url(self::$dispatcher, $id . '/uninstall/');
	}
}
?>
