<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 20
 * @since       PHPBoost 4.0 - 2013 07 31
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ContactDateField extends AbstractContactField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('regex', 'possible_values', 'default_value_small', 'default_value_medium'));
		$this->set_name(LangLoader::get_message('user.field.type.date', 'user-lang'));
	}

	public function display_field(ContactField $field)
	{
		$fieldset = $field->get_fieldset();

		$fieldset->add_field(new FormFieldDate($field->get_field_name(), $field->get_name(), null,
			array('description' => $field->get_description(), 'required' =>(bool)$field->is_required())
		));
	}

	public function get_value(HTMLForm $form, ContactField $field)
	{
		$field_name = $field->get_field_name();
		if ($form->has_field($field_name))
			return $form->get_value($field_name) ? $form->get_value($field_name)->format(Date::FORMAT_DAY_MONTH_YEAR) : '';

		return '';
	}
}
?>
