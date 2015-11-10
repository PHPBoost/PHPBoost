<?php
/*##################################################
 *                         FormConstraintFieldsEquality.class.php
 *                            -------------------
 *   begin                : Februar 04, 2010
 *   copyright            : (C) 2010 Régis Viarre
 *   email                : crowkait@phpboost.com
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
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc
 * @package {@package}
 */
class FormConstraintFieldsEquality implements FormConstraint
{
	private $js_message;
	/**
	 * @var FormField
	 */
	private $first_field;
	/**
	 * @var FormField
	 */
	private $second_field;

	public function __construct(FormField $first_field, FormField $second_field, $js_message = '')
	{
		if (!empty($js_message))
		{
			$this->js_message = $js_message;
		}
		else
		{
			$this->js_message = LangLoader::get_message('form.fields_must_be_equal', 'status-messages-common');
		}

		$this->first_field = $first_field;
		$this->second_field = $second_field;

		$this->second_field->add_form_constraint($this);
	}

	public function validate()
	{
		$first_value = $this->first_field->get_value();
		$second_value = $this->second_field->get_value();
		if ($first_value !== null && $second_value !== null)
		{
			if ($first_value == $second_value)
			{
				return true;
			}
		}
		return false;
	}

	public function get_js_validation()
	{
		return 'equalityFormFieldValidator(' . TextHelper::to_js_string($this->first_field->get_id()) .
			', ' . TextHelper::to_js_string($this->second_field->get_id()) . ', ' . TextHelper::to_js_string($this->get_validation_error_message()) . ')';
	}

	public function get_validation_error_message()
	{
		return StringVars::replace_vars($this->js_message, 
			array('field1' => $this->first_field->get_label(), 'field2' => $this->second_field->get_label()));
	}

	public function get_related_fields()
	{
		return array($this->first_field, $this->second_field);
	}
}

?>