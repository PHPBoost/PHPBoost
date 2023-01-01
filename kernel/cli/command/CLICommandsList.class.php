<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 07
*/

class CLICommandsList implements CLICommands
{
	private $commands = array();

	public function __construct(array $commands)
	{
		$this->commands = $commands;
	}

	public function get_commands()
	{
		return array_keys($this->commands);
	}

    public function get_short_description($cmd)
    {
        $command = $this->get_command($cmd);
        return $command->short_description();
    }

    public function help($cmd, array $args)
    {
        $command = $this->get_command($cmd);
        $command->help($args);
    }

    public function execute($cmd, array $args)
    {
        $command = $this->get_command($cmd);
        $command->execute($args);
    }

    /**
     * @param string $command the command name
     * @return CLICommand
     */
    private function get_command($command)
    {
        if (!array_key_exists($command, $this->commands))
        {
            throw new CommandNotFoundException($command);
        }
        return new $this->commands[$command]();
    }
}
?>
