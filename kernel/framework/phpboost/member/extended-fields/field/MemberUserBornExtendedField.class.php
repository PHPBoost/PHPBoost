<?php
/**
 * @package     PHPBoost
 * @subpackage  Member\extended-fields\field
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 20
 * @since       PHPBoost 3.0 - 2010 12 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MemberUserBornExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('regex', 'possible_values', 'default_value'));
		$this->set_name(LangLoader::get_message('user.field.type.born','user-lang'));
		$this->field_used_once = true;
		$this->field_used_phpboost_config = true;
	}

	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$fieldset->add_field(new FormFieldDate($member_extended_field->get_field_name(), $member_extended_field->get_name(), null,
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
			return array('name' => $member_extended_field->get_name(), 'field_name' => $member_extended_field->get_field_name(), 'value' => $date->format(Date::FORMAT_DAY_MONTH_YEAR));
		}
	}

	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		if ($form->has_field($field_name))
		{
			$date = $form->get_value($field_name);

			if (!empty($date))
				return $date->format(Date::FORMAT_TIMESTAMP);

			return '';
		}
		return '';
	}
}
?>
