<?php
/*##################################################
 *                             form_date_field.class.php
 *                            -------------------
 *   begin                : September 19, 2009
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
 * @desc This class manage single-line text fields for date input.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>required_alert : Text displayed if field is empty (javscript only)</li>
 * </ul>
 * @package builder
 * @subpackage form
 */
class FormTextDate extends FormField
{	
	private $calendar_day = 1;
	private $calendar_month = 1;
	private $calendar_year = 2009;
	private $num_instance = 0;
	private static $num_instances = 0;
	
	public function __construct($fieldId, $field_options = array())
	{
		$this->num_instance = ++self::$num_instances;

		$this->fill_attributes($fieldId, $field_options);
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'calendar_day' :
					$this->calendar_day = $value;
				break;
				case 'calendar_month' :
					$this->calendar_month = $value;
				break;
				case 'calendar_year' :
					$this->calendar_year = $value;
				break;
				default :
					$this->throw_error(sprintf('Unsupported option %s with field ' . __CLASS__, $attribute), E_USER_NOTICE);
			}
		}
	}
	
	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$Template = new Template('framework/builder/forms/field_date.tpl');
		
		$Template->assign_vars(array(
			'C_NOT_ALREADY_INCLUDED' => ($this->num_instance == 1),
			'INSTANCE' => $this->num_instance,
			'ID' => $this->id,
			'NAME' => $this->name,
			'VALUE' => $this->value,
			'CLASS' => !empty($this->css_class) ? ' ' . $this->css_class : '',
			'ONBLUR' => $this->on_blur,
			'CALENDAR_DAY' => $this->calendar_day,
			'CALENDAR_MONTH' => $this->calendar_month,
			'CALENDAR_YEAR' => $this->calendar_year,
			'L_FIELD_TITLE' => $this->title,
			'L_EXPLAIN' => $this->sub_title,
			'L_REQUIRE' => $this->required ? '* ' : ''
		));	
		
		return $Template->parse(Template::TEMPLATE_PARSER_STRING);
	}
}

?>