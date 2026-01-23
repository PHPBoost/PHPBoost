<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2016 11 14
 * @since       PHPBoost 3.0 - 2010 02 28
 * @contributor mipel <mipel@phpboost.com>
*/

class UrlSerializedParameterEncoder
{
    /**
     * Encodes an array of parameters.
     *
     * @param array $parameters The parameters to encode
     * @return string The encoded string
     */
    public static function encode(array $parameters): string
    {
        return self::encode_array_values($parameters);
    }

    /**
     * Encodes the values of an array.
     *
     * @param array $array The array to encode
     * @return string The encoded string
     */
    private static function encode_array_values(array $array): string
    {
        $serialized_parameters = [];
        foreach ($array as $key => $value)
        {
            $serialized_parameters[] = self::encode_parameter($key, $value);
        }
        return implode(',', $serialized_parameters);
    }

    /**
     * Encodes an array.
     *
     * @param array $array The array to encode
     * @return string The encoded string
     */
    private static function encode_array(array $array): string
    {
        return '{' . self::encode_array_values($array) . '}';
    }

    /**
     * Encodes a parameter.
     *
     * @param string|int $key The key of the parameter
     * @param mixed $value The value of the parameter
     * @return string The encoded parameter
     */
    private static function encode_parameter($key, $value): string
    {
        return self::encode_name($key) . self::encode_value($value);
    }

    /**
     * Encodes the name of a parameter.
     *
     * @param string|int $key The key to encode
     * @return string The encoded name
     */
    private static function encode_name($key): string
    {
        if (is_string($key) && preg_match('`^[a-z][a-z0-9]*$`iu', $key))
        {
            return $key . ':';
        }
        return '';
    }

    /**
     * Encodes the value of a parameter.
     *
     * @param mixed $value The value to encode
     * @return string The encoded value
     */
    private static function encode_value($value): string
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

    /**
     * Encodes a string.
     *
     * @param mixed $value The value to encode
     * @return string The encoded string
     */
    private static function encode_string($value): string
    {
        return addcslashes((string) $value, ':{}\\,');
    }
}
?>
