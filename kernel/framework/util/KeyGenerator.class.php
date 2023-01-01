<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 06 19
*/

class KeyGenerator
{
	public static function generate_key($length = null)
	{
		if ($length == null)
		{
			return self::string_hash(uniqid(mt_rand(), true), false);
		}
		else
		{
			return TextHelper::substr(self::string_hash(uniqid(mt_rand(), true), false), 0, $length);
		}
	}

	public static function generate_token()
	{
		return self::generate_key(16);
	}

	/**
	 * Return a SHA256 hash of the $str string [with a salt]
	 * @param string $string the string to hash
	 * @param mixed $salt If true, add the default salt : md5($str)
	 * if a string, use this string as the salt
	 * if false, do not use any salt
	 * @return string a SHA256 hash of the $string string [with a salt]
	*/
	public static function string_hash($string, $salt = true)
	{
		if ($salt === true)
		{
			$string = md5($string) . $string;
		}
		elseif ($salt !== false)
		{
			$string = $salt . $string;
		}
		return hash('sha256', $string);
	}
}
?>
