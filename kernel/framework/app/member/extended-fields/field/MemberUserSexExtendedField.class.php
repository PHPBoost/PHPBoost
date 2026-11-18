<?php
/**
 * @package     PHPBoost
 * @subpackage  Member\extended-fields\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2010 12 26
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MemberUserSexExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(['regex', 'possible_values', 'default_value']);
		$this->set_name(LangLoader::get_message('user.field.type.sex','user-lang'));
		$this->field_used_once = true;
		$this->field_used_phpboost_config = true;
	}

	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$fieldset->add_field(new FormFieldSimpleSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), '0',
		[
			new FormFieldSelectChoiceOption('--', ''),
			new FormFieldSelectChoiceOption(LangLoader::get_message('user.male', 'user-lang'), '1'),
			new FormFieldSelectChoiceOption(LangLoader::get_message('user.female', 'user-lang'), '2'),
		],
			['description' => $member_extended_field->get_description(), 'required' =>(bool)$member_extended_field->get_required()]
		));
	}

	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$fieldset->add_field(new FormFieldSimpleSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), $member_extended_field->get_value(),
		[
			new FormFieldSelectChoiceOption('--', ''),
			new FormFieldSelectChoiceOption(LangLoader::get_message('user.male', 'user-lang'), '1'),
			new FormFieldSelectChoiceOption(LangLoader::get_message('user.female', 'user-lang'), '2'),
		],
			['description' => $member_extended_field->get_description(), 'required' =>(bool)$member_extended_field->get_required()]
		));
	}

	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		if ($member_extended_field->get_value())
		{
			return ['name' => $member_extended_field->get_name(), 'field_name' => $member_extended_field->get_field_name(), 'value' => $this->get_picture_sex($member_extended_field->get_value())];
		}
	}

	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		if ($form->has_field($field_name))
			return $form->get_value($field_name)->get_raw_value();

		return '';
	}

	private function get_picture_sex($value)
	{
		switch ($value)
		{
			case 1:
				return '<i class="fa fa-male"></i>';
				break;
			case 2:
				return '<i class="fa fa-female"></i>';
				break;
			default:
				return '';
		}
	}
}
?>
