<?php
/**
 * @package     PHPBoost
 * @subpackage  Member\extended-fields\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2010 12 08
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MemberLongTextExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(['possible_values']);
		$this->set_name(LangLoader::get_message('user.field.type.long.text','user-lang'));
	}

	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$fieldset->add_field(new FormFieldRichTextEditor($member_extended_field->get_field_name(), $member_extended_field->get_name(), $member_extended_field->get_default_value(), [
			'required' => (bool)$member_extended_field->get_required(), 'rows' => 5, 'cols' => 47, 'description' => $member_extended_field->get_description()],
			[$this->constraint($member_extended_field->get_regex())]
		));
	}

	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$fieldset->add_field(new FormFieldRichTextEditor($member_extended_field->get_field_name(), $member_extended_field->get_name(), $member_extended_field->get_value(), [
			'required' => (bool)$member_extended_field->get_required(), 'rows' => 5, 'cols' => 47, 'description' => $member_extended_field->get_description()],
			[$this->constraint($member_extended_field->get_regex())]
		));
	}

	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		$value = FormatingHelper::second_parse($member_extended_field->get_value());
		if (!empty($value))
		{
			return ['name' => $member_extended_field->get_name(), 'field_name' => $member_extended_field->get_field_name(), 'value' => $value];
		}
	}

	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		return $form->get_value($field_name);
	}
}
?>
