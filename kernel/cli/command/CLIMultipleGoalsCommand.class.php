<?php
/*##################################################
 *                          CLIMultipleGoalsCommand.class.php
 *                            -------------------
 *   begin                : April 11, 2010
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
		CLIOutput::writeln('scenario: phpboost ' . $this->name. ' goal');
		CLIOutput::writeln('where "goal" is the name of the operation that you want to launch');
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
			$goal = array_shift($args);
			$this->call($goal, $args);
		}
	}

	public function print_commands_descriptions()
	{
		CLIOutput::writeln('available goals are:');
		foreach ($this->goals as $name => $goal_classname)
		{
			$goal = new $goal_classname();
			$this->display_short_description($name, $goal->short_description());
		}
	}

	private function call($goal_name, array $args)
	{
		if (isset($this->goals[$goal_name]))
		{
			$goal = new $this->goals[$goal_name]();
			$goal->execute($args);
		}
		else
		{
			CLIOutput::writeln('goal ' . $goal_name . ' does not exist');
			$this->help(array());
		}
	}

	private function display_short_description($goal, $description)
	{
		CLIOutput::writeln("\t" . '- ' . $goal . ': ' . $description);
	}
}
?>