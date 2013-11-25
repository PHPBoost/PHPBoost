<?php
/*##################################################
 *                          CLIHelpCommand.class.php
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

class CLIHelpCommand implements CLICommand
{
	public function short_description()
	{
		return 'describe phpboost commands';
	}

	public function help(array $args)
	{
		CLIOutput::writeln('scenario: phpboost help command [args ...]');
		CLIOutput::writeln('where command is the name of the command that you want to know more about');
		CLIOutput::writeln('[args ...] are the optionnal help parameters that can be given to the ' .
		'help command operation (depends of the command)');
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
			$command = array_shift($args);
			$this->call($command, $args);
		}
	}

    public function print_commands_descriptions()
    {
        CLIOutput::writeln('available commands are:');
        $provider_service = AppContext::get_extension_provider_service();
        $extension_point = $provider_service->get_extension_point(CLICommands::EXTENSION_POINT);
        foreach ($extension_point as $commands)
        {
            foreach ($commands->get_commands() as $command)
            {
                $this->display_short_description($command, $commands->get_short_description($command));
            }
        }
    }

	private function call($command, array $args)
	{
		$provider_service = AppContext::get_extension_provider_service();
		$providers = $provider_service->get_providers(CLICommands::EXTENSION_POINT);
		foreach ($providers as $provider)
		{
			$commands = $provider->get_extension_point(CLICommands::EXTENSION_POINT);
			if (in_array($command, $commands->get_commands()))
			{
				$commands->help($command, $args);
				return;
			}
		}
		CLIOutput::writeln('command ' . $command . ' does not exist');
		$this->help(array());
	}
	
	private function display_short_description($command, $description)
	{
		CLIOutput::writeln("\t" . '- ' . $command . ': ' . $description);
	}
}
?>