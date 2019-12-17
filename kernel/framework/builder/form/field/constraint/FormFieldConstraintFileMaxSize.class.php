<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 04 04
 * @since       PHPBoost 5.3 - 2019 04 04
*/

class FormFieldConstraintFileMaxSize extends AbstractFormFieldConstraint
{
	private $max_size;
	
	public function __construct($max_size, $error_message = '')
	{
		$this->error_message = $error_message;
		if (!$this->error_message)
		{
			$this->error_message = StringVars::replace_vars(LangLoader::get_message('upload.max_file_size_exceeded', 'status-messages-common'), array('max_file_size' => File::get_formated_size($max_size)));
		}
		$this->set_validation_error_message($this->error_message);

		$this->max_size = $max_size;
	}

	public function validate(FormField $field)
	{
		$post_file = isset($_FILES[$field->get_id()]) ? $_FILES[$field->get_id()] : '';
		$value = is_array($post_file) ? $post_file['size'] : 0;
		if (!is_numeric($value))
		{
			return false;
		}
		$value = (int)$value;
		if (!empty($value))
		{
			return ($value <= $this->max_size);
		}
		return true;
	}

	public function get_js_validation(FormField $field)
	{
		return 'maxSizeFilePickerFormFieldValidator(' . TextHelper::to_js_string($field->get_id()) . ',
		' . (int)$this->max_size . ', ' . TextHelper::to_js_string($this->error_message) . ')';
	}
}

?>
