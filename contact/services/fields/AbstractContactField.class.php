<?php
/*##################################################
 *                               AbstractContactField.class.php
 *                            -------------------
 *   begin                : July 31, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/
 
/**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 * @desc Abstract class that proposes a default implementation for the ContactFieldType interface.
 * @package {@package}
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
					return new FormFieldConstraintRegex('`^[a-zA-Z]+$`i');
					break;
				case 3:
					return new FormFieldConstraintRegex('`^[a-zA-Z0-9]+$`i');
					break;
				case 7:
					return new FormFieldConstraintRegex('`^[a-zA-Zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ-]+$`i');
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
			$name = strtolower($name);
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
