<?php
/*##################################################
 *                         NotEmptyFormFieldConstraint.class.php
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
 * @package builder
 * @subpackage form/constraint
 */ 
class NotEmptyFormFieldConstraint implements FormFieldConstraint 
{
	private $js_onblur_message;
	private $js_onsubmit_message;
	
	public function __construct($js_onblur_message = '', $js_onsubmit_message = '')
	{
		if (empty($js_onblur_message))
		{
			$js_onblur_message = LangLoader::get_message('this_has_to_be_filled', 'builder-form-Validator');
		}
		$this->js_onblur_message = TextHelper::to_js_string($js_onblur_message);
		
		$this->js_onsubmit_message = $js_onsubmit_message;
	}
	
	public function validate(FormField $field)
	{
		$value = $field->get_value();
		return $value !== null && $value != '';
	}

	public function get_onblur_validation(FormField $field)
	{
		return 'nonEmptyFormFieldOnblurValidator(' . TextHelper::to_js_string($field->get_html_id()) .
			', ' . $this->js_onblur_message . ')';
	}

	public function get_onsubmit_validation(FormField $field)
	{
		if (empty($this->js_onsubmit_message))
		{
			$this->js_onsubmit_message = sprintf(LangLoader::get_message('has_to_be_filled', 'builder-form-Validator'), $field->get_label());
		}
		
		return 'notEmptyFormFieldOnsubmitValidator(' . TextHelper::to_js_string($field->get_html_id()) . ', ' . TextHelper::to_js_string($this->js_onsubmit_message) . ')';
	}
}

?>