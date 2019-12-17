<?php
/**
 * @package     PHPBoost
 * @subpackage  Member\extended-fields\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 07 06
 * @since       PHPBoost 3.0 - 2010 12 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class MemberMultipleSelectExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('regex', 'default_value'));
		$this->set_name(LangLoader::get_message('type.multiple-select','admin-user-common'));
	}

	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$options = array();
		$default_values = array();
		foreach ($member_extended_field->get_possible_values() as $name => $parameters)
		{
			$option = new FormFieldSelectChoiceOption(stripslashes($parameters['title']), $name);
			$options[] = $option;

			if ($parameters['is_default'])
			{
				$default_values[] = $option;
			}
		}

		$fieldset->add_field(new FormFieldMultipleSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), $default_values, $options, array('required' => (bool)$member_extended_field->get_required(), 'description' => $member_extended_field->get_description())));
	}

	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$options = array();
		$default_values = $this->unserialise_value($member_extended_field->get_value());
		foreach ($member_extended_field->get_possible_values() as $name => $parameters)
		{
			$options[] = new FormFieldSelectChoiceOption(stripslashes($parameters['title']), $name);
		}

		$fieldset->add_field(new FormFieldMultipleSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), $default_values, $options, array('required' => (bool)$member_extended_field->get_required(), 'description' => $member_extended_field->get_description())));
	}

	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		$value = implode(', ', $this->unserialise_value($member_extended_field->get_value()));
		if (!empty($value))
		{
			return array('name' => $member_extended_field->get_name(), 'value' => $value);
		}
	}

	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		$array = array();
		if ($form->has_field($field_name))
		{
			foreach ($form->get_value($field_name, array()) as $field => $value)
			{
				$array[] = $value->get_label();
			}
		}
		return $this->serialise_value($array);
	}

	private function serialise_value(Array $array)
	{
		return implode('|', $array);
	}

	private function unserialise_value($string)
	{
		return explode('|', $string);
	}
}
?>
