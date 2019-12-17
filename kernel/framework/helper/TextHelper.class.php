<?php
/**
 * Text helper
 * @package     Helper
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 09
 * @since       PHPBoost 3.0 - 2010 01 24
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class TextHelper
{
	const HTML_NO_PROTECT = false;
	const HTML_PROTECT = true;
	const ADDSLASHES_FORCE = 1;
	const ADDSLASHES_NONE = 2;

	/**
	 * Protects an input variable. Never trust user input!
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
	 * Inserts a carriage return every $lenght characters. It's equivalent to wordwrap PHP function but it can deal with the HTML entities.
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
	 * Cuts a string containing some HTML code which contains some HTML entities. The substr PHP function considers a HTML entity as several characters.
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
			return self::htmlspecialchars(self::substr(self::html_entity_decode($str), $start), ENT_NOQUOTES);
		else
			return self::htmlspecialchars(self::substr(self::html_entity_decode($str), $start, $end), ENT_NOQUOTES);
	}

	/**
	 * Cut string to the desired length, ending with the last full word.
	 * @param string $string A PHP string to convert to cut
	 * @param int $length The desired length
	 * @return string The substring
	 */
	public static function cut_string($string, $length)
	{
		if (strlen($string) <= $length)
			return $string;

		$str = mb_substr(str_replace('<br />', '<br/>', $string), 0, $length + 1, 'UTF-8');
		return substr($str, 0, strrpos($str, ' ')) . '...';
	}

	/**
	 * Exports a variable to be used in a javascript script.
	 * @param string $string A PHP string to convert to a JS one
	 * @param string $add_quotes If true, returned string will be bounded by single quotes
	 * @return string The js equivalent string
	 */
	public static function to_js_string($string, $add_quotes = true)
	{
		$bounds = $add_quotes ? '\'' : '';
		return $bounds . str_replace(array("\r\n", "\r", "\n", '"'), array('\n', '\n', '\n', '&quot;'), addcslashes($string, '\'')) . $bounds;
	}

	/**
	 * Exports a variable to be used in a json javascript script.
	 * @param string $string A PHP string to convert to a json one
	 * @param string $add_quotes If true, returned string will be bounded by double quotes
	 * @return string The json equivalent string
	 */
	public static function to_json_string($string, $add_quotes = true)
	{
		$bounds = $add_quotes ? '"' : '';
		return $bounds . str_replace(array("\r\n", "\r", "\n",), array('\n', '\n', '\n',), addcslashes($string, '"')) . $bounds;
	}

	public static function htmlspecialchars($string, $flags = null, $encoding = 'UTF-8', $double_encode = true)
	{
		if ($flags === null)
			$flags = ENT_COMPAT;

		return str_replace('&amp;', '&', htmlspecialchars($string, $flags, $encoding, $double_encode));
	}

	public static function htmlspecialchars_decode($string, $flags = null)
	{
		if ($flags === null)
			$flags = ENT_COMPAT;

		return htmlspecialchars_decode($string, $flags);
	}

	public static function html_entity_decode($string, $flags = null, $encoding = 'UTF-8')
	{
		if ($flags === null)
			$flags = ENT_COMPAT;

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
		$fc = mb_strtolower(mb_substr($string, 0, 1));
		return $fc . mb_substr($string, 1);
	}

	public static function ucfirst($string)
	{
		$fc = mb_strtoupper(mb_substr($string, 0, 1));
		return $fc . mb_substr($string, 1);
	}

	public static function strlen($string)
	{
		return strlen($string);
	}

	public static function strpos($string, $substring, $offset = '')
	{
		if (!empty($substring))
		{
			if (is_int($offset))
				return mb_strpos($string, $substring, $offset);
			else
				return mb_strpos($string, $substring);
		}
		return false;
	}

	public static function stripos($string, $substring, $offset = '')
	{
		if (!empty($substring))
		{
			if (is_int($offset))
				return mb_stripos($string, $substring, $offset);
			else
				return mb_stripos($string, $substring);
		}
		return false;
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

	public static function substr_count($string, $needle, $encoding = '')
	{
		if ($encoding != '')
			return mb_substr_count($string, $needle, $encoding);
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

	public static function serialize_base64($string)
	{
		return base64_encode(self::serialize($string));
	}

	public static function unserialize($string)
	{
		return unserialize(self:: is_base64($string) ? base64_decode($string) : $string);
	}

	public static function mb_unserialize($string)
	{
		$string = preg_replace_callback('!s:(\d+):"(.*?)";!s', function ($matches) {
			if (isset($matches[2])) return 's:' . strlen($matches[2]) . ':"' . $matches[2] . '";';
		}, $string
		);
		return unserialize($string);
	}

	private static function is_base64($string)
	{
		$decoded = base64_decode($string, true);
		return preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string) && false !== $decoded && base64_encode($decoded) == $string;
	}

	/**
	 * Checks if a string contains less than a defined number of links (used to prevent SPAM).
	 * @param string $contents String in which you want to count the number of links
	 * @param int $max_nbr Maximum number of links accepted.
	 * @param bool $has_html_links true if the content is in HTML
	 * @return bool true if there are no too much links, false otherwise.
	 */
	public static function check_nbr_links($contents, $max_nbr, $has_html_links = false)
	{
		if ($max_nbr == -1)
			return true;

		if ($has_html_links)
			$nbr_link = preg_match_all('`<a href="(?:ftp|https?)://`u', $contents, $array);
		else
			$nbr_link = preg_match_all('`(?:ftp|https?)://`u', $contents, $array);

		if ($nbr_link !== false && $nbr_link > $max_nbr)
			return false;

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
			if (preg_match('%^(.{1,' . $width . '})(?:\s|$)%u', $string, $matches))
			{
				// Add this line to the output
				$lines[] = $matches[1];
				$string = self::substr($string, self::strlen($matches[0]));
			}
			// Just take the next $width characters
			else
			{
				$lines[] = self::substr($string, 0, $width);
				$string = self::substr($string, $width);
			}
		}
		return implode($break, $lines);
	}
}

?>
