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

class MemberSimpleSelectExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(['regex', 'default_value']);
		$this->set_name(LangLoader::get_message('user.field.type.simple.select','user-lang'));
	}

	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$options = [];
		$default = '';
		foreach ($member_extended_field->get_possible_values() as $name => $parameters)
		{
			$options[] = new FormFieldSelectChoiceOption(stripslashes($parameters['title']), $name);
			if ($parameters['is_default'])
			{
				$default = $name;
			}
		}

		if (empty($default))
		{
			$options = array_merge([new FormFieldSelectChoiceOption('', '')], $options);
			$default = '';
		}

		$fieldset->add_field(new FormFieldSimpleSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), $default, $options, ['required' => (bool)$member_extended_field->get_required(), 'description' => $member_extended_field->get_description()]));
	}

	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$options = [];
		$default = $member_extended_field->get_value();
		foreach ($member_extended_field->get_possible_values() as $name => $parameters)
		{
			$option = new FormFieldSelectChoiceOption(stripslashes($parameters['title']), $name);
			$option->set_active($default == stripslashes($parameters['title']));
			$options[] = $option;
		}

		if (empty($default))
		{
			$options = array_merge([new FormFieldSelectChoiceOption('', '')], $options);
			$default = '';
		}

		$fieldset->add_field(new FormFieldSimpleSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), $default, $options, ['required' => (bool)$member_extended_field->get_required(), 'description' => $member_extended_field->get_description()]));
	}

	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();

		if ($form->has_field($field_name))
		{
			$value = $form->get_value($field_name);
			if (!empty($value))
				return $value->get_label();
		}

		return '';
	}
}
?>
