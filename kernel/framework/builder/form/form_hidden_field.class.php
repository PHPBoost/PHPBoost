<?php
/*##################################################
 *                             field_input_hidden.class.php
 *                            -------------------
 *   begin                : April 28, 2009
 *   copyright            : (C) 2009 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

import('builder/form/form_field');

/**
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class manage hidden input fields.
 * @package builder
 * @subpackage form
 */
class FormHiddenField extends FormField
{
	public function __construct($fieldId, $field_options = array())
	{
		$this->fill_attributes($fieldId, $field_options);
	}
	
	public function display()
	{
		$field = '<input type="hidden" ';
		$field .= 'name="' . $this->name . '" ';
		$field .= !empty($this->id) ? 'id="' . $this->id . '" ' : '';
		$field .= !empty($this->value) ? 'value="' . $this->value . '" ' : '';
		$field .= '/>';
		
		return $field;
	}
}

?>