<?php
/*##################################################
 *                             debug.class.php
 *                            -------------------
 *   begin                : October 3, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc
 * @package util
 */
class Debug
{
	/**
	 * @desc Displays information on an exception and exits
	 * @param Exception $exception the exception to display information on
	 */
	public static function fatal(Exception $exception)
	{
		$message = $exception->getMessage();
		if (empty($message))
		{
			$message = 'An exception has been thrown';
		}
		echo '<br />' . $message . '<br /><br />Stack<hr />';
		Debug::print_stacktrace(0, $exception);
		exit;
	}

	/**
	 * @desc prints the stacktrace and exits
	 */
	public static function stop()
	{
		self::print_stacktrace();
		exit;
	}

	/**
	 * @desc returns the current exception
	 * @return Exception the current exception
	 */
	public static function get_exception_context()
	{
		return new Exception();
	}

	/**
	 * @desc returns the current stacktrace
	 * @return string the current stacktrace
	 */
	public static function get_stacktrace()
	{
		$stack = self::get_exception_context()->getTrace();
		unset($stack[0]);
		return array_merge($stack, array());
	}

	/**
	 * @desc print the current stacktrace
	 */
	public static function get_stacktrace_as_string($start_trace_index = 0,
	Exception $exception = null)
	{
		$string_stacktrace = '';
		$stacktrace = null;
		if ($exception === null)
		{
			$stacktrace = self::get_stacktrace();
		}
		else
		{
			$start_trace_index--;
			$stacktrace = $exception->getTrace();
		}
		$stacktrace_size = count($stacktrace);
		$start_trace_index = $start_trace_index + 1;
		for ($i = $start_trace_index; $i < $stacktrace_size; $i++)
		{
			$trace =& $stacktrace[$i];
			$string_stacktrace .= '[' . ($i - $start_trace_index) . '] ' .
			self::get_file($trace) . ' - ' . self::get_method_prototype($trace) . '<br />';
		}
		return $string_stacktrace;
	}

	/**
	 * @desc print the current stacktrace
	 */
	public static function print_stacktrace($start_trace_index = 0, Exception $exception = null)
	{
		if ($exception !== null)
		{
			$start_trace_index--;
		}
		echo self::get_stacktrace_as_string($start_trace_index + 1, $exception);
	}

	private static function get_file($trace)
	{
		if (!empty($trace['file']))
		{
			return Path::get_path_from_root($trace['file']) . ':' . $trace['line'];
		}
		return 'Internal';
	}

	private static function get_method_prototype($call)
	{
		$prototype = '<b>';
		if (!empty($call['class']))
		{
			$prototype .= $call['class'] . $call['type'];
		}
		$prototype .= $call['function'] . '(</b>';
		if (!empty($call['args']))
		{
			$prototype .= self::get_args($call['args']);
		}
		$prototype .= '<b>)</b>';
		return $prototype;
	}

	private static function get_args($args)
	{
		$string_stacktrace = '';

		$i = 0;
		$count = count($args) - 1;
		foreach ($args as $arg)
		{
			if (is_numeric($arg))
			{
				$string_stacktrace .= (int) $arg;
			}
			elseif (is_bool($arg))
			{
				$string_stacktrace .= ($arg ? 'True' : 'False');
			}
			elseif (is_object($arg))
			{
				$string_stacktrace .= get_class($arg);
			}
			elseif (is_array($arg))
			{
				$string_stacktrace .= 'Array[' . count($arg) . ']';
			}
			else
			{
				$string_maxlength = 20;
				if (strlen($arg) > $string_maxlength)
				{
					$arg = substr($arg, 0, $string_maxlength - 3) . '...';
				}
				$string_stacktrace .= '\'' . htmlspecialchars(addslashes($arg)) . '\'';
			}

			if ($i < $count)
			{
				$string_stacktrace .= ', ';
			}
			$i++;
		}
		return $string_stacktrace;
	}
}
?>