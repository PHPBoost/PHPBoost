<?php
/*##################################################
 *                          InstallationServices.class.php
 *                            -------------------
 *   begin                : February 3, 2010
 *   copyright            : (C) 2010 Loic Rouchon
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

class InstallationServices
{
	private static $token_file_content = '1';
	private static $min_php_version = '5.1.2';

	/**
	 * @var File
	 */
	private $token;

	public function __construct()
	{
		$this->token = new File(PATH_TO_ROOT . '/.install_token');
	}

	public function create_phpboost_tables($dbms, $host, $port, $database, $login, $password, $tables_prefix, $create_db_if_needed = true)
	{
		$db_connection_data = $this->initialize_db_connection($dbms, $host, $port, $database, $login,
		$password, $tables_prefix, $create_db_if_needed);
		$this->create_tables();
		$this->write_connection_config_file($db_connection_data, $tables_prefix);
		$this->generate_installation_token();
	}

	public function configure_website()
	{
		$this->get_installation_token();
		return true;
	}

	public function create_admin_account()
	{
		$this->get_installation_token();

		$this->delete_installation_token();
		return true;
	}

	private function initialize_db_connection($dbms, $host, $port, $database, $login, $password, $tables_prefix, $create_db_if_needed)
	{
		defined('PREFIX') or define('PREFIX', $tables_prefix);
		$db_connection_data = array(
			'dbms' => $dbms,
			'dsn' => 'mysql:host=' . $host . ';dbname=' . $database,
			'driver_options' => array(),
			'host' => $host,
			'login' => $login,
			'password' => $password,
			'database' => $database,
		);
		$this->connect_to_database($dbms, $db_connection_data, $database, $create_db_if_needed);
		return $db_connection_data;
	}

	private function connect_to_database($dbms, array $db_connection_data, $database, $create_db_if_needed)
	{
		DBFactory::init_factory($dbms);
		$connection = DBFactory::new_db_connection();
		try
		{
			$connection->connect($db_connection_data);
		}
		catch (UnexistingDatabaseException $exception)
		{
			if ($create_db_if_needed)
			{
				DBFactory::new_dbms_util(DBFactory::new_sql_querier($connection))->create_database($database);
				$connection->disconnect();
				$connection->connect($db_connection_data);
			}
			else
			{
				throw $exception;
			}
		}
		DBFactory::set_db_connection($connection);
	}

	private function create_tables()
	{
		$kernel = new KernelSetup();
		$kernel->install();
	}

	private function write_connection_config_file(array $db_connection_data, $tables_prefix)
	{
		$db_config_content = '<?php' . "\n" .
			'$db_connection_data = ' . var_export($db_connection_data, true) . ";\n\n" .
            'defined(\'PREFIX\') or define(\'PREFIX\' , \'' . $tables_prefix . '\');'. "\n" .
            'defined(\'PHPBOOST_INSTALLED\') or define(\'PHPBOOST_INSTALLED\', true);' . "\n" .
            'require_once PATH_TO_ROOT . \'/kernel/db/tables.php\';' . "\n" .
        '?>';

		$db_config_file = new File(PATH_TO_ROOT . '/kernel/db/config.php');
		$db_config_file->write($db_config_content);
		$db_config_file->close();
	}

	private function generate_installation_token()
	{
		$this->token->write(self::$token_file_content);
	}

	private function get_installation_token()
	{
		$is_token_valid = false;
		try
		{
			$is_token_valid = $this->token->exists() && $this->token->read() == self::$token_file_content;
		}
		catch (IOException $ioe)
		{
			$is_token_valid = false;
		}

		if (!$is_token_valid)
		{
			throw new TokenNotFoundException($this->token->get_path_from_root());
		}
	}

	private function delete_installation_token()
	{
		$this->token->delete();
	}
}

?>
