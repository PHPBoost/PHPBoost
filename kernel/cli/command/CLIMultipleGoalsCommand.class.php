<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 02 04
 * @since       PHPBoost 3.0 - 2010 04 11
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class CLIMultipleGoalsCommand implements CLICommand
{
    private array $goals = [];
    private string $name = '';

    public function __construct(string $name, array $goals)
    {
        $this->name = $name;
        $this->goals = $goals;
    }

    public function help(array $args): void
    {
        CLIOutput::writeln('scenario: phpboost ' . $this->name . ' <goal>');
        CLIOutput::writeln('where "goal" is the name of the operation that you want to launch');
        $this->print_commands_descriptions();
    }

    public function print_commands_descriptions(): void
    {
        CLIOutput::writeln('available goals are:');
        foreach ($this->goals as $name => $goal_classname)
        {
            $goal = $this->get_command($name);
            $this->display_short_description($name, $goal->short_description());
        }
    }

    public function execute(array $args): void
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

    private function display_short_description(string $goal, string $description): void
    {
        CLIOutput::writeln("\t" . '- ' . $goal . ': ' . $description);
    }

    private function call(string $goal_name, array $args): void
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
            $this->help([]);
        }
    }

    /**
     * @return CLICommand
     * @throws CommandNotFoundException whether the command is not defined
     */
    private function get_command(string $command_name): CLICommand
    {
        if (isset($this->goals[$command_name]))
        {
            return new $this->goals[$command_name]();
        }
        throw new CommandNotFoundException($command_name);
    }

    private function print_commands_helps(array $args): void
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

    private function print_all_commands_helps(): void
    {
        CLIOutput::writeln('Usage: phpboost ' . $this->name . ' <goal> <opts> where goal is in this list');
        foreach ($this->get_commands_instances() as $name => $command)
        {
            CLIOutput::writeln("\t" . $name . ': ' . $command->short_description());
        }
    }

    private function get_commands_instances(): array
    {
        $instances = [];
        foreach ($this->goals as $goal_name => $goal_class)
        {
            $instances[$goal_name] = $this->get_command($goal_name);
        }
        return $instances;
    }

    private function print_command_help(string $command_name): void
    {
        $command = $this->get_command($command_name);
        CLIOutput::writeln('Usage: phpboost ' . $this->name . ' ' . $command_name . ' <opts>');
        $command->help([]);
    }
}
?>
