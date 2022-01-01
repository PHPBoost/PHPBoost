<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 06
*/

class CLIOutput
{
	private static $err_output;

	public static function __static()
	{
		self::$err_output = new File('php://stderr');
	}

	public static function write($message)
	{
		echo $message;
	}

	public static function writeln($message = '', $nbLinesBreak = 1)
	{
		$break = '';
		for ($i = 0; $i < $nbLinesBreak; $i++)
		{
			$break .= "\n";
		}
		self::write($message . $break);
	}

	/**
	 * Prints a message in the error output.
	 * @param $message The message to print
	 */
	public static function err($message)
	{
		self::$err_output->append($message . "\n");
	}
}
?>
