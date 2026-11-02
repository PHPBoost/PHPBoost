<?php
/**
 * This class manages all the environment services.
 * It's able to create each of them and return them.
 * @package     Core
 * @subpackage  Environment\context
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 02
 * @since       PHPBoost 3.0 - 2010 04 11
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PersistenceContext
{
	private static ?SQLQuerier $sql_querier = null;
	private static ?DBQuerier $db_querier = null;
	private static ?DBMSUtils $dbms_utils = null;

	/** Returns the sql querier */
	public static function get_querier(): DBQuerier
	{
		if (self::$db_querier === null)
		{
			self::$db_querier = new DBQuerier(self::get_sql_querier());
		}
		return self::$db_querier;
	}

	/** Returns the sql querier */
	public static function get_dbms_utils(): DBMSUtils
	{
		if (self::$dbms_utils === null)
		{
			self::$dbms_utils = DBFactory::new_dbms_util(self::get_querier());
		}
		return self::$dbms_utils;
	}

	/** Closes the database connection */
	public static function close_db_connection(): void
	{
		DBFactory::close_db_connection();
		self::$sql_querier = null;
		self::$db_querier = null;
		self::$dbms_utils = null;
	}

	/** Returns the sql querier */
	private static function get_sql_querier(): SQLQuerier
	{
		if (self::$sql_querier === null)
		{
			self::$sql_querier = DBFactory::new_sql_querier(DBFactory::get_db_connection());
		}
		return self::$sql_querier;
	}
}
?>
