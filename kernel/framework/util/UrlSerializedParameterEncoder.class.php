<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 14
 * @since       PHPBoost 3.0 - 2010 02 28
 * @contributor mipel <mipel@phpboost.com>
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
		if (is_string($key) && preg_match('`^[a-z][a-z0-9]*$`iu', $key))
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
