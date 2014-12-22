<?php
/*##################################################
 *                               Arrays.class.php
 *                            -------------------
 *   begin                : February 25, 2010
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
class Arrays
{
	/**
	 * @desc searches for $key in $values keys and returns the associated value.
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