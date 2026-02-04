<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 02 04
 * @since       PHPBoost 3.0 - 2010 02 06
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CLIOutput
{
    private static File $err_output;

    public static function init(): void
    {
        self::$err_output = new File('php://stderr');
    }

    public static function write(string $message): void
    {
        echo $message;
    }

    public static function writeln(string $message = '', int $nbLinesBreak = 1): void
    {
        $break = str_repeat("\n", $nbLinesBreak);
        self::write($message . $break);
    }

    /**
     * Prints a message in the error output.
     * @param string $message The message to print
     */
    public static function err(string $message): void
    {
        self::$err_output->append($message . "\n");
    }
}
?>
