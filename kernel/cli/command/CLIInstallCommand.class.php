<?php
/*##################################################
 *                          CLIInstallCommand.class.php
 *                            -------------------
 *   begin                : February 06, 2010
 *   copyright            : (C) 2010 Loïc Rouchon
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
    private $website_path = '';
    private $website_name = 'PHPBoost';
    private $website_timezone = 'GMT';
    
    private $user_login = 'admin';
    private $user_password = 'admin';
    private $user_email = 'admin@mail.com';
	
	public function execute(array $arguments = array())
	{
        $this->check_parameters();
        $this->check_env();
        $this->install();
	}
	
	private function check_parameters()
	{
        CLIOutput::writeln('check parameters');
	}
	
	private function check_env()
	{
        CLIOutput::writeln('check environment');
	}
	
	private function install()
	{
        CLIOutput::writeln('starting phpboost installation');
	}
}
?>