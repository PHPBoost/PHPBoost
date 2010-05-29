<?php
/*##################################################
 *                          CLIInstallCommand.class.php
 *                            -------------------
 *   begin                : February 06, 2010
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
	private $website_locale = 'english';
	private $website_timezone = 0;

	private $user_login = 'admin';
	private $user_password = 'admin';
	private $user_email = 'admin@mail.com';

	/**
	 * @var CLIArgumentsReader
	 */
	private $arg_reader;

    public function short_description()
    {
        return 'install phpboost development environment';
    }

    public function help(array $args)
    {
    	CLIOutput::writeln('this is the phpboost install command line manual...');
    }

	public function execute(array $args)
	{
		$this->arg_reader = new CLIArgumentsReader($args);
		$this->check_parameters();
		$this->show_parameters();
		$this->check_env();
		$this->install();
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
		$this->website_locale = $this->arg_reader->get('--ws-locale', $this->website_locale);
		$timezone = $this->arg_reader->get('--ws-timezone', $this->website_timezone);
		if (is_numeric($timezone))
		{
			$timezone = (int) $timezone;
			if ($timezone >= -12 && $timezone <= 12)
			{
				$this->website_timezone = $timezone;
			}
		}

		$this->user_login = $this->arg_reader->get('--u-login', $this->user_login);
		$this->user_password = $this->arg_reader->get('--u-pwd', $this->user_password);
		$this->user_email = $this->arg_reader->get('--u-email', $this->user_email);
	}

	private function check_env()
	{
		CLIOutput::writeln('check environment');
	}

	private function install()
	{
		CLIOutput::writeln('starting phpboost installation');
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
		$this->show_parameter('--ws-locale', $this->website_locale);
		$this->show_parameter('--ws-timezone', 'GMT' . ($this->website_timezone >= 0 ? '+' : '') . $this->website_timezone);

		$this->show_parameter_section('admin');
		$this->show_parameter('--u-login', $this->user_login);
		$this->show_parameter('--u-pwd', $this->user_password);
		$this->show_parameter('--u-email', $this->user_email);

		$installation = new InstallationServices();
		$installation->validate_server_configuration();
		$installation->validate_database_connection();
		$installation->configure_website();
		$installation->create_admin_account();
	}

	private function show_parameter($name, $value)
	{
		CLIOutput::writeln("\t" . $name . '=' . $value);
	}

	private function show_parameter_section($name)
	{
		CLIOutput::writeln(strtoupper($name));
	}
}
?>