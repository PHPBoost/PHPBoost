<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 25
*/

class Arrays
{
	/**
	 * searches for $key in $values keys and returns the associated value.
	 * If it doesn't exist, it will return the $default value if it is not null. Otherwise a
	 * TokenNotFoundException will be thrown
	 * @param mixed $key the value to look for
	 * @param mixed[mixed] $values the availables values
	 * @param mixed $default the default value to return if the $key is not found
	 * @return mixed the corresponding value
	 * @throws TokenNotFoundException
	 */
	public static function find($key, array $values, $default = null)
	{
		if (array_key_exists($key, $values))
		{
			return $values[$key];
		}
		if ($default == null)
		{
			throw new TokenNotFoundException($needle);
		}
		return $default;
	}

	public static function remove_key($key, array &$values)
	{
		if (array_key_exists($key, $values))
		{
			unset($values[array_search($key, $values)]);
		}
	}
}
?>
