<?php
/*##################################################
 *                       PersistenceContext.class.php
 *                            -------------------
 *   begin                : April 11, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/



/**
 * @package {@package}
 * @desc This class manages all the persistence services.
 * It's able to create each of them and return them.
 * @author loic rouchon <loic.rouchon@phpboost.com>
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