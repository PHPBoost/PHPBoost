<?php
/*##################################################
 *                          CLILauncher.class.php
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

class CLILauncher
{
	private $args;
	private $command = '';
	private $commands = array(
       'install' => 'CLIInstallCommand');
	private $commands_help = array(
       'install' => 'install phpboost');
	
	/**
	 * @var CLILauncher
	 */
	private $launcher;

	public function __construct(array $args = array())
	{
		array_shift($args);
		$this->args = $args;
		if (count($this->args) > 0)
		{
			$this->command = $this->args[0];
		}
	}

	public function launch()
	{
		if ($this->find_command())
		{
			$this->execute_command();
		}
		else
		{
			if (empty($this->command))
			{
				CLIOutput::writeln('no command specified', 2);
			}
			else
			{
				CLIOutput::writeln('command ' . $this->command . ' does not exists', 2);
			}
			$this->display_commands();
		}
	}

	public function find_command()
	{
		if (array_key_exists($this->command, $this->commands))
		{
			$this->launcher = new $this->commands[$this->command]();
			return true;
		}
		return false;
	}

	public function execute_command()
	{
		$args = $this->args;
        array_shift($args);
		$this->launcher->execute($args);
	}

	public function display_commands()
	{
		CLIOutput::writeln('availables commands are:', 2);
		foreach ($this->commands_help as $command => $help)
		{
			CLIOutput::writeln("\t" . '- ' . $command . ': ' . $help, 2);
		}
	}
}
?>