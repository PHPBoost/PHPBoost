<?php
/*##################################################
 *                          CLIMultipleGoalsCommand.class.php
 *                            -------------------
 *   begin                : April 11, 2010
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

abstract class CLIMultipleGoalsCommand implements CLICommand
{
	private $goals = array();
	private $name = '';

	public function __construct($name, array $goals)
	{
		$this->name = $name;
		$this->goals = $goals;
	}

	public function help(array $args)
	{
		CLIOutput::writeln('scenario: phpboost ' . $this->name. ' <goal>');
		CLIOutput::writeln('where "goal" is the name of the operation that you want to launch');
		$this->print_commands_descriptions();
	}

	public function print_commands_descriptions()
	{
		CLIOutput::writeln('available goals are:');
		foreach ($this->goals as $name => $goal_classname)
		{
			$goal = $this->get_command($name);
			$this->display_short_description($name, $goal->short_description());
		}
	}

	public function execute(array $args)
	{
		if (empty($args))
		{
			$this->help($args);
		}
		else
		{
			$goal = array_shift($args);
			$this->call($goal, $args);
		}
	}

	private function display_short_description($goal, $description)
	{
		CLIOutput::writeln("\t" . '- ' . $goal . ': ' . $description);
	}

	private function call($goal_name, array $args)
	{
		try
		{
			if ($goal_name == 'help')
			{
				$this->print_commands_helps($args);
			}
			else
			{
				$goal = $this->get_command($goal_name);
				$goal->execute($args);
			}
		}
		catch (CommandNotFoundException $e)
		{
			CLIOutput::err($e->getMessage());
			$this->help(array());
		}
	}
	
	/**
	 * @return CliCommand
	 * @throws CommandNotFoundException whether the command is not defined
	 */
	private function get_command($command_name)
	{
		if (isset($this->goals[$command_name]))
		{
			return new $this->goals[$command_name]();
		}
		throw new CommandNotFoundException($command_name);
	}

	private function print_commands_helps($args)
	{
		if (count($args) == 0)
		{
			$this->print_all_commands_helps();
		}
		else
		{
			$this->print_command_help($args[0]);
		}
	}

	private function print_all_commands_helps()
	{
		CLIOutput::writeln('Usage: phpboost ' . $this->name . ' <goal> <opts> where goal is in this list');
		foreach ($this->get_commands_instances() as $name => $command)
		{
			CLIOutput::writeln("\t" . $name . ': ' . $command->short_description());
		}
	}

	private function get_commands_instances()
	{
		$instances = array();
		foreach ($this->goals as $goal_name => $goal_class)
		{
			$instances[$goal_name] = $this->get_command($goal_name);
		}
		return $instances;
	}

	private function print_command_help($command_name)
	{
		$command = $this->get_command($command_name);
		CLIOutput::writeln('Usage: phpboost ' . $this->name . ' ' . $command_name . ' <opts>');
		CLIOutput::writeln($command->help(array()));
	}
}
?>