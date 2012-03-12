<?php
/*##################################################
 *                       UpdateServices.class.php
 *                            -------------------
 *   begin                : February 29, 2012
 *   copyright            : (C) 2012 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class UpdateServices
{
	const CONNECTION_SUCCESSFUL = 0;
	const CONNECTION_ERROR = 1;
	const UNEXISTING_DATABASE = 2;
	const UNKNOWN_ERROR = 3;
	
	private static $token_file_content = '1';
	
	private static $directory = '/update/services';
	private static $configuration_pattern = '`ConfigUpdateVersion\.class\.php$`';
	private static $module_pattern = '`ModuleUpdateVersion\.class\.php$`';
	private static $kernel_pattern = '`UpdateVersion\.class\.php$`';
	
	/**
	 * @var File
	 */
	private $token;
	
	/**
	 * @var string[string]
	 */
	private $messages;
	
	public function __construct($locale = '')
	{
		$this->token = new File(PATH_TO_ROOT . '/cache/.update_token');
		if (!empty($locale))
		{
			LangLoader::set_locale($locale);
		}
		$this->messages = LangLoader::get('update', 'update');
	}
	
	public function is_already_installed()
	{
		$tables_list = PersistenceContext::get_dbms_utils()->list_tables();
		return in_array(PREFIX . 'member', $tables_list) || in_array(PREFIX . 'configs', $tables_list);
	}
	
	public function check_db_connection($host, $port, $login, $password, &$database, $tables_prefix)
	{
		try
		{
			$this->try_db_connection($host, $port, $login, $password, $database, $tables_prefix);
		}
		catch (UnexistingDatabaseException $ex)
		{
			DBFactory::reset_db_connection();
			return self::UNEXISTING_DATABASE;
		}
		catch (DBConnectionException $ex)
		{
			DBFactory::reset_db_connection();
			return self::CONNECTION_ERROR;
		}
		catch (Exception $ex)
		{
			DBFactory::reset_db_connection();
			return self::UNKNOWN_ERROR;
		}
		return self::CONNECTION_SUCCESSFUL;
	}

	private function try_db_connection($host, $port, $login, $password, $database, $tables_prefix)
	{
		defined('PREFIX') or define('PREFIX', $tables_prefix);
		$db_connection_data = array(
			'dbms' => DBFactory::MYSQL,
			'dsn' => 'mysql:host=' . $host . ';port=' . $port . 'dbname=' . $database,
			'driver_options' => array(),
			'host' => $host,
			'login' => $login,
			'password' => $password,
			'database' => $database,
		);
		$db_connection = new MySQLDBConnection();
		DBFactory::init_factory($db_connection_data['dbms']);
		DBFactory::set_db_connection($db_connection);
		$db_connection->connect($db_connection_data);
	}
	
	private function initialize_db_connection($dbms, $host, $port, $database, $login, $password, $tables_prefix)
	{
		defined('PREFIX') or define('PREFIX', $tables_prefix);
		$db_connection_data = array(
			'dbms' => $dbms,
			'dsn' => 'mysql:host=' . $host . ';port=' . $port . 'dbname=' . $database,
			'driver_options' => array(),
			'host' => $host,
			'port' => $port,
			'login' => $login,
			'password' => $password,
			'database' => $database,
		);
		$this->connect_to_database($dbms, $db_connection_data, $database);
		return $db_connection_data;
	}
	
	private function connect_to_database($dbms, array $db_connection_data, $database)
	{
		DBFactory::init_factory($dbms);
		$connection = DBFactory::new_db_connection();
		DBFactory::set_db_connection($connection);
		try
		{
			$connection->connect($db_connection_data);
		}
		catch (UnexistingDatabaseException $exception)
		{
			PersistenceContext::get_dbms_utils()->create_database($database);
			PersistenceContext::close_db_connection();
			$connection = DBFactory::new_db_connection();
			$connection->connect($db_connection_data);
			DBFactory::set_db_connection($connection);
		}
	}
	
	public function create_connection($dbms, $host, $port, $database, $login, $password, $tables_prefix)
	{
		$db_connection_data = $this->initialize_db_connection($dbms, $host, $port, $database, $login,
		$password, $tables_prefix);
		$this->write_connection_config_file($db_connection_data, $tables_prefix);
		$this->generate_cache();
		$this->generate_update_token();
		return true;
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
	
	public function execute()
	{
		$this->update_kernel();
		$this->update_configurations();
		$this->update_modules();
	}
	
	public function update_kernel()
	{
		$update_kernel_class = $this->get_class(self::$directory . '/kernel/', self::$kernel_pattern);
		foreach ($update_kernel_class as $class_name)
		{
			$object = new $class_name();
			$object->execute();
		}
	}
	
	public function update_configurations()
	{
		$configs_module_class = $this->get_class(self::$directory . '/modules/config/', self::$configuration_pattern);
		$configs_kernel_class = $this->get_class(self::$directory . '/kernel/config/', self::$configuration_pattern);
		
		$configs_class = array_merge($configs_module_class, $configs_kernel_class);
		foreach ($configs_class as $class_name)
		{
			$object = new $class_name();
			$object->execute();
		}
	}
	
	public function update_modules()
	{
		$update_module_class = $this->get_class(self::$directory . '/modules/', self::$module_pattern);
		foreach ($update_module_class as $class_name)
		{
			$object = new $class_name();
			$object->execute();
		}
	}
	
	private function get_class($directory, $pattern)
	{
		$class = array();
		$folder = new Folder($directory);
		foreach ($folder->get_files($pattern) as $file)
		{
			$class[] = $file->get_name();
		}
		return $class;
	}
	
	private function generate_cache()
	{
		AppContext::get_cache_service()->clear_cache();
	}
	
	private function generate_update_token()
	{
		$this->token->write(self::$token_file_content);
	}
	
	private function delete_update_token()
	{
		$this->token->delete();
	}
}
?>