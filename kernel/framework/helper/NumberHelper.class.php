<?php
/**
 * Number helper
 * @package     Helper
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2010 01 24
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class NumberHelper
{
	/**
	 * Converts a string to a numeric value.
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
	 * Rounds a number
	 * @param mixed $number Number to round
	 * @param int $dec The number of decilam points
	 * @return string The rounded number.
	 */
	public static function round($number, $dec)
	{
		return floatval(number_format($number, $dec, '.', ''));
	}
}
?>
