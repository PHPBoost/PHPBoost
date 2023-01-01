<?php
/**
 * @package     IO
 * @subpackage  DB\driver\pdo
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 11 01
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
