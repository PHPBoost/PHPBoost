<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 20
 * @since       PHPBoost 4.0 - 2013 07 31
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ContactShortTextField extends AbstractContactField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('possible_values', 'default_value_medium'));
		$this->set_name(LangLoader::get_message('user.field.type.short.text', 'user-lang'));
	}

	public function display_field(ContactField $field)
	{
		$fieldset = $field->get_fieldset();
		$regex = $field->get_regex();

		switch ($regex)
		{
			case 1:
				$field_class = 'FormFieldNumberEditor';
				$display_constraint = false;
				break;
			case 4:
				$field_class = 'FormFieldMailEditor';
				$display_constraint = false;
				break;
			case 5:
				$field_class = 'FormFieldUrlEditor';
				$display_constraint = false;
				break;
			case 8:
				$field_class = 'FormFieldTelEditor';
				$display_constraint = false;
				break;
			default:
				$field_class = 'FormFieldTextEditor';
				$display_constraint = true;
				break;
		}

		$fieldset->add_field(new $field_class($field->get_field_name(), $field->get_name(), $field->get_default_value(), array(
			'required' => (bool)$field->is_required(), 'description' => $field->get_description()),
			($display_constraint ? array($this->constraint($regex)) : array())
		));
	}
}
?>
