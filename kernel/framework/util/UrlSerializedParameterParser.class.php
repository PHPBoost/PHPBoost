<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2016 11 15
 * @since       PHPBoost 3.0 - 2010 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class UrlSerializedParameterParser
{
    /**
     * @var string The regex pattern for parameter names
     */
    private static string $param_name_regex = '`^([a-z][a-z0-9-]*):`iu';

    /**
     * @var string The escape character
     */
    private static string $escape_char = '\\';

    /**
     * @var string The parameter separator
     */
    private static string $parameter_separator = ',';

    /**
     * @var string The composed parameter start character
     */
    private static string $composed_parameter_start_char = '{';

    /**
     * @var string The composed parameter end character
     */
    private static string $composed_parameter_end_char = '}';

    /**
     * @var string The arguments to parse
     */
    private string $args;

    /**
     * @var int The length of the arguments
     */
    private int $args_length;

    /**
     * @var int The current index in the arguments
     */
    private int $args_index = 0;

    /**
     * @var array The parsed parameters
     */
    private array $parameters = [];

    /**
     * Constructs a new UrlSerializedParameterParser.
     *
     * @param string $args The arguments to parse
     */
    public function __construct(string $args)
    {
        $this->args = $args;
        $this->args_length = TextHelper::strlen($this->args);
        $this->parse();
    }

    /**
     * Gets the parsed parameters.
     *
     * @return array The parsed parameters
     */
    public function get_parameters(): array
    {
        return $this->parameters;
    }

    /**
     * Parses the arguments.
     */
    private function parse(): void
    {
        while (!$this->is_ended())
        {
            $this->parse_next_parameter($this->parameters);
        }
    }

    /**
     * Parses the next parameter.
     *
     * @param array $parameters The parameters array to fill
     */
    private function parse_next_parameter(array &$parameters): void
    {
        if ($this->is_named())
        {
            $name = $this->parse_parameter_name();
            $value = $this->parse_parameter_value();
            $parameters[$name] = $value;
        }
        else
        {
            $value = $this->parse_parameter_value();
            $parameters[] = $value;
        }
    }

    /**
     * Checks if the current parameter is named.
     *
     * @return bool True if the parameter is named, false otherwise
     */
    private function is_named(): bool
    {
        return (bool)preg_match(self::$param_name_regex, $this->get_remaining_args());
    }

    /**
     * Parses the parameter name.
     *
     * @return string The parsed parameter name
     */
    private function parse_parameter_name(): string
    {
        $matches = [];
        preg_match(self::$param_name_regex, $this->get_remaining_args(), $matches);
        $name = $matches[1];
        $this->consume_chars(TextHelper::strlen($name) + 1);
        return $name;
    }

    /**
     * Parses the parameter value.
     *
     * @return mixed The parsed parameter value
     */
    private function parse_parameter_value()
    {
        if ($this->is_parameter_composed())
        {
            return $this->parse_composed_parameter();
        }
        else
        {
            return $this->parse_simple_parameter();
        }
    }

    /**
     * Checks if the parameter is composed.
     *
     * @return bool True if the parameter is composed, false otherwise
     */
    private function is_parameter_composed(): bool
    {
        return !$this->is_ended() && $this->assert_next_character_is(self::$composed_parameter_start_char);
    }

    /**
     * Parses a composed parameter.
     *
     * @return array The parsed composed parameter
     */
    private function parse_composed_parameter(): array
    {
        $values = [];
        $this->consume_next_char();
        while (!$this->is_composed_parameter_ended())
        {
            $this->parse_next_parameter($values);
        }
        $this->consume_if(self::$composed_parameter_end_char);
        $this->consume_if(self::$parameter_separator);
        return $values;
    }

    /**
     * Checks if the composed parameter is ended.
     *
     * @return bool True if the composed parameter is ended, false otherwise
     */
    private function is_composed_parameter_ended(): bool
    {
        return $this->is_ended() || $this->assert_next_character_is(self::$composed_parameter_end_char);
    }

    /**
     * Parses a simple parameter.
     *
     * @return string The parsed simple parameter
     */
    private function parse_simple_parameter(): string
    {
        $value = '';
        $length = $this->get_nb_remaining_chars();
        $escaped = false;
        for ($i = 0; $i < $length; $i++)
        {
            $current = $this->consume_next_char();
            if (!$escaped)
            {
                if ($current === self::$escape_char)
                {
                    $escaped = true;
                    continue;
                }
                if ($current === self::$parameter_separator)
                {
                    break;
                }
                if ($current === self::$composed_parameter_end_char)
                {
                    $this->rollback_last_char_consumed();
                    break;
                }
            }
            $escaped = false;
            $value .= $current;
        }
        return $value;
    }

    /**
     * Consumes a specified number of characters.
     *
     * @param int $nb_characters_to_consume The number of characters to consume
     */
    private function consume_chars(int $nb_characters_to_consume): void
    {
        $this->args_index += $nb_characters_to_consume;
    }

    /**
     * Consumes the next character.
     *
     * @return string The consumed character
     */
    private function consume_next_char(): string
    {
        return $this->args[$this->args_index++];
    }

    /**
     * Rolls back the last consumed character.
     */
    private function rollback_last_char_consumed(): void
    {
        $this->args_index--;
    }

    /**
     * Checks if the parsing is ended.
     *
     * @return bool True if the parsing is ended, false otherwise
     */
    private function is_ended(): bool
    {
        return $this->args_index >= $this->args_length;
    }

    /**
     * Checks if the next character is the specified character.
     *
     * @param string $char The character to check
     * @return bool True if the next character is the specified character, false otherwise
     */
    private function assert_next_character_is(string $char): bool
    {
        return $this->args[$this->args_index] === $char;
    }

    /**
     * Consumes the next character if it is the specified character.
     *
     * @param string $char The character to consume
     */
    private function consume_if(string $char): void
    {
        if (!$this->is_ended() && $this->assert_next_character_is($char))
        {
            $this->consume_next_char();
        }
    }

    /**
     * Gets the number of remaining characters.
     *
     * @return int The number of remaining characters
     */
    private function get_nb_remaining_chars(): int
    {
        return $this->args_length - $this->args_index;
    }

    /**
     * Gets the remaining arguments.
     *
     * @return string The remaining arguments
     */
    private function get_remaining_args(): string
    {
        return TextHelper::substr($this->args, $this->args_index);
    }

    /**
     * Serializes the parameters.
     *
     * @param array $parameters The parameters to serialize
     */
    private function serialize_parameters(array $parameters): void
    {
        // TODO
    }
}
?>
