<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 15
 * @since       PHPBoost 4.1 - 2015 08 27
 * @contributor mipel <mipel@phpboost.com>
*/

class FormFieldConstraintDate extends FormFieldConstraintRegex
{
	private static $regex = '`^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$`u';

	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('form.doesnt_match_date_regex', 'status-messages-common');
		}
		$this->set_validation_error_message($error_message);

		parent::__construct(
			self::$regex,
			self::$regex,
			$error_message
		);
	}

	public function get_url_checking_regex()
	{
		return self::$regex;
	}
}

?>
