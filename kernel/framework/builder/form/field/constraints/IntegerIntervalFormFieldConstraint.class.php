<?php
/*##################################################
 *                         IntegerIntervalFormFieldConstraint.class.php
 *                            -------------------
 *   begin                : December 20, 2009
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
 * @author Régis Viarre <crowkait@phpboost.com>, Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc 
 * @package builder
 * @subpackage form/constraint
 */ 
class IntegerIntervalFormFieldConstraint implements FormFieldConstraint 
{
	private $js_message;
	private $rboundary;
	private $lboundary;
	
	public function __construct($lboundary, $rboundary, $js_message = '')
	{
		if (empty($js_message))
		{
			$js_message = LangLoader::get_message('doesnt_match_integer_intervall', 'builder-form-Validator');
		}
		$this->js_message = TextHelper::to_js_string($js_message);
		$this->lboundary = $lboundary;
		$this->rboundary = $rboundary;
	}
	
	public function validate(FormField $field)
	{
		if (!is_numeric($field->get_value()))
		{
			return false;
		}
		$value = (int)$field->get_value();		
		return ($value >= $this->lboundary && $value <= $this->rboundary);
	}

	public function get_js_validation(FormField $field)
	{
		return 'integerIntervalFormFieldValidator(' . TextHelper::to_js_string($field->get_html_id()) . ', 
		' . (int)$this->lboundary . ', ' . (int)$this->rboundary . ', ' . $this->js_message . ')';
	}
}

?>