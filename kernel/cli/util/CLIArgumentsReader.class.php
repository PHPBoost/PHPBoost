<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 02 04
 * @since       PHPBoost 3.0 - 2010 02 06
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CLIArgumentsReader
{
    private array $args;
    private int $nb_args;

    public function __construct(array $args)
    {
        $this->args = $args;
        $this->nb_args = count($this->args);
    }

    public function get(string $option, $default_value = null): ?string
    {
        try
        {
            $index = $this->find_arg_index($option);
            return $this->get_arg_at($index + 1);
        }
        catch (Exception $ex)
        {
            if ($default_value !== null)
            {
                return $default_value;
            }
            throw new ArgumentNotFoundException($option, $this->args);
        }
    }

    public function has_arg(string $arg): bool
    {
        return in_array($arg, $this->args, true);
    }

    public function find_arg_index(string $arg): int
    {
        if ($this->has_arg($arg))
        {
            return array_search($arg, $this->args, true);
        }
        throw new ArgumentNotFoundException($arg, $this->args);
    }

    public function get_arg_at(int $index): string
    {
        if (isset($this->args[$index]))
        {
            return $this->args[$index];
        }
        throw new OutOfBoundsException($index . '/' . $this->nb_args);
    }

    public function get_nb_args(): int
    {
        return $this->nb_args;
    }
}
?>
