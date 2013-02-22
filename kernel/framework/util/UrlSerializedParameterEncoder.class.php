<?php
/*##################################################
 *                     UrlSerializedParameterEncoder.class.php
 *                            -------------------
 *   begin                : February 28, 2010
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
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @package {@package}
 */
class UrlSerializedParameterEncoder
{
	public static function encode(array $parameters)
	{
		return self::encode_array_values($parameters);
	}

    private static function encode_array_values(array $array)
    {
        $serialized_parameters = array();
        foreach ($array as $key => $value)
        {
            $serialized_parameters[] = self::encode_parameter($key, $value);
        }
        return join(',', $serialized_parameters);
    }

	private static function encode_array(array $array)
	{
		return '{' . self::encode_array_values($array) . '}';
	}

	private static function encode_parameter($key, $value)
	{
		return self::encode_name($key) . self::encode_value($value);
	}

	private static function encode_name($key)
	{
		if (is_string($key) && preg_match('`^[a-z][a-z0-9]*$`i', $key))
		{
			return $key . ':';
		}
		return '';
	}

	private static function encode_value($value)
	{
		if (is_array($value))
		{
			return self::encode_array($value);
		}
		else
		{
			return self::encode_string($value);
		}
	}

	private static function encode_string($value)
	{
		return addcslashes((string) $value, ':{}\\,');
	}
}
?>