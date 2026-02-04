<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 02 04
 * @since       PHPBoost 3.0 - 2010 02 07
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CLICommandsList implements CLICommands
{
    private array $commands = [];

    public function __construct(array $commands)
    {
        $this->commands = $commands;
    }

    public function get_commands(): array
    {
        return array_keys($this->commands);
    }

    public function get_short_description(string $cmd): string
    {
        $command = $this->get_command($cmd);
        return $command->short_description();
    }

    public function help(string $cmd, array $args): void
    {
        $command = $this->get_command($cmd);
        $command->help($args);
    }

    public function execute(string $cmd, array $args): void
    {
        $command = $this->get_command($cmd);
        $command->execute($args);
    }

    /**
     * @param string $command the command name
     * @return CLICommand
     */
    private function get_command(string $command): CLICommand
    {
        if (!array_key_exists($command, $this->commands))
        {
            throw new CommandNotFoundException($command);
        }
        return new $this->commands[$command]();
    }
}
?>
