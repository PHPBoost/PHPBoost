<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 25
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Arrays
{
    /**
     * Searches for $key in $values keys and returns the associated value.
     * If it doesn't exist, it will return the $default value if it is not null. Otherwise, a
     * TokenNotFoundException will be thrown.
     *
     * @param mixed $key The value to look for
     * @param array $values The available values
     * @param mixed $default The default value to return if the $key is not found
     * @return mixed The corresponding value
     * @throws TokenNotFoundException
     */
    public static function find($key, array $values, $default = null): mixed
    {
        if (array_key_exists($key, $values))
        {
            return $values[$key];
        }
        if ($default === null)
        {
            throw new TokenNotFoundException($key);
        }
        return $default;
    }

    /**
     * Removes a key from an array.
     *
     * @param mixed $key The key to remove
     * @param array &$values The array to modify
     */
    public static function remove_key($key, array &$values): void
    {
        if (array_key_exists($key, $values))
        {
            unset($values[$key]);
        }
    }
}
?>
