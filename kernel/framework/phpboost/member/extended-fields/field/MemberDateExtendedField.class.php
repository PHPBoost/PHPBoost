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

class MemberDateExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('regex', 'possible_values', 'default_value'));
		$this->set_name(LangLoader::get_message('type.date','admin-user-common'));
	}

	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$value = $member_extended_field->get_default_value() ? new Date($member_extended_field->get_default_value()) : null;
		$fieldset->add_field(new FormFieldDate($member_extended_field->get_field_name(), $member_extended_field->get_name(), $value,
			array('description' => $member_extended_field->get_description(), 'required' =>(bool)$member_extended_field->get_required())
		));
	}

	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$value = $member_extended_field->get_value() ? new Date($member_extended_field->get_value()) : null;
		$fieldset->add_field(new FormFieldDate($member_extended_field->get_field_name(), $member_extended_field->get_name(), $value,
			array('description' => $member_extended_field->get_description(), 'required' =>(bool)$member_extended_field->get_required())
		));
	}

	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		if ($member_extended_field->get_value())
		{
			$date = new Date($member_extended_field->get_value());
			return array('name' => $member_extended_field->get_name(), 'value' => $date->format(Date::FORMAT_DAY_MONTH_YEAR));
		}
	}

	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		if ($form->has_field($field_name))
			return $form->get_value($field_name)->format(Date::FORMAT_TIMESTAMP);

		return '';
	}
}
?>
