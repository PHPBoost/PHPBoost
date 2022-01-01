<?php
/**
 * @package     Core
 * @subpackage  Error
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 10 20
*/

class ExceptionUtils
{
	public static function get_file($call)
	{
		if (!empty($call['file']))
		{
			return Path::get_path_from_root($call['file']);
		}
		return 'Internal';
	}

	public static function get_line($call)
	{
		if (!empty($call['file']))
		{
			return $call['line'];
		}
		return '';
	}

	public static function get_method_prototype($call)
	{
		$prototype = '';
		if (!empty($call['class']))
		{
			$prototype .= $call['class'] . $call['type'];
		}
		$prototype .= $call['function'] . '(';
		$prototype .= implode(', ', self::get_args_types($call));
		$prototype .= ')';
		return $prototype;
	}

	public static function has_args($call)
	{
		return !empty($call['args']);
	}

	private static function get_args_types($call)
	{
		$types = array();
		if (empty($call['args']))
		{
			return $types;
		}
		foreach ($call['args'] as $arg)
		{
			$types[] = self::get_arg_type($arg);
		}
		return $types;
	}

	public static function get_arg_type($arg)
	{
		if (is_array($arg))
		{
			return 'array';
		}
		elseif (is_bool($arg))
		{
			return 'boolean';
		}
		elseif (is_int($arg))
		{
			return 'int';
		}
		elseif (is_float($arg))
		{
			return 'float';
		}
		elseif (is_string($arg))
		{
			return 'string';
		}
		elseif (is_object($arg))
		{
			return get_class($arg);
		}
		return 'null';
	}

	public static function get_args($call)
	{
		$args = $call['args'];
		$trace = '<ul>';
		foreach ($args as $arg)
		{
			$trace .= '<li><pre>' . TextHelper::htmlspecialchars(print_r($arg, true)) . '</pre></li>';
		}
		$trace .= '</ul>';
		return $trace;
	}
}
?>
