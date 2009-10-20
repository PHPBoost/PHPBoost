<?php
/*##################################################
 *                           mysql_db_connection.class.php
 *                            -------------------
 *   begin                : October 1, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : horn@phpboost.com
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

import('io/db/DBConnection');
import('io/db/mysql/MySQLDBConnectionException');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package sql
 * @subpackage mysql
 * @desc
 */
class MySQLDBConnection implements DBConnection
{
	/**
	 * @var string
	 */
	private $host;

	/**
	 * @var string
	 */
	private $login;

	/**
	 * @var string
	 */
	private $password;

	/**
	 * @var string
	 */
	private $database;

	/**
	 * @var bool
	 */
	private $connected = false;

	/**
	 * @var MysqlResource
	 */
	private $link = null;
	

	public function __construct($host, $login, $password)
	{
		$this->host = $host;
		$this->login = $login;
		$this->password = $password;
	}

	public function __destruct()
	{
		$this->disconnect();
	}

	public function is_connected()
	{
		return $this->connected;
	}

	public function connect()
	{
		if (!$this->is_connected())
		{
			$mysql_link = @mysql_connect($this->host, $this->login, $this->password);
			if ($mysql_link)
			{
				$this->link = $mysql_link;
				$this->connected = true;
			}
			else
			{
				throw new MySQLDBConnectionException('can\'t connect to database!');
			}
		}
	}

	public function get_link()
	{
		return $this->link;
	}

	public function disconnect()
	{
		if ($this->is_connected())
		{
			$this->connected = false;
            if (!is_resource($this->link) || !@mysql_close($this->link))
			{
				throw new MySQLDBConnectionException('can\'t close database connection');
			}
		}
	}

	public function select_database($database_name)
	{
		if (!$this->is_connected())
		{
			throw new MySQLDBConnectionException('you must be connected ' .
    			'to a server to select a database');
		}

		if (!@mysql_select_db($database_name, $this->link))
		{
			throw new MySQLUnexistingDatabaseException();
		}
		$this->database_name = $database_name;
	}
}

?>