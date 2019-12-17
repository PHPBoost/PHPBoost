<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 11 09
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

	public static function config()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}

	/**
	 * @return Url
	 */

	public static function admin_form()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/form/');
	}

	/**
	 * @return Url
	 */
	public static function form()
	{
		return DispatchManager::get_url(self::$dispatcher, '/form');
	}

	/**
	 * @return Url
	 */
	public static function css()
	{
		return DispatchManager::get_url(self::$dispatcher, '/css');
	}

	/**
	 * @return Url
	 */
	public static function multitabs()
	{
		return DispatchManager::get_url(self::$dispatcher, '/multitabs');
	}

	/**
	 * @return Url
	 */
	public static function plugins()
	{
		return DispatchManager::get_url(self::$dispatcher, '/plugins');
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
	public static function menu()
	{
		return DispatchManager::get_url(self::$dispatcher, '/menu');
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
	public static function mail()
	{
		return DispatchManager::get_url(self::$dispatcher, '/mail');
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
