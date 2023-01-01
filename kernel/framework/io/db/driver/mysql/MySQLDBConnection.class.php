<?php
/**
 * @package     IO
 * @subpackage  DB\driver\mysql
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 25
 * @since       PHPBoost 3.0 - 2009 10 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
		mysqli_report(MYSQLI_REPORT_OFF);

		$mysqli_link = mysqli_connect(
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
		if (!mysqli_select_db($this->link, $database))
		{
			throw new MySQLUnexistingDatabaseException();
		}
	}
}
?>
