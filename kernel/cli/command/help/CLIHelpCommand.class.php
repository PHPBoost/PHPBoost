<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 02 04
 * @since       PHPBoost 3.0 - 2010 02 06
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CLIHelpCommand implements CLICommand
{
    public function short_description(): string
    {
        return 'describe phpboost commands';
    }

    public function help(array $args): void
    {
        CLIOutput::writeln('scenario: phpboost help command [args ...]');
        CLIOutput::writeln('where command is the name of the command that you want to know more about');
        CLIOutput::writeln('[args ...] are the optionnal help parameters that can be given to the ' .
        'help command operation (depends of the command)');
        $this->print_commands_descriptions();
    }

    public function execute(array $args): void
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

    public function print_commands_descriptions(): void
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

    private function call(string $command, array $args): void
    {
        $provider_service = AppContext::get_extension_provider_service();
        $providers = $provider_service->get_providers(CLICommands::EXTENSION_POINT);
        foreach ($providers as $provider)
        {
            $commands = $provider->get_extension_point(CLICommands::EXTENSION_POINT);
            if (in_array($command, $commands->get_commands(), true))
            {
                $commands->help($command, $args);
                return;
            }
        }
        CLIOutput::writeln('command ' . $command . ' does not exist');
        $this->help([]);
    }

    private function display_short_description(string $command, string $description): void
    {
        CLIOutput::writeln("\t" . '- ' . $command . ': ' . $description);
    }
}
?>
