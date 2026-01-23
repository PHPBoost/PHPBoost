<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 06 19
*/

class KeyGenerator
{
    /**
     * Generates a key of the specified length.
     *
     * @param int|null $length The desired length of the key
     * @return string The generated key
     */
    public static function generate_key(?int $length = null): string
    {
        if ($length === null)
        {
            return self::string_hash(uniqid((string)mt_rand(), true), false);
        }
        else
        {
            return TextHelper::substr(self::string_hash(uniqid((string)mt_rand(), true), false), 0, $length);
        }
    }

    /**
     * Generates a token of 16 characters.
     *
     * @return string The generated token
     */
    public static function generate_token(): string
    {
        return self::generate_key(16);
    }

    /**
     * Returns a SHA256 hash of the string, optionally with a salt.
     *
     * @param string $string The string to hash
     * @param bool|string $salt If true, adds the default salt: md5($string).
     * If a string, uses this string as the salt.
     * If false, does not use any salt.
     * @return string A SHA256 hash of the string, optionally with a salt
     */
    public static function string_hash(string $string, $salt = true): string
    {
        if ($salt === true)
        {
            $string = md5($string) . $string;
        }
        elseif ($salt !== false)
        {
            $string = (string)$salt . $string;
        }
        return hash('sha256', $string);
    }
}
?>
