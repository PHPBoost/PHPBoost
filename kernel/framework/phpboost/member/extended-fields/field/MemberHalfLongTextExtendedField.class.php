<?php
/**
 * @package     PHPBoost
 * @subpackage  Member\extended-fields\field
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 20
 * @since       PHPBoost 3.0 - 2010 12 08
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MemberHalfLongTextExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('possible_values'));
		$this->set_name(LangLoader::get_message('user.field.type.half.text','user-lang'));
	}

	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$fieldset->add_field(new FormFieldShortMultiLineTextEditor($member_extended_field->get_field_name(), $member_extended_field->get_name(), $member_extended_field->get_default_value(), array(
			'required' => (bool)$member_extended_field->get_required(), 'rows' => 4, 'cols' => 47, 'description' => $member_extended_field->get_description()),
			array($this->constraint($member_extended_field->get_regex()))
		));
	}

	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$fieldset->add_field(new FormFieldShortMultiLineTextEditor($member_extended_field->get_field_name(), $member_extended_field->get_name(), $member_extended_field->get_value(), array(
			'required' => (bool)$member_extended_field->get_required(), 'rows' => 4, 'cols' => 47, 'description' => $member_extended_field->get_description()),
			array($this->constraint($member_extended_field->get_regex()))
		));
	}
}
?>
