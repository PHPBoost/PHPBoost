<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 22
 * @since       PHPBoost 3.0 - 2011 03 09
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AbstractFormFieldConstraint implements FormFieldConstraint
{
	private string $validation_error_message = '';

	public function validate(FormField $field): mixed
    {
        return null;
    }

	public function get_js_validation(FormField $field): mixed
    {
        return null;
    }

	public function get_validation_error_message(): string
	{
		return $this->validation_error_message;
	}

	public function set_validation_error_message(mixed $error_message): void
	{
		$this->validation_error_message = $error_message;
	}
}

?>
