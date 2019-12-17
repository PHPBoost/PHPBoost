<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 03 09
*/

class AbstractFormFieldConstraint implements FormFieldConstraint
{
	private $validation_error_message = '';

	public function validate(FormField $field) {}

	public function get_js_validation(FormField $field) {}

	public function get_validation_error_message()
	{
		return $this->validation_error_message;
	}

	public function set_validation_error_message($error_message)
	{
		$this->validation_error_message = $error_message;
	}
}

?>
