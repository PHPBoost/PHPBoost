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

class MemberLongTextExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('possible_values'));
		$this->set_name(LangLoader::get_message('type.long-text','admin-user-common'));
	}

	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$fieldset->add_field(new FormFieldRichTextEditor($member_extended_field->get_field_name(), $member_extended_field->get_name(), $member_extended_field->get_default_value(), array(
			'required' => (bool)$member_extended_field->get_required(), 'rows' => 5, 'cols' => 47, 'description' => $member_extended_field->get_description()),
			array($this->constraint($member_extended_field->get_regex()))
		));
	}

	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$fieldset->add_field(new FormFieldRichTextEditor($member_extended_field->get_field_name(), $member_extended_field->get_name(), $member_extended_field->get_value(), array(
			'required' => (bool)$member_extended_field->get_required(), 'rows' => 5, 'cols' => 47, 'description' => $member_extended_field->get_description()),
			array($this->constraint($member_extended_field->get_regex()))
		));
	}

	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		$value = FormatingHelper::second_parse($member_extended_field->get_value());
		if (!empty($value))
		{
			return array('name' => $member_extended_field->get_name(), 'value' => $value);
		}
	}

	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		return $form->get_value($field_name);
	}
}
?>
