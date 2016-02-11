<?php
/*##################################################
 *                         FormFieldConstraintPasswordStrength.class.php
 *                            -------------------
 *   begin                : August 5, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 * @desc
 * @package {@package}
 */
class FormFieldConstraintPasswordStrength extends FormFieldConstraintRegex
{
	// Must be at least 6 characters
	private static $weak_strength_regex = '/^(?=.{6,}).*$/';
	// Must containt at least upper case letters and lower case letters or soit lower case letters and digits
	private static $medium_strength_regex = '/^(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$/';
	// Must containt at least upper case letters, lower case letters and digits
	private static $strong_strength_regex = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\*]).*$/';
	
	public function __construct($error_message = '')
	{
		switch (SecurityConfig::load()->get_internal_password_strength())
		{
			case SecurityConfig::PASSWORD_STRENGTH_STRONG :
				$regex = self::$strong_strength_regex;
				$error_message = empty($error_message) ? LangLoader::get_message('form.doesnt_match_strong_password_regex', 'status-messages-common') : $error_message;
				break;
			
			case SecurityConfig::PASSWORD_STRENGTH_MEDIUM :
				$regex = self::$medium_strength_regex;
				$error_message = empty($error_message) ? LangLoader::get_message('form.doesnt_match_medium_password_regex', 'status-messages-common') : $error_message;
				break;
			
			default :
				$regex = self::$weak_strength_regex;
				break;
		}
		
		$this->set_validation_error_message($error_message);
		
		parent::__construct(
			$regex, 
			$regex, 
			$error_message
		);
	}

	public function get_password_medium_strength_regex()
	{
		return self::$medium_strength_regex;
	}

	public function get_password_strong_strength_regex()
	{
		return self::$strong_strength_regex;
	}
}

?>