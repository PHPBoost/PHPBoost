<?php
/*##################################################
 *                  FormFieldMiltipleCheckboxOption.class.php
 *                            -------------------
 *   begin                : November 20, 2010
 *   copyright            : (C) 2010 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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
 * @desc This class represents an option for the FormFieldMultipleCheckbox
 * @package {@package}
 */
class FormFieldMultipleCheckboxOption
{
	private $id;
	private $label;
	
	public function __construct($id, $label)
	{
		$this->id = $id;
		$this->label = $label;
	}

	public function get_id()
	{
		return $this->id;
	}
	
	public function get_label()
	{
		return $this->label;
	}
}

?>