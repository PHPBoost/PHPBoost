<?php
/*##################################################
 *                          CLIDeleteUserCommand.class.php
 *                            -------------------
 *   begin                : October 11, 2011
 *   copyright            : (C) 2011 K�vin MASSY
 *   email                : kevin.massy@phpboost.com
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

class CLIDeleteUserCommand implements CLICommand
{
	private $id = '';
	private $login = '';
	private $email = '';
	
	public function short_description()
	{
		return 'delete user';
	}

	public function help(array $args)
	{
		CLIOutput::writeln('scenario: phpboost user delete [args]');
		$this->show_parameter('--id', 'user id');
		CLIOutput::writeln("\t or");
		$this->show_parameter('--login', 'user login');
		CLIOutput::writeln("\t or");
		$this->show_parameter('--email', 'user email');
	}

	public function execute(array $args)
	{
		if (empty($args))
		{
			$this->help($args);
		}
		else
		{
			$this->arg_reader = new CLIArgumentsReader($args);
			$this->check_parameters();
			$this->delete_user();
		}
	}
	
	private function delete_user()
	{
		if (!empty($this->id))
		{
			$this->show_parameter('--id', $this->id);
			try
			{
				UserService::delete_by_id($this->id);
				$this->write_success_message();
			}
			catch (RowNotFoundException $ex) {
				$this->write_user_not_exists_message();
			}
			exit;
		}
		else if (!empty($this->login))
		{
			$this->show_parameter('--login', $this->login);
			try
			{
				$condition = 'WHERE login=:login';
				$parameters = array('login' => $this->login);
				$user_id = PersistenceContext::get_querier()->get_column_value(DB_TABLE_INTERNAL_AUTHENTICATION, 'user_id', $condition, $parameters);

				UserService::delete_by_id($user_id);
				$this->write_success_message();
			}
			catch (RowNotFoundException $ex) {
				$this->write_user_not_exists_message();
			}
			exit;
		}
		else if (!empty($this->email))
		{
			$this->show_parameter('--email', $this->email);
			try
			{
				$user_id = UserService::user_exists('WHERE email=:email', array('email' => $this->email));
				UserService::delete_by_id($user_id);
				$this->write_success_message();
			}
			catch (RowNotFoundException $ex) {
				$this->write_user_not_exists_message();
			}
		}
		else
		{
			$this->help(array());
		}
	}
	
	private function write_success_message()
	{
		CLIOutput::writeln('User deleted successfull');
	}
	
	private function write_user_not_exists_message()
	{
		CLIOutput::writeln('User not exists');
	}

	private function check_parameters()
	{
		CLIOutput::writeln('check parameters');
		$this->id = $this->arg_reader->get('--id', $this->id);
		$this->login = $this->arg_reader->get('--login', $this->login);
		$this->email = $this->arg_reader->get('--email', $this->email);
	}
	
	private function show_parameter($name, $value)
	{
		CLIOutput::writeln("\t" . $name . ' ' . $value);
	}
}
?>