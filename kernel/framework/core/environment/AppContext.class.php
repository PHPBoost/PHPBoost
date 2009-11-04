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
	 * Inits the breadcrumb
	 */
	public static function init_breadcrumb()
	{
		self::$breadcrumb = new BreadCrumb();
	}

	/**
	 * Returns the current page's bread crumb
	 * @return BreadCrumb
	 */
	public static function get_breadcrumb()
	{
		return self::$breadcrumb;
	}

	/**
	 * Inits the db connection
	 */
	public static function init_sql(DBConnection $connection, $database)
	{
		self::$sql = new Sql($connection, $database);
	}

	/**
	 * Returns the data base connection
	 * @return Sql
	 */
	public static function get_sql()
	{
		return self::$sql;
	}
	/**
	 * @deprecated de merde pour toi benoit
	 */
	public static function set_sql($sql)
	{
		// TODO ben, supprime ça, mais casse pas l'installateur (étape 6
		self::$sql = $sql;
	}

	/**
	 * Inits the database querier
	 */
	public static function init_sql_querier()
	{
		self::$sql_querier = DBFactory::new_sql_querier(DBFactory::get_db_connection());
		self::init_sql_common_query();

		// TODO @ben, refactor this, find another way to retrieve the $sql_base
		//Configuration file
		@include PATH_TO_ROOT . '/kernel/db/config.php';
		//If PHPBoost is not installed, we redirect the user to the installation page
		if (defined('PHPBOOST_INSTALLED'))
		{
			static $connection = null;
			if ($connection === null)
			{	// TODO arrange this If using PDO, there might be some problems with the connection
				$connection = new MySQLDBConnection();
				$connection->connect($db_connection_data);
			}
			self::init_sql($connection, $db_connection_data['database']);
		}
	}

	/**
	 * Returns the sql querier
	 * @return SqlQuerier
	 */
	public static function get_sql_querier()
	{
		return self::$sql_querier;
	}
	/**
	 * Inits the database common querier
	 */
	public static function init_sql_common_query()
	{
		
		self::$sql_common_query = new CommonQuery(self::get_sql_querier());
	}

	/**
	 * Returns the sql querier
	 * @return CommonQuery
	 */
	public static function get_sql_common_query()
	{
		return self::$sql_common_query;
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