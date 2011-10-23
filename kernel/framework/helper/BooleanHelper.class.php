<?php
/*##################################################
 *                             BooleanHelper.class.php
 *                            -------------------
 *   begin                : September 09, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

/**
 * @desc Boolean helper
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
 */
class BooleanHelper
{
	/**
	 * @desc Converts a value to a boolean value.
	 * @param mixed $var The value you want to convert.
	 * @return boolean The Boolean value.
	 */
	public static function boolean($value)
	{
		if (self::is_boolean($value))
		{
			return $value;
		}
		else
		{
			return (bool) $value;
		}
	}
	
	public static function is_boolean($value)
	{
		return is_bool($value);
	}
}
?>