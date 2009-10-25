<?php
/*##################################################
 *                           db_factory.class.php
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
	/**
	 * @var DBConnection
	 */
	private static $db_connection;

	/**
	 * @desc returns a new <code>DBConnection</code> instance
	 * @return DBConnection a new <code>DBConnection</code> instance
	 */
	public static function get_db_connection()
	{
		if (self::$db_connection == null)
		{
			//Configuration file
			include PATH_TO_ROOT . '/kernel/db/config.php';

			//If PHPBoost is not installed, we redirect the user to the installation page
			if (!defined('PHPBOOST_INSTALLED'))
			{
				import('util/unusual_functions', INC_IMPORT);
				redirect(get_server_url_page('install/install.php'));
			}

			import('io/db/mysql/MySQLDBConnection');
			self::$db_connection = new MySQLDBConnection($host, $login, $password);
		}
        
		if (!self::$db_connection->is_connected())
		{
			self::$db_connection->connect();
			self::$db_connection->select_database($database);
		}
		return self::$db_connection;
	}

	/**
	 * @desc returns a new <code>SQLQuerier</code> instance
	 * @param DBConnection $db_connection the db connection that the <code>SQLQuerier</code> will use
	 * @return SQLQuerier a new <code>SQLQuerier</code> instance
	 */
	public static function new_sql_querier(DBConnection $db_connection)
	{
		import('io/db/mysql/MySQLQuerier');
		return new MySQLQuerier($db_connection);
	}
}

?>