<?php
/*##################################################
 *                          CLIAddUserCommand.class.php
 *                            -------------------
 *   begin                : October 11, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class CLIAddUserCommand implements CLICommand
{
	private $login = 'user';
	private $email = 'user@user.com';
	private $password = 'phpboost';
	private $level = 'member';
	private $approbation = 'yes';
	
	private $level_possible_values = array('0' => 'member', '1' => 'moderator', '2' => 'administrator');
	private $approbation_possible_values = array('1' => 'yes', '0' => 'no');
	
	public function short_description()
	{
		return 'add user';
	}

	public function help(array $args)
	{
		CLIOutput::writeln('scenario: phpboost user add [args]');
		$this->print_commands_descriptions();
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
			
			try {
				$this->check_parameters();
				$this->print_commands_descriptions();
				$this->add_user();
			} catch (Exception $e) {
				CLIOutput::writeln("\n Error : " . $e->getMessage());
				exit;
			}
		}
	}
	
	private function print_commands_descriptions()
	{ 
		$this->show_parameter('--login', $this->login);
		$this->show_parameter('--email', $this->email);
		$this->show_parameter('--pwd', $this->password);
		$this->show_parameter('--level', $this->level . ' in possible values : ' . $this->show_possible_values($this->level_possible_values));
		$this->show_parameter('--approb', $this->approbation . ' in possible values : ' . $this->show_possible_values($this->approbation_possible_values));
	}
	
	private function check_parameters()
	{
		CLIOutput::writeln('check parameters');
		$this->login = $this->arg_reader->get('--login', $this->login);
		$this->email = $this->arg_reader->get('--email', $this->email);
		$this->password = $this->arg_reader->get('--pwd', $this->password);
		
		$level = $this->arg_reader->get('--level', $this->level);
		if ($this->level_is_valid($level))
		{
			$this->level = $level;
		}
		
		$approbation = $this->arg_reader->get('--approb', $this->approbation);
		if ($this->approbation_is_valid($approbation))
		{
			$this->approbation = $approbation;
		}
	}
	
	private function show_possible_values(Array $possible_values)
	{
		return implode('|', $possible_values);
	}
	
	private function add_user()
	{
		if (PersistenceContext::get_querier()->row_exists(DB_TABLE_INTERNAL_AUTHENTICATION, 'WHERE login=:login', array('login' => $this->login)))
		{
			throw new Exception($this->login . ' login already use');
		}
		else if (UserService::user_exists('WHERE email=:email', array('email' => $this->email)))
		{
			throw new Exception($this->email . ' email already use');
		}
		else
		{
			$user = new User();
			$user->set_display_name($this->login);
			$user->set_level($this->get_real_value($this->level, $this->level_possible_values));
			$user->set_email($this->email);
			$auth_method = new PHPBoostAuthenticationMethod($this->login, $this->password);
			$auth_method->set_association_parameters($this->get_real_value($this->approbation, $this->approbation_possible_values));
			if (UserService::create($user, $auth_method))
				CLIOutput::writeln('User added successfull');
			else
				CLIOutput::writeln('User ' . $this->login . ' already exists!');
		}
	}
	
	private function get_real_value($name, Array $values)
	{
		foreach ($values as $key => $value)
		{
			if ($name == $value)
			{
				return $key;
			}
		}
	}
	
	private function level_is_valid($level)
	{
		if (in_array($level, $this->level_possible_values))
		{
			return true;
		}
		throw new ArgumentNotAvailableException($level, $this->show_possible_values($this->level_possible_values));
	}

	private function approbation_is_valid($approbation)
	{
		if (in_array($approbation, $this->approbation_possible_values))
		{
			return true;
		}
		throw new ArgumentNotAvailableException($approbation, $this->show_possible_values($this->approbation_possible_values));
	}
	
	private function show_parameter($name, $value)
	{
		CLIOutput::writeln("\t" . $name . ' ' . $value);
	}
}
?>