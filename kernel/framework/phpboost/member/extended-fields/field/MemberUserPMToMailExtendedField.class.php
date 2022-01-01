<?php
/**
 * @package     PHPBoost
 * @subpackage  Member\extended-fields\field
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 20
 * @since       PHPBoost 4.1 - 2015 09 19
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MemberUserPMToMailExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('field_required', 'regex', 'possible_values', 'default_value'));
		$this->set_name(LangLoader::get_message('user.field.type.pm.email','user-lang'));
		$this->field_used_once = true;
		$this->field_used_phpboost_config = true;
	}

	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$fieldset->add_field(new FormFieldCheckbox($member_extended_field->get_field_name(), $member_extended_field->get_name(), false,
			array('description' => $member_extended_field->get_description(), 'required' => (bool)$member_extended_field->get_required())
		));
	}

	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$fieldset->add_field(new FormFieldCheckbox($member_extended_field->get_field_name(), $member_extended_field->get_name(), $member_extended_field->get_value(),
			array('description' => $member_extended_field->get_description(), 'required' => (bool)$member_extended_field->get_required())
		));
	}

	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		//The field is not displayed in the member profile
		return false;
	}

	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		if ($form->has_field($field_name))
			return (int)$form->get_value($field_name);

		return '';
	}
}
?>
