<?php
/*##################################################
 *                       ContactConstraintFieldExist.class.php
 *                            -------------------
 *   begin                : August 4, 2013
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

class ContactConstraintFieldExist extends AbstractFormFieldConstraint
{
	private $field_id = 0;
	private $error_message;
	
	public function __construct($field_id = 0, $error_message = '')
	{
		if (!empty($field_id))
		{
			$this->field_id = $field_id;
		}
		
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('message.field_name_already_used', 'common', 'contact');
		}
		$this->set_validation_error_message($error_message);
		$this->error_message = TextHelper::to_js_string($error_message);
	}
	
	public function validate(FormField $field)
	{
		$field_name = ContactField::rewrite_field_name($field->get_value());
		
		$valid = true;
		
		if (!empty($this->field_id))
		{
			foreach (ContactConfig::load()->get_fields() as $id => $f)
			{
				if ($id != $this->field_id && $f['field_name'] == $field_name)
					$valid = false;
			}
		}
		else
		{
			foreach (ContactConfig::load()->get_fields() as $id => $f)
			{
				if ($f['field_name'] == $field_name)
					$valid = false;
			}
		}
		
		return $valid;
	}
	
	public function get_js_validation(FormField $field)
	{
		return 'ContactFieldExistValidator(' . $this->error_message . ', ' . $this->field_id . ')';
	}
}
?>
