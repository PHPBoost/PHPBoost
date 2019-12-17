<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 06
*/

class CLILauncher
{
	private $args;
	private $command = '';
	private $commands = array();

	/**
	 * @var CLICommands
	 */
	private $cli_commands;

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

	/**
	 * @return bool true whether it works, false whether an error occurred
	 */
	public function launch()
	{
		try
		{
			$this->do_launch();
			return true;
		}
		catch (Exception $exception)
		{
			CLIOutput::err('Uncaught exception: ' . $exception->getMessage() . ' at:');
			CLIOutput::err($exception->getTraceAsString());
			return false;
		}
	}

	private function do_launch()
	{
		if ($this->find_command())
		{
			$this->execute_command();
		}
		else
		{
			if (empty($this->command))
			{
				CLIOutput::writeln('no command specified');
			}
			else
			{
				CLIOutput::writeln('command ' . $this->command . ' does not exists');
			}
			$this->display_commands();
		}
	}

	private function load_commands_list()
	{
		$provider_service = AppContext::get_extension_provider_service();
		$extension_point = $provider_service->get_extension_point(CLICommands::EXTENSION_POINT);
		foreach ($extension_point as $commands)
		{
			foreach ($commands->get_commands() as $command)
			{
				$this->commands[$command] = $commands;
			}
		}
	}

	private function find_command()
	{
		if (array_key_exists($this->command, $this->commands))
		{
			$this->cli_commands = $this->commands[$this->command];
			return true;
		}
		return false;
	}

	private function execute_command()
	{
		$args = $this->args;
		array_shift($args);
		$this->cli_commands->execute($this->command, $args);
	}

	private function display_commands()
	{
		$help = new CLIHelpCommand();
		$help->print_commands_descriptions();
	}
}
?>
