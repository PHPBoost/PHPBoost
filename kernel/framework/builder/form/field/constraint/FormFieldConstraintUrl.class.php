<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 03
 * @since       PHPBoost 3.0 - 2010 02 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldConstraintUrl extends FormFieldConstraintRegex
{
	private static $regex = '/(^\/[0-9A-Za-z#!:.?+=&%@!\-\/\._]+$)|(^(ftps?|https?):\/\/([0-9a-z\.-]+)\.{0,1}([a-z\.]{2,6})([0-9A-Za-z#!:.?+=&%@!\-\/\._]*)*\/?$)/u';

	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('warning.regex.url', 'warning-lang');
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
