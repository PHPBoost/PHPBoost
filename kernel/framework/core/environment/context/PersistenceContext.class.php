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
 * @package core
 * @subpackage environment/context
 * @desc This class manages all the persistence services.
 * It's able to create each of them and return them.
 * @author loic rouchon <loic.rouchon@phpboost.com>
 */
class PersistenceContext
{
	/**
	 * @var SQLQuerier
	 */
	private static $querier;
	/**
	 * @var CommonQuery
	 */
	private static $common_query;
	/**
	 * @var DBMSUtils
	 */
	private static $dbms_utils;
	
	/**
	 * @var Sql
	 */
	private static $sql;

    /**
     * @desc Returns the data base connection
     * @deprecated
     * @return Sql
     */
    public static function get_sql()
    {
        if (self::$sql === null)
        {
            self::$sql = new Sql();
        }
        return self::$sql;
    }
	
	/**
	 * @deprecated de merde pour toi benoit
	 */
	public static function set_sql($sql)
	{
		self::$sql = $sql;
	}

	/**
	 * Returns the sql querier
	 * @return SqlQuerier
	 */
	public static function get_querier()
	{
		if (self::$querier === null)
		{
			self::$querier = DBFactory::new_sql_querier(DBFactory::get_db_connection());
		}
		return self::$querier;
	}

	/**
	 * Returns the sql querier
	 * @return CommonQuery
	 */
	public static function get_common_query()
	{
		if (self::$common_query === null)
		{
			self::$common_query = new CommonQuery(self::get_querier());
		}
		return self::$common_query;
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
		DBFactory::get_db_connection()->disconnect();
	}
}

?>