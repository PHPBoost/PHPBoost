<?php
/**
 * This class manages all the environment services.
 * It's able to create each of them and return them.
 * @package     Core
 * @subpackage  Environment\context
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 04 11
*/

class PersistenceContext
{
	/**
	 * @var SQLQuerier
	 */
	private static $sql_querier;
	/**
	 * @var DBQuerier
	 */
	private static $db_querier;
	/**
	 * @var DBMSUtils
	 */
	private static $dbms_utils;

	/**
	 * Returns the sql querier
	 * @return DBQuerier
	 */
	public static function get_querier()
	{
		if (self::$db_querier === null)
		{
			self::$db_querier = new DBQuerier(self::get_sql_querier());
		}
		return self::$db_querier;
	}

	/**
	 * Returns the sql querier
	 * @return DBMSUtils
	 */
	public static function get_dbms_utils()
	{
		if (self::$dbms_utils === null)
		{
			self::$dbms_utils = DBFactory::new_dbms_util(self::get_querier());
		}
		return self::$dbms_utils;
	}

	/**
	 * Closes the database connection
	 */
	public static function close_db_connection()
	{
		DBFactory::close_db_connection();
		self::$sql_querier = null;
		self::$db_querier = null;
		self::$dbms_utils = null;
	}

	/**
	 * Returns the sql querier
	 * @return SQLQuerier
	 */
	private static function get_sql_querier()
	{
		if (self::$sql_querier === null)
		{
			self::$sql_querier = DBFactory::new_sql_querier(DBFactory::get_db_connection());
		}
		return self::$sql_querier;
	}
}
?>
