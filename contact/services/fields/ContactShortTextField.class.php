<?php
/*##################################################
 *                               ContactShortTextField.class.php
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
 
class ContactShortTextField extends AbstractContactField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('possible_values', 'default_value_medium'));
		$this->set_name(LangLoader::get_message('type.short-text', 'admin-user-common'));
	}
	
	public function display_field(ContactField $field)
	{
		$fieldset = $field->get_fieldset();
		$regex = $field->get_regex();
		
		switch ($regex)
		{
			case 1:
				$field_class = 'FormFieldNumberEditor';
				$display_constraint = false;
				break;
			case 4:
				$field_class = 'FormFieldMailEditor';
				$display_constraint = false;
				break;
			case 5:
				$field_class = 'FormFieldUrlEditor';
				$display_constraint = false;
				break;
			case 8:
				$field_class = 'FormFieldTelEditor';
				$display_constraint = false;
				break;
			default:
				$field_class = 'FormFieldTextEditor';
				$display_constraint = true;
				break;
		}
		
		$fieldset->add_field(new $field_class($field->get_field_name(), $field->get_name(), $field->get_default_value(), array(
			'required' => (bool)$field->is_required(), 'description' => $field->get_description()),
			($display_constraint ? array($this->constraint($regex)) : array())
		));
	}
}
?>
