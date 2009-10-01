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
	 * @var Sql
	 */
	private static $db_connection;
	/**
	 * @var Session
	 */
	private static $session;
	/**
	 * @var User
	 */
	private static $user;
	
	/**
	 * Inits the breadcrumb
	 */
	public static function init_breadcrumb()
	{
		self::$breadcrumb = new BreadCrumb();
	}

	/**
	 * Inits the bench
	 */
	public static function init_bench()
	{
		self::$bench = new Bench();
	}

	/**
	 * Inits the db connection
	 */
	public static function init_db_connection()
	{
		self::$db_connection = new Sql();
	}

	/**
	 * Inits the session
	 */
	public static function init_session()
	{
		self::$session = new Session();
	}

	/**
	 * Inits the user
	 */
	public static function init_user()
	{
		self::$user = new User();
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
	 * Returns the current page's bench
	 * @return BreadCrumb
	 */
	public static function get_bench()
	{
		return self::$bench;
	}

	/**
	 * Returns the data base connection
	 * @return Sql
	 */
	public static function get_db_connection()
	{
		return self::$db_connection;
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
	 * Returns the current user
	 * @return User
	 */
	public static function get_user()
	{
		return self::$user;
	}
}

?>