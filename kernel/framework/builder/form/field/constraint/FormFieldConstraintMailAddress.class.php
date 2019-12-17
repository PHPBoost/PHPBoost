<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2010 02 07
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldConstraintMailAddress extends FormFieldConstraintRegex
{
	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('form.doesnt_match_mail_regex', 'status-messages-common');
		}
		$this->set_validation_error_message($error_message);
		$mail_service = AppContext::get_mail_service();
		$regex = $mail_service->get_mail_checking_regex();

		parent::__construct(
			$regex,
			$regex,
			$error_message
		);
	}
}

?>
