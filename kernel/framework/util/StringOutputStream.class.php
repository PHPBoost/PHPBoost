<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 07 08
*/

class StringOutputStream
{
    /**
     * @var string The stream content
     */
    private string $stream;

    /**
     * @var int The current index in the stream
     */
    private int $index = 0;

    /**
     * @var int The length of the stream
     */
    private int $length;

    /**
     * Constructs a new StringOutputStream.
     *
     * @param string $string The initial string content
     */
    public function __construct(string $string = '')
    {
        $this->stream = $string;
        $this->length = TextHelper::strlen($this->stream);
    }

    /**
     * Writes a string to the stream.
     *
     * @param string $string The string to write
     */
    public function write(string $string): void
    {
        $this->stream .= $string;
        $this->length = TextHelper::strlen($this->stream);
    }

    /**
     * Returns the stream as a string.
     *
     * @return string The stream content
     */
    public function to_string(): string
    {
        return $this->stream;
    }
}
?>
