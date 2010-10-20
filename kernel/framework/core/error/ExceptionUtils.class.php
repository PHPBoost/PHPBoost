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
			return Path::get_path_from_root($call['file']) . ':' . $call['line'];
		}
		return 'Internal';
	}

	public static function get_method_prototype($call)
	{
		$prototype = '';
		if (!empty($call['class']))
		{
			$prototype .= $call['class'] . $call['type'];
		}
		$prototype .= $call['function'];
		return $prototype;
	}

	public static function has_args($call)
	{
		return !empty($call['args']);
	}

	public static function get_args($call)
	{
		$args = $call['args'];
		$trace = '<ul>';
		foreach ($args as $arg)
		{
			$trace .= '<li><pre>' . htmlspecialchars(print_r($arg, true)) . '</pre></li>';
		}
		$trace .= '</ul>';
		return $trace;
	}
}

?>