<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 25
 * @since       PHPBoost 5.2 - 2019 07 30
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldConstraintForbiddenMailDomains extends FormFieldConstraintRegex
{
	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('warning.email.authorized.domains.regex', 'warning-lang');
		}
		$this->set_validation_error_message($error_message);

		$forbidden_mail_domains = SecurityConfig::load()->get_forbidden_mail_domains();

		$regex = $forbidden_mail_domains ? '/^((?!' . implode('|', $forbidden_mail_domains) . ').)*$/i' : '//';

		parent::__construct(
			$regex,
			$regex,
			$error_message
		);
	}
}

?>
