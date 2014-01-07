<?php
/*##################################################
 *                         FormFieldConstraintLengthRange.class.php
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
class FormFieldConstraintLengthRange extends AbstractFormFieldConstraint 
{
	private $error_message;
	private $rboundary;
	private $lboundary;
	
	public function __construct($lboundary, $rboundary, $error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('doesnt_match_length_intervall', 'builder-form-Validator');
		}
		$this->set_validation_error_message($error_message);
		$this->error_message = $error_message;
		$this->lboundary = $lboundary;
		$this->rboundary = $rboundary;
	}
	
	public function validate(FormField $field)
	{
		$value = strlen($field->get_value());
		$is_required = $field->is_required();
		if (!empty($value) || $is_required)
		{
			return ($value >= $this->lboundary && $value <= $this->rboundary);
		}
		return true;
	}

	public function get_js_validation(FormField $field)
	{
		return 'lengthFormFieldValidator(' . TextHelper::to_js_string($field->get_id()) . ', ' . $this->lboundary . ', ' . 
		$this->rboundary . ', ' . TextHelper::to_js_string($this->error_message) . ')';
	}
}

?>