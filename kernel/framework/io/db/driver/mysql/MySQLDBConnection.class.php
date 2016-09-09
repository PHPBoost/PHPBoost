<?php
/*##################################################
 *                           MySQLDBConnection.class.php
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
 * @desc
 */
class MySQLDBConnection implements DBConnection
{
	/**
	 * @var MysqlResource
	 */
	private $link = null;

	public function __destruct()
	{
		$this->disconnect();
	}

	public function connect(array $db_connection_data)
	{
		if (!extension_loaded('mysqli'))
		{
			throw new DBConnectionException('Unable to load mysqli extension');
		}
		
		$mysqli_link = @mysqli_connect(
			$db_connection_data['host'],
			$db_connection_data['login'],
			$db_connection_data['password'],
			"",
			$db_connection_data['port']
		);
		
		if ($mysqli_link)
		{
			$this->link = $mysqli_link;
			$this->select_database($db_connection_data['database']);
			$this->execute("SET NAMES UTF8");
		}
		else
		{
			throw new MySQLDBConnectionException('can\'t connect to database!');
		}
	}

	public function get_link()
	{
		return $this->link;
	}

	public function disconnect()
	{
		if ($this->link !== null)
		{
			if (!@mysqli_close($this->link))
			{
				throw new MySQLDBConnectionException('can\'t close database connection');
			}
			else
			{
				$this->link = null;
			}
		}
	}

	public function start_transaction()
	{
		$this->execute("START TRANSACTION;");
	}

	public function commit()
	{
		$this->execute("COMMIT;");
	}

	public function rollback()
	{
		$this->execute("ROLLBACK;");
	}

	private function execute($command)
	{
		$resource = mysqli_query($this->link, $command);
		if ($resource === false)
		{
			throw new MySQLQuerierException('invalid mysql command', $command);
		}
	}
	
	private function select_database($database)
	{
		if (!@mysqli_select_db($this->link, $database))
		{
			throw new MySQLUnexistingDatabaseException();
		}
	}
}
?>