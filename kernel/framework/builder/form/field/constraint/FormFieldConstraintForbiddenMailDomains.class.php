<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2019 10 25
 * @since       PHPBoost 5.2 - 2019 07 30
*/

class FormFieldConstraintForbiddenMailDomains extends FormFieldConstraintRegex
{
	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('form.doesnt_match_mail_authorized_domains_regex', 'status-messages-common');
		}
		$this->set_validation_error_message($error_message);
		
		$regex = '/^((?!' . implode('|', SecurityConfig::load()->get_forbidden_mail_domains()) . ').)*$/iu';

		parent::__construct(
			$regex,
			$regex,
			$error_message
		);
	}
}

?>
