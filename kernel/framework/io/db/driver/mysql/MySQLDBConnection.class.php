<?php
/**
 * @package     IO
 * @subpackage  DB\driver\mysql
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2009 10 01
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MySQLDBConnection implements DBConnection
{
	private  ?mysqli $link = null;

	public function __destruct()
	{
		$this->disconnect();
	}

	public function connect(array $db_connection_data): void
	{
		if (!extension_loaded('mysqli'))
		{
			throw new DBConnectionException('Unable to load mysqli extension');
		}
		mysqli_report(MYSQLI_REPORT_OFF);

		$previous_error_reporting = error_reporting(0);
		try
		{
			$mysqli_link = mysqli_connect(
				$db_connection_data['host'],
				$db_connection_data['login'],
				$db_connection_data['password'],
				"",
				$db_connection_data['port']
			);
		}
		finally
		{
			error_reporting($previous_error_reporting);
		}

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

	public function get_link(): ?mysqli
	{
		return $this->link;
	}

	public function disconnect(): void
	{
		if ($this->link !== null)
		{
			if (!mysqli_close($this->link))
			{
				throw new MySQLDBConnectionException('can\'t close database connection');
			}
			else
			{
				$this->link = null;
			}
		}
	}

	public function start_transaction(): void
	{
		$this->execute("START TRANSACTION;");
	}

	public function commit(): void
	{
		$this->execute("COMMIT;");
	}

	public function rollback(): void
	{
		$this->execute("ROLLBACK;");
	}

	private function execute(string $command): void
	{
		$resource = mysqli_query($this->link, $command);
		if ($resource === false)
		{
			throw new MySQLQuerierException('invalid mysql command', $command);
		}
	}

	private function select_database(string $database): void
	{
		if (!mysqli_select_db($this->link, $database))
		{
			throw new MySQLUnexistingDatabaseException();
		}
	}
}
?>
