<?php
/**
 * Regex helper
 * @package     Helper
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2010 01 21
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class RegexHelper
{
	const REGEX_MULTIPLICITY_NOT_USED = 0x01;
	const REGEX_MULTIPLICITY_OPTIONNAL = 0x02;
	const REGEX_MULTIPLICITY_REQUIRED = 0x03;
	const REGEX_MULTIPLICITY_AT_LEAST_ONE = 0x04;
	const REGEX_MULTIPLICITY_ALL = 0x05;

	/**
	 * Returns the sub-regex with its multiplicity option
	 * @param string $sub_regex the sub-regex on which add the multiplicity
	 * @param int $occurence REGEX_MULTIPLICITY_OPTION
	 * @return string the subregex with its multiplicity option
	 * @see RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL
	 * @see REGEX_MULTIPLICITY_NEEDED
	 * @see RegexHelper::REGEX_MULTIPLICITY_AT_LEAST_ONE
	 * @see RegexHelper::REGEX_MULTIPLICITY_ALL
	 * @see RegexHelper::REGEX_MULTIPLICITY_NOT_USED
	 */
	public static function set_subregex_multiplicity($sub_regex, $multiplicity_option)
	{
		switch ($multiplicity_option)
		{
			case self::REGEX_MULTIPLICITY_OPTIONNAL:
				// Optionnal
				return '(?:' . $sub_regex . ')?';
			case self::REGEX_MULTIPLICITY_REQUIRED:
				// Required
				return $sub_regex;
			case self::REGEX_MULTIPLICITY_AT_LEAST_ONE:
				// Optionnal
				return '(?:' . $sub_regex . ')+';
			case self::REGEX_MULTIPLICITY_ALL:
				// Optionnal
				return '(?:' . $sub_regex . ')*';
			case self::REGEX_MULTIPLICITY_NOT_USED:
			default:
				// Not present
				return '';
		}
	}
}
?>
