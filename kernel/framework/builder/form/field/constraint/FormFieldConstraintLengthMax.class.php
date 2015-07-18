<?php
/*##################################################
 *                         FormFieldConstraintLengthMax.class.php
 *                            -------------------
 *   begin                : December 19, 2009
 *   copyright            : (C) 2009 Régis Viarre
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
class FormFieldConstraintLengthMax extends AbstractFormFieldConstraint 
{
	private $error_message;
	private $upper_bound;
	
	public function __construct($upper_bound, $js_message = '')
	{
		if (empty($js_message))
		{
			$js_message = LangLoader::get_message('form.doesnt_match_length_max', 'status-messages-common');
		}
		$this->error_message = StringVars::replace_vars($js_message, array('upper_bound' => $upper_bound));
		$this->set_validation_error_message($this->error_message);
		$this->upper_bound = $upper_bound;
	}
	
	public function validate(FormField $field)
	{
		$value = strlen($field->get_value());
		$is_required = $field->is_required();
		if (!empty($value) || $is_required)
		{
			return ($value <= $this->upper_bound);
		}
		return true;
	}

	public function get_js_validation(FormField $field)
	{
		return 'lengthMaxFormFieldValidator(' . TextHelper::to_js_string($field->get_id()) . ', ' . $this->upper_bound . ', ' . TextHelper::to_js_string($this->error_message) . ')';
	}
}

?>