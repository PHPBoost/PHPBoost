<?php
/*##################################################
 *                          CLIOutput.class.php
 *                            -------------------
 *   begin                : February 06, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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