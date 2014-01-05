<?php
/*##################################################
 *                             RegexHelper.class.php
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
 * @desc Regex helper
 * @author Régis Viarre <crowkait@phpboost.com>
 * @package {@package}
 */
class RegexHelper 
{
	const REGEX_MULTIPLICITY_NOT_USED = 0x01;
	const REGEX_MULTIPLICITY_OPTIONNAL = 0x02;
	const REGEX_MULTIPLICITY_REQUIRED = 0x03;
	const REGEX_MULTIPLICITY_AT_LEAST_ONE = 0x04;
	const REGEX_MULTIPLICITY_ALL = 0x05;
		
	/**
	 * @desc Returns the sub-regex with its multiplicity option
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