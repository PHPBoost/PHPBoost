<?php
/*##################################################
 *                           PDODBConnection.class.php
 *                            -------------------
 *   begin                : November 1, 2009
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
class PDODBConnection implements DBConnection
{
	/**
	 * @var PDO
	 */
	private $pdo;

	public function __destruct()
	{
		$this->disconnect();
	}

	public function connect(array $db_connection_data)
	{
		try
		{
			$this->pdo = new PDO(
			$db_connection_data['dsn'],
			$db_connection_data['login'],
			$db_connection_data['password'],
			$db_connection_data['driver_options']);
		}
		catch (PDOException $exception)
		{
			throw new PDODBConnectionException($exception->getMessage(), $this->pdo);
		}
	}

	public function get_link()
	{
		return $this->pdo;
	}

	public function disconnect()
	{
		$this->pdo = null;
	}

	public function start_transaction()
	{
		$this->pdo->beginTransaction();
	}

	public function commit()
	{
		$this->pdo->commit();
	}

	public function rollback()
	{
		$this->pdo->rollBack();
	}
}
?>