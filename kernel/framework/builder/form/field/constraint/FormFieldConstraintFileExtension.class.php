<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 25
 * @since       PHPBoost 4.1 - 2018 11 17
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldConstraintFileExtension extends FormFieldConstraintRegex
{
	public function __construct($extensions, $error_message = '')
	{
		if (is_array($extensions))
			$extensions = implode('|', $extensions);

		if (empty($error_message))
		{
			$error_message = StringVars::replace_vars(LangLoader::get_message('warning.regex.authorized.extensions', 'warning-lang'), array('extensions' => str_replace('|', ', ', $extensions)));
		}
		$this->set_validation_error_message($error_message);

		$regex = '/\.(' . $extensions . ')$/iu';

		parent::__construct(
			$regex,
			$regex,
			$error_message
		);
	}
}

?>
