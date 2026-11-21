<?php
/**
 * Abstract class that proposes a default implementation for the MemberExtendedFieldType interface.
 * @package     PHPBoost
 * @subpackage  Member\extended-fields\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 21
 * @since       PHPBoost 3.0 - 2010 12 08
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractMemberExtendedField implements MemberExtendedFieldType
{
	protected array $lang;
	protected HTMLForm $form;
	protected bool $field_used_once;
	protected bool $field_used_phpboost_config;
	protected array $disable_fields_configuration = [];
	protected string $name;

	public function __construct()
	{
		$this->lang = LangLoader::get_all_langs();
		$this->field_used_once = false;
		$this->field_used_phpboost_config = false;
		$this->name = 'ExtendedField';
	}

	public function display_field_create(MemberExtendedField $member_extended_field): void
	{
		$fieldset = $member_extended_field->get_fieldset();
		return;
	}

	public function display_field_update(MemberExtendedField $member_extended_field): void
	{
		$fieldset = $member_extended_field->get_fieldset();
		return;
	}

	public function delete_field(MemberExtendedField $member_extended_field): void
	{
		return;
	}

	public function display_field_profile(MemberExtendedField $member_extended_field): mixed
	{
		if ($member_extended_field->get_value())
		{
			return ['name' => $member_extended_field->get_name(), 'field_name' => $member_extended_field->get_field_name(), 'value' => $member_extended_field->get_value()];
		}
        return null;
	}

	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field): mixed
	{
		$field_name = $member_extended_field->get_field_name();
		return TextHelper::htmlspecialchars($form->get_value($field_name, ''));
	}

	public function constraint(mixed $value): ?FormFieldConstraintRegex
	{
		if (is_numeric($value))
		{
			switch ($value)
			{
				case 2:
					return new FormFieldConstraintRegex('`^[a-zA-Z]+$`iu');
				case 3:
					return new FormFieldConstraintRegex('`^[a-zA-Z0-9]+$`iu');
				case 7:
					return new FormFieldConstraintRegex('`^[a-zA-Zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ-]+$`iu');
			}
		}
		elseif (is_string($value) && !empty($value))
		{
			return new FormFieldConstraintRegex($value);
		}
        return null;
	}

	public function set_disable_fields_configuration(array $names): void
	{
		foreach($names as $name)
		{
			$name = TextHelper::strtolower($name);
			switch ($name)
			{
				case 'name':
					$this->disable_fields_configuration[] = $name;
					break;
				case 'description':
					$this->disable_fields_configuration[] = $name;
					break;
				case 'field_type':
					$this->disable_fields_configuration[] = $name;
					break;
				case 'field_required':
					$this->disable_fields_configuration[] = $name;
					break;
				case 'regex':
					$this->disable_fields_configuration[] = $name;
					break;
				case 'possible_values':
					$this->disable_fields_configuration[] = $name;
					break;
				case 'default_value':
					$this->disable_fields_configuration[] = $name;
					break;
				case 'authorizations':
					$this->disable_fields_configuration[] = $name;
					break;
				default :
					throw new Exception('Field name ' . $name . ' not exist');
			}
		}
	}

	public function get_disable_fields_configuration(): array
	{
		return $this->disable_fields_configuration;
	}

	public function get_field_used_once(): bool
	{
		return $this->field_used_once;
	}

	public function set_name(string $name): void
	{
		$this->name = $name;
	}

	public function get_name(): string
	{
		return $this->name;
	}

	public function get_field_used_phpboost_configuration(): bool
	{
		return $this->field_used_phpboost_config;
	}

	public function set_form(HTMLForm $form): void
	{
		$this->form = $form;
	}

	public function get_form(): HTMLForm
	{
		return $this->form;
	}
}
?>
