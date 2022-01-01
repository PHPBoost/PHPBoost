<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 10 09
 * @since       PHPBoost 3.0 - 2010 02 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class CLIInstallCommand implements CLICommand
{
	private $db_host = 'localhost';
	private $db_port = '3306';
	private $db_user = 'root';
	private $db_password = '';
	private $db_schema = 'phpboost';
	private $db_tables_prefix = 'phpboost_';

	private $website_server = 'http://localhost';
	private $website_path = '/';
	private $website_name = 'PHPBoost';
	private $website_slogan = '';
	private $website_description = 'PHPBoost command line installation';
	private $website_locale = 'english';
	private $website_timezone = 'Europe/Paris';

	private $user_login = 'admin';
	private $user_password = 'admin';
	private $user_email = 'admin@mail.com';

	/**
	 * @var InstallationServices
	 */
	private $installation;

	/**
	 * @var ServerConfiguration
	 */
	private $server_configuration;

	/**
	 * @var CLIArgumentsReader
	 */
	private $arg_reader;

	public function __construct()
	{
		$this->server_configuration = new ServerConfiguration();
	}

	public function short_description()
	{
		return 'install phpboost development environment';
	}

	public function help(array $args)
	{
        CLIOutput::writeln('this is the phpboost install command line manual.');
		CLIOutput::writeln('this commands have optionals parameters. Here is theirs default values:');

		$this->show_parameter_section('database');
		$this->show_parameter('--db-host', $this->db_host);
		$this->show_parameter('--db-port', $this->db_port);
		$this->show_parameter('--db-user', $this->db_user);
		$this->show_parameter('--db-pwd', $this->db_password);
		$this->show_parameter('--db-schema', $this->db_schema);
		$this->show_parameter('--db-table-prefix', $this->db_tables_prefix);

		$this->show_parameter_section('website');
		$this->show_parameter('--ws-server', $this->website_server);
		$this->show_parameter('--ws-path', $this->website_path);
		$this->show_parameter('--ws-name', $this->website_name);
		$this->show_parameter('--ws-slogan', $this->website_slogan);
		$this->show_parameter('--ws-desc', $this->website_description);
		$this->show_parameter('--ws-locale', $this->website_locale);
		$this->show_parameter('--ws-timezone', $this->website_timezone);

		$this->show_parameter_section('admin');
		$this->show_parameter('--u-login', $this->user_login);
		$this->show_parameter('--u-pwd', $this->user_password);
		$this->show_parameter('--u-email', $this->user_email);
	}

	public function execute(array $args)
	{
		$this->arg_reader = new CLIArgumentsReader($args);
		$this->check_parameters();
		$this->show_parameters();
		if (!($this->check_env() && $this->install()))
		{
			CLIOutput::writeln('installation failed');
		}
	}

	private function check_parameters()
	{
		CLIOutput::writeln('check parameters');
		$this->db_host = $this->arg_reader->get('--db-host', $this->db_host);
		$this->db_port = $this->arg_reader->get('--db-port', $this->db_port);
		$this->db_user = $this->arg_reader->get('--db-user', $this->db_user);
		$this->db_password = $this->arg_reader->get('--db-pwd', $this->db_password);
		$this->db_schema = $this->arg_reader->get('--db-schema', $this->db_schema);
		$this->db_tables_prefix = $this->arg_reader->get('--db-table-prefix', $this->db_tables_prefix);

		$this->website_server = $this->arg_reader->get('--ws-server', $this->website_server);
		$this->website_path = $this->arg_reader->get('--ws-path', $this->website_path);
		$this->website_name = $this->arg_reader->get('--ws-name', $this->website_name);
		$this->website_slogan = $this->arg_reader->get('--ws-slogan', $this->website_slogan);
		$this->website_description = $this->arg_reader->get('--ws-desc', $this->website_description);
		$this->website_locale = $this->arg_reader->get('--ws-locale', $this->website_locale);
		$this->website_timezone = $this->arg_reader->get('--ws-timezone', $this->website_timezone);

		$this->user_login = $this->arg_reader->get('--u-login', $this->user_login);
		$this->user_password = $this->arg_reader->get('--u-pwd', $this->user_password);
		$this->user_email = $this->arg_reader->get('--u-email', $this->user_email);
	}

	private function show_parameters()
	{
		$this->show_parameter_section('database');
		$this->show_parameter('--db-host', $this->db_host);
		$this->show_parameter('--db-port', $this->db_port);
		$this->show_parameter('--db-user', $this->db_user);
		$this->show_parameter('--db-pwd', $this->db_password);
		$this->show_parameter('--db-schema', $this->db_schema);
		$this->show_parameter('--db-table-prefix', $this->db_tables_prefix);

		$this->show_parameter_section('website');
		$this->show_parameter('--ws-server', $this->website_server);
		$this->show_parameter('--ws-path', $this->website_path);
		$this->show_parameter('--ws-name', $this->website_name);
		$this->show_parameter('--ws-slogan', $this->website_slogan);
		$this->show_parameter('--ws-desc', $this->website_description);
		$this->show_parameter('--ws-locale', $this->website_locale);
		$this->show_parameter('--ws-timezone', $this->website_timezone);

		$this->show_parameter_section('admin');
		$this->show_parameter('--u-login', $this->user_login);
		$this->show_parameter('--u-pwd', $this->user_password);
		$this->show_parameter('--u-email', $this->user_email);
	}

	private function show_parameter($name, $value)
	{
		CLIOutput::writeln("\t" . $name . ' ' . $value);
	}

	private function show_parameter_section($name)
	{
		CLIOutput::writeln(TextHelper::strtoupper($name));
	}

	private function check_env()
	{
		CLIOutput::writeln('environment check...');
		return $this->check_php_version() && $this->check_folders_permissions();
	}

	private function install()
	{
		CLIOutput::writeln('installation');
        $this->installation = new InstallationServices($this->website_locale);
		CLIOutput::writeln("\t" . 'kernel...');
		if (!$this->create_phpboost_tables())
		{
			return false;
		}
		CLIOutput::writeln("\t" . 'modules...');
		if (!$this->installation->configure_website($this->website_server, $this->website_path, $this->website_name, $this->website_slogan,
			$this->website_description, $this->website_timezone))
		{
			return false;
		}
		CLIOutput::writeln("\t" . 'admin creation...');
		if (!$this->installation->create_admin($this->user_login, $this->user_login, $this->user_password, $this->user_email, false, false))
		{
			return false;
		}
		$this->installation->save_server_environnement_config();
		CLIOutput::writeln('installation successfull');
		return true;
	}

	private function check_php_version()
	{
		CLIOutput::writeln("\t" . 'php version');
		if (!$this->server_configuration->is_php_compatible())
		{
			CLIOutput::writeln('PHP version (' . ServerConfiguration::get_phpversion() . ') is not compatible with PHPBoost.');
			CLIOutput::writeln('PHP ' . ServerConfiguration::MIN_PHP_VERSION . ' is needed!');
			return false;

		}
		return true;
	}

	private function check_folders_permissions()
	{
		CLIOutput::writeln("\t" . 'folder permissions...');
		if (!PHPBoostFoldersPermissions::validate())
		{
			foreach (PHPBoostFoldersPermissions::get_permissions() as $folder_name => $folder)
			{
				if (!$folder->is_writable())
				{
					CLIOutput::writeln('Folder ' . $folder_name . ' is not writable. Please change its rights');
				}
			}
			return false;
		}
		return true;
	}

	private function create_phpboost_tables()
	{
		AppContext::get_cache_service()->clear_cache();
		try
		{
			$this->installation->create_phpboost_tables(DBFactory::MYSQL, $this->db_host, $this->db_port,
			$this->db_schema, $this->db_user, $this->db_password, $this->db_tables_prefix);
			return true;
		}
		catch (UnexistingDatabaseException $exception)
		{
			CLIOutput::writeln('Database ' . $this->db_schema .
				' does not exist and attempt to create it failed. Create it and relaunch the installation');
		}
		catch (DBConnectionException $exception)
		{
			CLIOutput::writeln('Connection to database failed, check your connection parameters');
		}
		catch (IOException $exception)
		{
			CLIOutput::writeln('Can\'t write database configuration file informations to /kernel/db/config.php. ' .
				'Check file or parent directory permissions.');
		}
		return false;
	}
}
?>
