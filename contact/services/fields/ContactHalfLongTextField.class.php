<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 07 31
*/

class ContactHalfLongTextField extends AbstractContactField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('possible_values', 'default_value_small'));
		$this->set_name(LangLoader::get_message('type.half-text', 'admin-user-common'));
	}

	public function display_field(ContactField $field)
	{
		$fieldset = $field->get_fieldset();

		$fieldset->add_field(new FormFieldShortMultiLineTextEditor($field->get_field_name(), $field->get_name(), $field->get_default_value(), array(
			'required' => (bool)$field->is_required(), 'rows' => 10, 'cols' => 80, 'description' => $field->get_description()),
			array($this->constraint($field->get_regex()))
		));
	}
}
?>
