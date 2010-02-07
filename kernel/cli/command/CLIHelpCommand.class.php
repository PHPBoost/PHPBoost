<?php
/*##################################################
 *                          CLIHelpCommand.class.php
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
			$cli = $this->call($command, $args);
		}
	}

	private function call($command, array $args)
	{
		$mds = AppContext::get_extension_provider_service();
		foreach ($mds->get_available_modules(CLICommand::EXTENSION_POINT) as $extension_provider)
		{
			$new_commands = $extension_provider->call(CLICommand::EXTENSION_POINT);
			if (array_key_exists($command, $new_commands))
			{
				$cli = new $new_commands[$command]();
				$cli->help($args);
				return;
			}
		}
		CLIOutput::writeln('command ' . $command . ' does not exist');
	}
}
?>