<?php
/*##################################################
 *                             field_select_option.class.php
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
 * @desc This class manage select field options.
 * @package builder
 * @subpackage form
 */
class FormSelectOption extends FormField
{
	public function __construct($field_options)
	{
		$this->fillAttributes('', $field_options);
		
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'label' :
					$this->label = $value;
				break;
				case 'selected' :
					$this->selected = $value;
				break;
				default :
					$this->throw_error(sprintf('Unsupported option %s with field option ' . __CLASS__, $attribute), E_USER_NOTICE);
			}
		}
	}
	
	/**
	 * @return string The html code for the select.
	 */
	public function display()
	{
		$option = '<option ';
		$option .= !empty($this->value) ? 'value="' . $this->value . '"' : '';
		$option .= (boolean)$this->selected ? ' selected="selected"' : '';
		$option .= '> ' . $this->label . '</option>' . "\n";
		
		return $option;
	}

	private $label = '';
	private $selected = false;
}

?>