<?php
/**
 * This class is used to decode HTML entities emojis.
 * @package     Content
 * @subpackage  Formatting\parser
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 06
 * @since       PHPBoost 6.0 - 2022 03 06
*/

class HTMLEmojisDecoder
{
	public static function decode_html_emojis( $string ) {
		$stringBuilder = "";
		$offset = 0;

		if ( empty( $string ) ) {
			return "";
		}

		while ( $offset >= 0 ) {
			$decValue = self::ordutf8( $string, $offset );
			$char = self::unichr($decValue);

			$htmlEntited = htmlentities( $char );
			if( $char != $htmlEntited ){
				$stringBuilder .= $htmlEntited;
			} elseif( $decValue >= 128 ){
				$stringBuilder .= "&#" . $decValue;
			} else {
				$stringBuilder .= $char;
			}
		}

		return $stringBuilder;
	}

	public static function ordutf8($string, &$offset) {
		$code = ord(substr($string, $offset,1));
		if ($code >= 128) {
			if ($code < 224) $bytesnumber = 2;
			else if ($code < 240) $bytesnumber = 3;
			else if ($code < 248) $bytesnumber = 4;
			$codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) - ($bytesnumber > 3 ? 16 : 0);
			for ($i = 2; $i <= $bytesnumber; $i++) {
				$offset ++;
				$code2 = ord(substr($string, $offset, 1)) - 128; 
				$codetemp = $codetemp*64 + $code2;
			}
			$code = $codetemp;
		}
		$offset += 1;
		if ($offset >= strlen($string)) $offset = -1;
		return $code;
	}

	public static function unichr($u) {
		return mb_convert_encoding('&#' . intval($u) . ';', 'UTF-8', 'HTML-ENTITIES');
	}
}
?>
