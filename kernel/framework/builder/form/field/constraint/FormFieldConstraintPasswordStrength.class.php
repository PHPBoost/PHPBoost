<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 15
 * @since       PHPBoost 4.1 - 2015 08 05
 * @contributor mipel <mipel@phpboost.com>
*/

class FormFieldConstraintPasswordStrength extends FormFieldConstraintRegex
{
	// Must be at least 6 characters
	private static $weak_strength_regex = '/^(?=.{6,}).*$/u';
	// Must containt at least upper case letters and lower case letters or lower case letters and digits
	private static $medium_strength_regex = '/^(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$/u';
	// Must containt at least upper case letters, lower case letters and digits
	private static $strong_strength_regex = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).*$/u';
	// Must containt at least upper case letters, lower case letters, digits and special characters
	private static $very_strong_strength_regex = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\*]).*$/u';

	public function __construct($error_message = '')
	{
		switch (SecurityConfig::load()->get_internal_password_strength())
		{
			case SecurityConfig::PASSWORD_STRENGTH_VERY_STRONG :
				$regex = self::$very_strong_strength_regex;
				$error_message = empty($error_message) ? LangLoader::get_message('form.doesnt_match_very_strong_password_regex', 'status-messages-common') : $error_message;
				break;

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

	public function get_password_very_strong_strength_regex()
	{
		return self::$very_strong_strength_regex;
	}
}

?>
