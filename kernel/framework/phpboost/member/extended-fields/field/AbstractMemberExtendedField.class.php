<?php
/**
 * Abstract class that proposes a default implementation for the MemberExtendedFieldType interface.
 * @package     PHPBoost
 * @subpackage  Member\extended-fields\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 07 06
 * @since       PHPBoost 3.0 - 2010 12 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

abstract class AbstractMemberExtendedField implements MemberExtendedFieldType
{
	protected $lang;
	protected $form;
	protected $field_used_once;
	protected $field_used_phpboost_config;
	protected $disable_fields_configuration = array();
	protected $name;

	/**
	 * @var bool
	 */
	public function __construct()
	{
		$this->lang = LangLoader::get('user-common');
		$this->field_used_once = false;
		$this->field_used_phpboost_config = false;
		$this->name = 'ExtendedField';
	}

	/**
	 * {@inheritdoc}
	 */
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		return;
	}

	/**
	 * {@inheritdoc}
	 */
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		return;
	}

	public function delete_field(MemberExtendedField $member_extended_field)
	{
		return;
	}

	/**
	 * {@inheritdoc}
	 */
	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		if ($member_extended_field->get_value())
		{
			return array('name' => $member_extended_field->get_name(), 'value' => $member_extended_field->get_value());
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		return TextHelper::htmlspecialchars($form->get_value($field_name, ''));
	}

	/**
	 * {@inheritdoc}
	 */
	public function constraint($value)
	{
		if (is_numeric($value))
		{
			switch ($value)
			{
				case 2:
					return new FormFieldConstraintRegex('`^[a-zA-Z]+$`iu');
					break;
				case 3:
					return new FormFieldConstraintRegex('`^[a-zA-Z0-9]+$`iu');
					break;
				case 7:
					return new FormFieldConstraintRegex('`^[a-zA-Zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ-]+$`iu');
					break;
			}
		}
		elseif (is_string($value) && !empty($value))
		{
			return new FormFieldConstraintRegex($value);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_disable_fields_configuration(array $names)
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

	/**
	 * {@inheritdoc}
	 */
	public function get_disable_fields_configuration()
	{
		return $this->disable_fields_configuration;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_field_used_once()
	{
		return $this->field_used_once;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_name($name)
	{
		$this->name = $name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_name()
	{
		return $this->name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_field_used_phpboost_configuration()
	{
		return $this->field_used_phpboost_config;
	}

	public function set_form(HTMLForm $form)
	{
		$this->form = $form;
	}

	public function get_form()
	{
		return $this->form;
	}
}
?>
