<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 29
 * @since       PHPBoost 4.1 - 2013 12 17
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxUrlBuilder
{
	private static $dispatcher = '/sandbox';

	/**
	 * @return Url
	 */
	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}

	/**
	 * @return Url
	 */
	public static function lang()
	{
		return DispatchManager::get_url(self::$dispatcher, '/lang/');
	}

	/**
	 * @return Url
	 */

	public static function config()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}

	/**
	 * @return Url
	 */

	public static function admin_builder()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/builder/');
	}

	/**
	 * @return Url
	 */

	public static function admin_component()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/component/');
	}

	/**
	 * @return Url
	 */
	public static function builder()
	{
		return DispatchManager::get_url(self::$dispatcher, '/builder');
	}

	/**
	 * @return Url
	 */
	public static function component()
	{
		return DispatchManager::get_url(self::$dispatcher, '/component');
	}

	/**
	 * @return Url
	 */
	public static function layout()
	{
		return DispatchManager::get_url(self::$dispatcher, '/layout');
	}

	/**
	 * @return Url
	 */
	public static function bbcode()
	{
		return DispatchManager::get_url(self::$dispatcher, '/bbcode');
	}

	/**
	 * @return Url
	 */
	public static function icons()
	{
		return DispatchManager::get_url(self::$dispatcher, '/icons');
	}

	/**
	 * @return Url
	 */
	public static function menus_content()
	{
		return DispatchManager::get_url(self::$dispatcher, '/menus/content');
	}

	/**
	 * @return Url
	 */
	public static function menus_nav()
	{
		return DispatchManager::get_url(self::$dispatcher, '/menus/nav');
	}

	/**
	 * @return Url
	 */
	public static function table()
	{
		return DispatchManager::get_url(self::$dispatcher, '/table');
	}

	/**
	 * @return Url
	 */
	public static function email()
	{
		return DispatchManager::get_url(self::$dispatcher, '/email');
	}

	/**
	 * @return Url
	 */
	public static function template()
	{
		return DispatchManager::get_url(self::$dispatcher, '/template');
	}
}
?>
