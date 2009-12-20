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
	private $js_message;
	
	public function __construct($js_message)
	{
		$this->js_message = $js_message;
	}
	
	public function validate(FormField $field)
	{
		$value = $field->get_value($name);
		return $value !== null && $value != '';
	}

	public function generate_onblur_validation(FormField $field)
	{
		return '';
	}

	public function generate_onsubmit_validation(FormField $field)
	{
		return 'document.getElementById("' . $field->get_id() .  '").value != ""';
	}
}

?>