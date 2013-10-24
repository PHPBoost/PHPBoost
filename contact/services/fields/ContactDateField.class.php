<?php
/*##################################################
 *                               ContactDateField.class.php
 *                            -------------------
 *   begin                : July 31, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 
class ContactDateField extends AbstractContactField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('regex', 'possible_values'));
		$this->set_name(LangLoader::get_message('field.type.date', 'common', 'contact'));
	}
	
	public function display_field(ContactField $field)
	{
		$fieldset = $field->get_fieldset();
		
		$value = $field->get_value() ? new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $field->get_value()) : null;
		$fieldset->add_field(new FormFieldDate($field->get_field_name(), $field->get_name(), $value, 
			array('description' => $field->get_description(), 'required' =>(bool)$field->is_required())
		));
	}
	
	public function return_value(HTMLForm $form, ContactField $field)
	{
		$field_name = $field->get_field_name();
		if ($form->has_field($field_name))
			return $form->get_value($field_name)->format(DATE_TIMESTAMP);
		
		return '';
	}
}
?>
