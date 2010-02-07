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
	private $commands = array();
	
	/**
	 * @var CLILauncher
	 */
	private $cli;

	public function __construct(array $args = array())
	{
		array_shift($args);
		$this->args = $args;
		if (count($this->args) > 0)
		{
			$this->command = $this->args[0];
		}
		$this->load_commands_list();
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

    private function load_commands_list()
    {
        $mds = AppContext::get_extension_provider_service();
        foreach ($mds->get_available_modules(CLICommand::EXTENSION_POINT) as $extension_provider)
        {
        	$new_commands = $extension_provider->call(CLICommand::EXTENSION_POINT);
        	$this->commands = array_merge($this->commands, $new_commands);
        }
    }

    private function find_command()
    {
        if (array_key_exists($this->command, $this->commands))
        {
            $this->cli = new $this->commands[$this->command]();
            return true;
        }
        return false;
    }

	private function execute_command()
	{
		$args = $this->args;
        array_shift($args);
		$this->cli->execute($args);
	}

	private function display_commands()
	{
		CLIOutput::writeln('availables commands are:', 2);
		foreach ($this->commands as $command => $cli_classname)
		{
			$cli = new $cli_classname();
			CLIOutput::writeln("\t" . '- ' . $command . ': ' . $cli->short_description(), 2);
		}
	}
}
?>