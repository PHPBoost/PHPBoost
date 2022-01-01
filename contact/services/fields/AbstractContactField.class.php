<?php
/**
 * Abstract class that proposes a default implementation for the ContactFieldType interface.
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 11 15
 * @since       PHPBoost 4.0 - 2013 07 31
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

abstract class AbstractContactField implements ContactFieldType
{
	protected $form;
	protected $disable_fields_configuration = array();
	protected $name;

	/**
	 * @var bool
	 */
	public function __construct()
	{
		$this->name = 'ContactField';
	}

	/**
	 * {@inheritdoc}
	 */
	public function display_field(ContactField $field)
	{
		$fieldset = $field->get_fieldset();
		return;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_value(HTMLForm $form, ContactField $field)
	{
		$field_name = $field->get_field_name();
		return $form->get_value($field_name, '');
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
				case 'regex':
					$this->disable_fields_configuration[] = $name;
					break;
				case 'possible_values':
					$this->disable_fields_configuration[] = $name;
					break;
				case 'default_value_small':
					$this->disable_fields_configuration[] = $name;
					break;
				case 'default_value_medium':
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
