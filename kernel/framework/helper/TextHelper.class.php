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
			$var = preg_replace('`&amp;((?:#[0-9]{2,5})|(?:[a-z0-9]{2,8}));`i', "&$1;", $var);
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
		$str = wordwrap(TextHelper::html_entity_decode($str), $lenght, $cut_char, $cut);
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
			return self::htmlspecialchars(substr(self::html_entity_decode($str), $start), ENT_NOQUOTES);
		}
		else
		{
			return self::htmlspecialchars(substr(self::html_entity_decode($str), $start, $end), ENT_NOQUOTES);
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
	
	public static function htmlentities($string, $flags = null, $encoding = 'UTF-8', $double_encode = true)
	{
		if ($flags === null)
		{
			$flags = ENT_COMPAT;
		}
		return htmlentities($string, $flags, $encoding, $double_encode);
	}
	
	public static function html_entity_decode($string, $flags = null, $encoding = 'UTF-8')
	{
		if ($flags === null)
		{
			$flags = ENT_COMPAT;
		}
		return html_entity_decode($string, $flags, $encoding);
	}
	
	public static function lowercase_first($string)
	{
		return lcfirst($string);
	}
	
	public static function uppercase_first($string)
	{
		return ucfirst($string);
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
			$nbr_link = preg_match_all('`<a href="(?:ftp|https?)://`', $contents, $array);
		}
		else
		{
			$nbr_link = preg_match_all('`(?:ftp|https?)://`', $contents, $array);
		}

		if ($nbr_link !== false && $nbr_link > $max_nbr)
		{
			return false;
		}

		return true;
	}
}
?>
