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

import('io/db/db_factory');

/**
 * @package core
 * @subpackage environment
 * @desc This class manages all the environment services.
 * It's able to create each of them and return them.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class EnvironmentServices
{
	/**
	 * @var BreadCrumb
	 */
	private static $breadcrumb;
	/**
	 * @var Bench
	 */
	private static $bench;
	/**
	 * @var SqlQuerier
	 */
	private static $sql_querier;
	/**
	 * @var DBConnection
	 */
	private static $db_connection;
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
	 * Inits the bench
	 */
	public static function init_bench()
	{
		self::$bench = new Bench();
	}

	/**
	 * Returns the current page's bench
	 * @return BreadCrumb
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
	public static function init_sql($db_name)
	{
		self::$sql = new Sql(self::$db_connection, $db_name);
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
        // TODO ben, supprime ça, mais casse pas l'installateur (étape 6)
        self::$sql = $sql;
    }

	/**
	 * Inits the database querier
	 */
	public static function init_sql_querier()
	{
	    self::$db_connection = DBFactory::new_db_connection();
		self::$sql_querier = DBFactory::new_sql_querier(self::$db_connection);
		
		// TODO @ben, refactor this, find another way to retrieve the $sql_base
		//Configuration file
        @include PATH_TO_ROOT . '/kernel/db/config.php';
        //If PHPBoost is not installed, we redirect the user to the installation page
        if (defined('PHPBOOST_INSTALLED'))
        {
		    self::init_sql($database);
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
	 * Closes the SqlQuerier
	 */
	public static function close_sql_querier()
	{
		self::$db_connection->disconnect();
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