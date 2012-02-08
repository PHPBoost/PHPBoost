<?php
/*##################################################
 *                            JSONBuilder.class.php
 *                            -------------------
 *   begin                : October 31, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc This class allows you to build JSON objects from a PHP array.
 * @package {@package}
 */
class JSONBuilder
{
	public static function build(array $object)
	{
		return self::build_array($object);
	}

	private static function build_array(array $array)
	{
		$values = array();
		$is_map = self::is_map($array);
		foreach ($array as $key => $value)
		{
			if ($is_map)
			{
				$values[] = '"' . $key . '":' . self::build_element($value);
			}
			else
			{
				$values[] = self::build_element($value);
			}
		}
		return '{' . implode(',', $values) . '}';
	}

	private static function build_element($object)
	{
		if (is_array($object))
		{
			return self::build_array($object);
		}
		return self::build_raw_value($object);
	}

	private static function build_raw_value($value)
	{
		if (is_bool($value))
		{
			return $value ? 'true' : 'false';
		}
		elseif (is_int($value))
		{
			return strval($value);
		}
		elseif (is_float($value))
		{
			return strval($value);
		}
		return TextHelper::to_json_string($value);
	}

	private static function is_map(array $array)
	{
		foreach (array_keys($array) as $key)
		{
			if (is_string($key))
			{
				return true;
			}
		}
		return false;
	}
}

?>