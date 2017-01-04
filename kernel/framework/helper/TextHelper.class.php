<?php
/*##################################################
 *                             TextHelper.class.php
 *                            -------------------
 *   begin                : Januar 21, 2010
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
 * @desc Text helper
 * @author Régis Viarre <crowkait@phpboost.com>
 * @package {@package}
 */
class TextHelper
{
	const HTML_NO_PROTECT = false;
	const HTML_PROTECT = true;

	const ADDSLASHES_FORCE = 1; //Force l'échappement des caractères critique
	const ADDSLASHES_NONE = 2; //Aucun échappement

	/**
	 * @desc Protects an input variable. Never trust user input!
	 * @param string $var Variable to protect.
	 * @param bool $html_protect HTML_PROTECT if you don't accept the HTML code (it will be transformed
	 * by the corresponding HTML entities and won't be considerer by the web browsers).
	 * @param int $addslashes If you want to escape the quotes in the string, use ADDSLASHES_FORCE, if you don't want, use the ADDSLASHES_NONE constant.
	 * @return string The protected string.
	 */
	public static function strprotect($var, $html_protect = self::HTML_PROTECT, $addslashes = self::ADDSLASHES_FORCE)
	{
		$var = trim((string)$var);

		//Protection contre les balises html.
		if ($html_protect)
		{
			$var = self::htmlspecialchars($var);
			//While we aren't in UTF8 encoding, we have to use HTML entities to display some special chars, we accept them.
			$var = preg_replace('`&amp;((?:#[0-9]{2,5})|(?:[a-z0-9]{2,8}));`iu', "&$1;", $var);
		}

		switch ($addslashes)
		{
			case self::ADDSLASHES_FORCE:
			default:
				//On force l'échappement de caractères
				$var = addslashes($var);
				break;
			case self::ADDSLASHES_NONE:
				//On ne touche pas la chaîne
				$var = stripslashes($var);
				break;
		}

		return $var;
	}

	/**
	 * @desc Inserts a carriage return every $lenght characters. It's equivalent to wordwrap PHP function but it can deal with the HTML entities.
	 * An entity is coded on several characters and the wordwrap function counts several characters for an entity whereas it represents only one character.
	 * @param string $str The string to wrap.
	 * @param int $lenght The number of characters you want in a line.
	 * @param string $cut_char The character to insert every $lenght characters. The default value is '<br />', the HTML carriage return tag.
	 * @param bool $cut True if you accept that a word would be broken apart, false if you want to cut only on a blank character.
	 * @return string The wrapped HTML string.
	 */
	public static function wordwrap_html($str, $lenght, $cut_char = '<br />', $cut = true)
	{
		$str = self::utf8_wordwrap(self::html_entity_decode($str), $lenght, $cut_char, $cut);
		return str_replace('&lt;br /&gt;', '<br />', self::htmlspecialchars($str, ENT_NOQUOTES));
	}

	/**
	 * @desc Cuts a string containing some HTML code which contains some HTML entities. The substr PHP function considers a HTML entity as several characters.
	 * This function allows you to consider them as only one character.
	 * @param string $str The string you want to cut.
	 * @param int $start  If start  is non-negative, the returned string will start at the start 'th position in string , counting from zero. For instance, in the string 'abcdef', the character at position 0 is 'a', the character at position 2 is 'c', and so forth.
	 * If start is negative, the returned string will start at the start 'th character from the end of string .
	 * If string is less than or equal to start characters long, FALSE will be returned.
	 * @param int $end If length is given and is positive, the string returned will contain at most length  characters beginning from start  (depending on the length of string ).
	 * @return string The sub string.
	 */
	public static function substr_html($str, $start, $end = '')
	{
		if ($end == '')
		{
			return self::htmlspecialchars(TextHelper::substr(self::html_entity_decode($str), $start), ENT_NOQUOTES);
		}
		else
		{
			return self::htmlspecialchars(TextHelper::substr(self::html_entity_decode($str), $start, $end), ENT_NOQUOTES);
		}
	}

	/**
	 * @desc Exports a variable to be used in a javascript script.
     * @param string $string A PHP string to convert to a JS one
     * @param string $add_quotes If true, returned string will be bounded by single quotes
	 * @return string The js equivalent string
	 */
	public static function to_js_string($string, $add_quotes = true)
	{
		$bounds = $add_quotes ? '\'' : '';
		return $bounds . str_replace(array("\r\n", "\r", "\n", '"'), array('\n', '\n', '\n', '&quot;'),
		addcslashes($string, '\'')) . $bounds;
	}

	/**
	 * @desc Exports a variable to be used in a json javascript script.
     * @param string $string A PHP string to convert to a json one
     * @param string $add_quotes If true, returned string will be bounded by double quotes
	 * @return string The json equivalent string
	 */
	public static function to_json_string($string, $add_quotes = true)
	{
		$bounds = $add_quotes ? '"' : '';
		return $bounds . str_replace(array("\r\n", "\r", "\n", ), array('\n', '\n', '\n', ),
		addcslashes($string, '"')) . $bounds;
	}
	
	public static function htmlspecialchars($string, $flags = null, $encoding = 'UTF-8', $double_encode = true)
	{
		if ($flags === null)
		{
			$flags = ENT_COMPAT;
		}
		return str_replace('&amp;', '&', htmlspecialchars($string, $flags, $encoding, $double_encode));
	}
	
	public static function htmlspecialchars_decode($string, $flags = null)
	{
		if ($flags === null)
		{
			$flags = ENT_COMPAT;
		}
		return htmlspecialchars_decode($string, $flags);
	}
	
	public static function html_entity_decode($string, $flags = null, $encoding = 'UTF-8')
	{
		if ($flags === null)
		{
			$flags = ENT_COMPAT;
		}
		return html_entity_decode($string, $flags, $encoding);
	}
	
	public static function strtolower($string)
	{
		return mb_strtolower($string);
	}
	
	public static function strtoupper($string)
	{
		return mb_strtoupper($string);
	}
	
	public static function lcfirst($string)
	{
		$first_letter = self::strtolower(self::substr($string, 0, 1));
		$string_end = self::substr($string, 1, self::strlen($string));
		return $first_letter . $string_end;
	}
	
	public static function ucfirst($string)
	{
		$first_letter = self::strtoupper(self::substr($string, 0, 1));
		$string_end = self::substr($string, 1, self::strlen($string));
		return $first_letter . $string_end;
	}
	
	public static function strlen($string)
	{
		return strlen($string);
	}
	
	public static function strpos($string, $substring, $offset ='')
	{
		if (is_int($offset))
			return mb_strpos($string, $substring, $offset);
		else
			return mb_strpos($string, $substring);
	}
	
	public static function stripos($string, $substring, $offset ='')
	{
		if (is_int($offset))
			return mb_stripos($string, $substring, $offset);
		else
			return mb_stripos($string, $substring);
	}
	
	public static function substr($string, $start, $length = '')
	{
		if (is_int($length))
			return substr($string, $start, $length);
		else
			return substr($string, $start);
	}
	
	public static function mb_substr($string, $start, $length = '')
	{
		if (is_int($length))
			return mb_substr($string, $start, $length);
		else
			return mb_substr($string, $start);
	}
	
	public static function strrchr($string, $needle)
	{
		return mb_strrchr($string, $needle);
	}
	
	public static function strripos($string, $needle, $offset = '')
	{
		if (is_int($offset))
			return mb_strripos($string, $needle, $offset);
		else
			return mb_strripos($string, $needle);
	}
	
	public static function strrpos($string, $needle, $offset = '')
	{
		if (is_int($offset))
			return mb_strrpos($string, $needle, $offset);
		else
			return mb_strrpos($string, $needle);
	}
	
	public static function strstr($string, $needle, $before_needle = '')
	{
		if (is_int($before_needle))
			return mb_strstr($string, $needle, $before_needle);
		else
			return mb_strstr($string, $needle);
	}
	
	public static function substr_count($string, $needle, $offset = '', $length = '')
	{
		if (is_int($offset) && is_int($length))
			return mb_substr_count($string, $needle, $offset, $length);
		else if (is_int($offset))
			return mb_substr_count($string, $needle, $offset);
		else
			return mb_substr_count($string, $needle);
	}
	
	public static function convert_case($string, $mode, $encoding = '')
	{
		if ($encoding != '')
			return mb_convert_case($string, $mode, $encoding);
		else
			return mb_convert_case($string, $mode);
	}
	
	public static function serialize($string)
	{
		return serialize($string);
	}
	
	public static function unserialize($string)
	{
		return unserialize($string);
	}

	/**
	 * @desc Checks if a string contains less than a defined number of links (used to prevent SPAM).
	 * @param string $contents String in which you want to count the number of links
	 * @param int $max_nbr Maximum number of links accepted.
	 * @param bool $has_html_links true if the content is in HTML
	 * @return bool true if there are no too much links, false otherwise.
	 */
	public static function check_nbr_links($contents, $max_nbr, $has_html_links = false)
	{
		if ($max_nbr == -1)
		{
			return true;
		}

		if ($has_html_links)
		{
			$nbr_link = preg_match_all('`<a href="(?:ftp|https?)://`u', $contents, $array);
		}
		else
		{
			$nbr_link = preg_match_all('`(?:ftp|https?)://`u', $contents, $array);
		}

		if ($nbr_link !== false && $nbr_link > $max_nbr)
		{
			return false;
		}

		return true;
	}
	
	/**
	 * Wraps a UTF-8 string to a given number of characters
	 *
	 * @param string $string the input string
	 * @param int $width the column width
	 * @param string $break the line is broken using the optional break parameter
	 * @param string $cut not used for the moment
	 * @return string the given string wrapped at the specified column
	 */
	public static function utf8_wordwrap($string, $width = 75, $break = "\n", $cut = true)
	{
		$lines = array();
		while (!empty($string))
		{
			// We got a line with a break in it somewhere before the end
			if (preg_match('%^(.{1,'.$width.'})(?:\s|$)%u', $string, $matches))
			{
				// Add this line to the output
				$lines[] = $matches[1];
				// Trim it off the input ready for the next go
				$string = TextHelper::substr($string, self::strlen($matches[0]));
			}
			// Just take the next $width characters
			else
			{
				$lines[] = TextHelper::substr($string, 0, $width);
				// Trim it off the input ready for the next go
				$string = TextHelper::substr($string, $width);
			}
		}
		return implode($break, $lines);
	}
}
?>
