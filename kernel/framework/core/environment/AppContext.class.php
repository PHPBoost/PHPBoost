<?php
/*##################################################
 *                     environment_services.class.php
 *                            -------------------
 *   begin                : October 01, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/



/**
 * @package core
 * @subpackage environment
 * @desc This class manages all the environment services.
 * It's able to create each of them and return them.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class AppContext
{
	/**
	 * @var HTTPRequest
	 */
	private static $request;

	/**
	 * @var BreadCrumb
	 */
	private static $breadcrumb;
	/**
	 * @var Bench
	 */
	private static $bench;
	/**
	 * @var SQLQuerier
	 */
	private static $sql_querier;
	/**
	 * @var CommonQuery
	 */
	private static $sql_common_query;
	/**
	 * @var DBMSUtils
	 */
	private static $dbms_utils;
	/**
	 * @var Sql
	 */
	private static $sql;
	/**
	 * @var Session
	 */
	private static $session;
	/**
	 * @var User
	 */
	private static $user;

	/**
	 * @desc set the <code>HTTPRequest</code>
	 * @param HTTPRequest $request
	 */
	public static function set_request(HTTPRequest $request)
	{
		self::$request = $request;
	}

	/**
	 * @desc Returns the <code>HTTPRequest</code> object
	 * @return HTTPRequest
	 */
	public static function get_request()
	{
		return self::$request;
	}


	/**
	 * Inits the bench
	 */
	public static function init_bench()
	{
		self::$bench = new Bench();
		self::$bench->start();
	}

	/**
	 * Returns the current page's bench
	 * @return Bench
	 */
	public static function get_bench()
	{
		return self::$bench;
	}
	
	/**
	 * @deprecated de merde pour toi benoit
	 */
	public static function set_sql($sql)
	{
		self::$sql = $sql;
	}

	/**
	 * Returns the data base connection
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
	 * Returns the sql querier
	 * @return SqlQuerier
	 */
	public static function get_sql_querier()
	{
		if (self::$sql_querier === null)
		{
			self::$sql_querier = DBFactory::new_sql_querier(DBFactory::get_db_connection());
		}
		return self::$sql_querier;
	}

	/**
	 * Returns the sql querier
	 * @return CommonQuery
	 */
	public static function get_sql_common_query()
	{
		if (self::$sql_common_query === null)
		{
			self::$sql_common_query = new CommonQuery(self::get_sql_querier());
		}
		return self::$sql_common_query;
	}

	/**
	 * Returns the sql querier
	 * @return DBMSUtils
	 */
	public static function get_dbms_utils()
	{
		if (self::$dbms_utils === null)
		{
			self::$dbms_utils = DBFactory::new_dbms_util(self::get_sql_querier());
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

	/**
	 * Inits the session
	 */
	public static function init_session()
	{
		self::$session = new Session();
	}

	/**
	 * Returns the current user's session
	 * @return Session
	 */
	public static function get_session()
	{
		return self::$session;
	}

	/**
	 * Inits the user
	 */
	public static function init_user()
	{
		self::$user = new User();
	}

	/**
	 * Returns the current user
	 * @return User
	 */
	public static function get_user()
	{
		return self::$user;
	}

	public static function set_user($user)
	{
		// TODO ben, supprime ça, mais casse pas l'installateur
		self::$user = $user;
	}
}

?>