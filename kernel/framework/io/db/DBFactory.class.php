<?php
/*##################################################
 *                           DBFactory.class.php
 *                            -------------------
 *   begin                : October 1, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package io
 * @subpackage sql
 * @desc this factory provides the <code>DBConnection</code> and the <code>SQLQuerier</code>
 * for the right sgbd.
 */
class DBFactory
{
	//private static $dbms = 'pdo-mysql';
	private static $dbms = 'mysql';

	/**
	 * @var DBConnection
	 */
	private static $db_connection;

	/**
	 * @desc returns a new <code>DBConnection</code> instance
	 * @return DBConnection a new <code>DBConnection</code> instance
	 */
	public static function get_db_connection($dbms_type = null)
	{
		if (self::$db_connection === null)
		{
			//Configuration file
			include PATH_TO_ROOT . '/kernel/db/config.php';

			//If PHPBoost is not installed, we redirect the user to the installation page
			if (!defined('PHPBOOST_INSTALLED'))
			{
				import('util/unusual_functions', INC_IMPORT);
				redirect(get_server_url_page('install/install.php'));
			}

			if ($dbms_type === null)
			{
				$dbms_type = self::$dbms;
			}
			switch ($dbms_type)
			{
				case 'pdo-mysql':
					self::$db_connection = new PDODBConnection();
					break;
				case 'mysql':
				default:
					self::$db_connection = new MySQLDBConnection();
					break;
			}
			self::$db_connection->connect($db_connection_data);
		}
		return self::$db_connection;
	}

	/**
	 * @desc returns a new <code>SQLQuerier</code> instance
	 * @param DBConnection $db_connection the db connection that the <code>SQLQuerier</code> will use
	 * @return SQLQuerier a new <code>SQLQuerier</code> instance
	 */
	public static function new_sql_querier(DBConnection $db_connection, $dbms_type = null)
	{
		if ($dbms_type === null)
		{
			$dbms_type = self::$dbms;
		}
		switch ($dbms_type)
		{
			case 'pdo-mysql':
				return new PDOQuerier($db_connection, self::new_query_translator());
			case 'mysql':
			default:
				return new MySQLQuerier($db_connection, self::new_query_translator());
		}
	}

	public static function new_dbms_util(SQLQuerier $querier, $dbms_type = null)
	{
		if ($dbms_type === null)
		{
			$dbms_type = self::$dbms;
		}
		switch ($dbms_type)
		{
			case 'pdo-mysql':
			case 'mysql':
			default:
				return new MySQLDBMSUtils($querier);
		}
	}

	private static function new_query_translator($dbms_type = null)
	{
		if ($dbms_type === null)
		{
			$dbms_type = self::$dbms;
		}
		switch ($dbms_type)
		{
			case 'pdo-mysql':
			case 'mysql':
			default:
				return new MySQLQueryTranslator();
		}
	}
}

?>