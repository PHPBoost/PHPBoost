<?php
/*##################################################
 *                             NumberHelper.class.php
 *                            -------------------
 *   begin                : Januar 24, 2010
 *   copyright            : (C) 2010 Régis Viarre
 *   email                : crowkait@phpboost.com
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
 * @desc Number helper
 * @author Régis Viarre <crowkait@phpboost.com>
 * @package {@package}
 */
class NumberHelper 
{
	/**
	 * @desc Converts a string to a numeric value.
	 * @param string $var The value you want to convert.
	 * @param string $type 'int' if you want to convert to an integer value, 'float' if you want a floating value.
	 * @return mixed The integer or floating value (according to the type you chose).
	 */
	public static function numeric($var, $type = 'int')
	{
		if (is_numeric($var)) //Retourne un nombre
		{
			if ($type === 'float')
			{
				return (float)$var; //Nbr virgule flottante.
			}
			else
			{
				return (int)$var; //Nombre entier
			}
		}
		else
		{
			return 0;
		}
	}
	
	/**
	 * @desc Rounds a number
	 * @param mixed $number Number to round
	 * @param int $dec The number of decilam points
	 * @return string The rounded number.
	 */
	public static function round($number, $dec)
	{
		return trim(number_format($number, $dec, '.', ''));
	}
}
?>