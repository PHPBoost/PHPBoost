<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 15
 * @since       PHPBoost 4.1 - 2015 06 01
 * @contributor mipel <mipel@phpboost.com>
*/

class FormFieldConstraintTel extends FormFieldConstraintRegex
{
	private static $regex = '/^(\+[0-9]+( |-)?|0)?[0-9]( |-)?([0-9]{2}( |-)?){4}$/u';
	private static $js_regex = '^(\\\+[0-9]+( |-)?|0)?[0-9]( |-)?([0-9]{2}( |-)?){4}$';

	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('form.doesnt_match_tel_regex', 'status-messages-common');
		}
		$this->set_validation_error_message($error_message);

		parent::__construct(
			self::$regex,
			TextHelper::to_js_string(self::$js_regex),
			$error_message
		);
	}
}

?>
