<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 11 27
 * @since       PHPBoost 3.0 - 2010 07 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class StringInputStream
{
    /**
     * @var string The stream content
     */
    private string $stream;

    /**
     * @var int The current index in the stream
     */
    private int $index = -1;

    /**
     * @var int The length of the stream
     */
    private int $length;

    /**
     * Constructs a new StringInputStream.
     *
     * @param string $string The input string
     */
    public function __construct(string $string)
    {
        $this->stream = $string;
        $this->length = TextHelper::strlen($this->stream);
    }

    /**
     * Checks if there is a next character in the stream.
     *
     * @return bool True if there is a next character, false otherwise
     */
    public function has_next(): bool
    {
        return $this->index < ($this->length - 1);
    }

    /**
     * Moves to the next character and returns it.
     *
     * @return string The next character
     */
    public function next(): string
    {
        $this->index++;
        return $this->get_current();
    }

    /**
     * Gets the current character.
     *
     * @return string The current character
     * @throws OutOfBoundsException If the index is out of bounds
     */
    public function get_current(): string
    {
        if ($this->index < 0)
        {
            throw new OutOfBoundsException('Out of stream', 0);
        }
        return $this->stream[$this->index];
    }

    /**
     * Gets the next character without moving the index.
     *
     * @return string The next character
     * @throws OutOfBoundsException If there is no next character
     */
    public function get_next(): string
    {
        if (!$this->has_next())
        {
            throw new OutOfBoundsException('End of stream', 0);
        }
        return $this->stream[$this->index + 1];
    }

    /**
     * Gets the previous character without moving the index.
     *
     * @return string The previous character
     * @throws OutOfBoundsException If there is no previous character
     */
    public function get_previous(): string
    {
        if ($this->index <= 0)
        {
            throw new OutOfBoundsException('Beginning of stream', 0);
        }
        return $this->stream[$this->index - 1];
    }

    /**
     * Asserts if the next part of the stream matches the given pattern.
     *
     * @param string $pattern The pattern to match
     * @param string $options The regex options
     * @param array|null $matches The matches array
     * @return int The number of matches
     */
    public function assert_next(string $pattern, string $options = '', ?array &$matches = null): int
    {
        $subject = TextHelper::substr($this->stream, $this->index + 1);
        return preg_match('`^(?:' . $pattern . ')`u' . $options, $subject, $matches);
    }

    /**
     * Consumes the next part of the stream if it matches the given pattern.
     *
     * @param string $pattern The pattern to match
     * @param string $options The regex options
     * @param array|null $matches The matches array
     * @return bool True if the pattern was matched and consumed, false otherwise
     */
    public function consume_next(string $pattern, string $options = '', ?array &$matches = null): bool
    {
        if ($this->assert_next($pattern, $options, $matches))
        {
            $this->move(TextHelper::strlen($matches[0]));
            return true;
        }
        return false;
    }

    /**
     * Moves the index by the given delta.
     *
     * @param int $delta The number of characters to move
     */
    public function move(int $delta): void
    {
        $new_index = $this->index + $delta;
        $this->seek($new_index);
    }

    /**
     * Moves the index by the given delta, ensuring it stays within bounds.
     *
     * @param int $delta The number of characters to move
     */
    public function safe_move(int $delta): void
    {
        $new_index = $this->index + $delta;
        $safe_index = max(0, min($new_index, $this->length - 1));
        $this->seek($safe_index);
    }

    /**
     * Gets the current index.
     *
     * @return int The current index
     */
    public function tell(): int
    {
        return $this->index;
    }

    /**
     * Sets the current index.
     *
     * @param int $new_index The new index
     * @throws OutOfBoundsException If the new index is out of bounds
     */
    public function seek(int $new_index): void
    {
        if (!($new_index >= -1 && $new_index < $this->length))
        {
            throw new OutOfBoundsException('New index ' . $new_index . ' is out of bounds (size: ' . $this->length . ')', 0);
        }
        $this->index = $new_index;
    }

    /**
     * Gets a substring from the current position.
     *
     * @param int $delta The offset from the current position
     * @param int $max_length The maximum length of the substring
     * @return string The substring
     */
    public function to_string(int $delta = 0, int $max_length = 50): string
    {
        $old_index = $this->index;
        $this->safe_move($delta);
        $str = TextHelper::substr($this->stream, $this->index);
        $this->seek($old_index);
        if ($max_length > 0)
        {
            return TextHelper::substr($str, 0, $max_length);
        }
        return $str;
    }

    /**
     * Gets the entire string.
     *
     * @return string The entire string
     */
    public function entire_string(): string
    {
        return $this->stream;
    }
}
?>
