<?php
/*##################################################
 *                    ExceptionUtils.class.php
 *                            -------------------
 *   begin                : October 20, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : horn@phpboost.com
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