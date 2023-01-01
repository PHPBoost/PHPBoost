<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 03
 * @since       PHPBoost 4.0 - 2013 08 04
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ContactConstraintFieldExist extends AbstractFormFieldConstraint
{
	private $field_id = 0;
	private $error_message;

	public function __construct($field_id = 0, $error_message = '')
	{
		if (!empty($field_id))
		{
			$this->field_id = $field_id;
		}

		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('contact.message.field.name.already.used', 'common', 'contact');
		}
		$this->set_validation_error_message($error_message);
		$this->error_message = TextHelper::to_js_string($error_message);
	}

	public function validate(FormField $field)
	{
		$field_name = ContactField::rewrite_field_name($field->get_value());

		$valid = true;

		if (!empty($this->field_id))
		{
			foreach (ContactConfig::load()->get_fields() as $id => $f)
			{
				if ($id != $this->field_id && $f['field_name'] == $field_name)
					$valid = false;
			}
		}
		else
		{
			foreach (ContactConfig::load()->get_fields() as $id => $f)
			{
				if ($f['field_name'] == $field_name)
					$valid = false;
			}
		}

		return $valid;
	}

	public function get_js_validation(FormField $field)
	{
		return 'ContactFieldExistValidator(' . $this->error_message . ', ' . $this->field_id . ')';
	}
}
?>
