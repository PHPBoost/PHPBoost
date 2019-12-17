<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 02 24
 * @since       PHPBoost 4.1 - 2015 09 30
 * @contributor xela <xela@phpboost.com>
*/

class DatabaseUrlBuilder
{
	private static $dispatcher = '/database';

	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}

	/**
	 * @return Url
	 */
	public static function database_management()
	{
		return new Url('/database/admin_database.php');
	}

	/**
	 * @return Url
	 */
	public static function db_sql_queries()
	{
		return new Url('/database/admin_database.php?query=1');
	}

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
	public static function documentation()
	{
		return new Url (ModulesManager::get_module('database')->get_configuration()->get_documentation());
	}
}
?>
