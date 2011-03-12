<?php
/*##################################################
 *                         FormFieldConstraintNotEmpty.class.php
 *                            -------------------
 *   begin                : December 19, 2009
 *   copyright            : (C) 2009 Régis Viarre, Loic Rouchon
 *   email                : crowkait@phpboost.com, loic.rouchon@phpboost.com
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
 * @author Régis Viarre <crowkait@phpboost.com>, Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc 
 * @package {@package}
 */ 
class FormFieldConstraintNotEmpty extends AbstractFormFieldConstraint
{
	private $error_message;
	
	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('has_to_be_filled', 'builder-form-Validator');
		}
		$this->error_message = $error_message;
	}
	
	public function validate(FormField $field)
	{
		$value = $field->get_value();
		$this->set_validation_error_message(StringVars::replace_vars($this->error_message, array('name' => strtolower($field->get_label()))));
        return $value !== null && $value != '';
	}

	public function get_js_validation(FormField $field)
	{
		return 'nonEmptyFormFieldValidator(' . TextHelper::to_js_string($field->get_id()) .
			', ' . TextHelper::to_js_string(StringVars::replace_vars($this->error_message, array('name' => strtolower($field->get_label())))) .')';
	}
}

?>