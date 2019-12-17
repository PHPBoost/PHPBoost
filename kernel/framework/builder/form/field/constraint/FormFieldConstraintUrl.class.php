<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 15
 * @since       PHPBoost 3.0 - 2010 02 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldConstraintUrl extends FormFieldConstraintRegex
{
	private static $regex = '`^((https?|ftp)://[^ ]+)|(/[^ ]+)$`u';

	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('form.doesnt_match_url_regex', 'status-messages-common');
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
