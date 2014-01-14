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
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package {@package}
 * @desc this factory provides the <code>DBConnection</code> and the <code>SQLQuerier</code>
 * for the right sgbd.
 */
class DBFactory
{
	const MYSQL = 0x01;
	const PDO_MYSQL = 0x11;
	const PDO_SQLITE = 0x12;
	const PDO_POSTGRESQL = 0x13;

	/**
	 * @var DBConnection
	 */
	private static $db_connection;

	/**
	 * @var DBMSFactory
	 */
	private static $factory;

	private static $config_file;

	public static function __static()
	{
		self::$config_file = '/kernel/db/config.php';
	}

	public static function load_prefix()
	{
		@include_once(PATH_TO_ROOT . self::$config_file);
	}

	public static function init_factory($dbms)
	{
		require_once(PATH_TO_ROOT . '/kernel/db/tables.php');
		switch ($dbms)
		{
			case self::PDO_MYSQL:
				self::$factory = new PDOMySQLDBFactory();
				break;
			case self::MYSQL:
			default:
				self::$factory = new MySQLDBFactory();
				break;
		}
	}

	/**
	 * @desc returns the currently opened <code>DBConnection</code> instance or if none,
	 * creates a new one
	 * @return DBConnection the currently opened <code>DBConnection</code> instance
	 */
	public static function get_db_connection()
	{
		if (self::$db_connection === null)
		{
			$data = self::load_config();
			self::init_factory($data['dbms']);
			self::$db_connection = self::new_db_connection();
			self::$db_connection->connect($data);
		}
		return self::$db_connection;
	}

	public static function close_db_connection()
	{
		if (self::$db_connection != null)
		{
			self::$db_connection->disconnect();
		}
	}

	public static function reset_db_connection()
	{
		self::$db_connection = null;
	}

	public static function set_db_connection(DBConnection $connection)
	{
		self::$db_connection = $connection;
	}

	/**
	 * @desc returns a new <code>DBConnection</code> instance
	 * @return SQLQuerier a new <code>DBConnection</code> instance
	 */
	public static function new_db_connection()
	{
		return self::get_factory()->new_db_connection();
	}

	/**
	 * @desc returns a new <code>SQLQuerier</code> instance
	 * @param DBConnection $db_connection the db connection that the <code>SQLQuerier</code> will use
	 * @return SQLQuerier a new <code>SQLQuerier</code> instance
	 */
	public static function new_sql_querier(DBConnection $db_connection)
	{
		return self::get_factory()->new_sql_querier($db_connection);
	}

	public static function new_dbms_util(SQLQuerier $querier, $dbms_type = null)
	{
		return self::get_factory()->new_dbms_util($querier);
	}

	private static function load_config()
	{
		if (file_exists(PATH_TO_ROOT . self::$config_file))
		{
			include PATH_TO_ROOT . self::$config_file;
			if (defined('PHPBOOST_INSTALLED'))
			{
				return $db_connection_data;
			}
		}
		throw new PHPBoostNotInstalledException();
	}

	/**
	 * @return DBMSFactory
	 */
	private static function get_factory()
	{
		return self::$factory;
	}
}
?>