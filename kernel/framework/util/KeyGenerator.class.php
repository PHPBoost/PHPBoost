<?php
/*##################################################
 *                               KeyGenerator.class.php
 *                            -------------------
 *   begin                : Juny 19, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @package {@package}
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
			return substr(self::string_hash(uniqid(mt_rand(), true), false), 0, $length);
		}
	}
	
	public static function generate_token()
	{
		return self::generate_key(16);
	}
	
	/**
	 * @desc Return a SHA256 hash of the $str string [with a salt]
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