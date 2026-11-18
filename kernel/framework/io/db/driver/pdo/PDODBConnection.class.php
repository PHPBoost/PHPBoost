<?php
/**
 * @package     IO
 * @subpackage  DB\driver\pdo
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2009 11 01
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PDODBConnection implements DBConnection
{
	/**
	 * @var PDO
	 */
	private ?PDO $pdo = null;

	public function __destruct()
	{
		$this->disconnect();
	}

	public function connect(array $db_connection_data): void
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

	public function get_link(): ?PDO
	{
		return $this->pdo;
	}

	public function disconnect(): void
	{
		$this->pdo = null;
	}

	public function start_transaction(): void
	{
		$this->pdo->beginTransaction();
	}

	public function commit(): void
	{
		$this->pdo->commit();
	}

	public function rollback(): void
	{
		$this->pdo->rollBack();
	}
}
?>
