<?php
/*##################################################
 *                             field_input_checkbox.class.php
 *                            -------------------
 *   begin                : April 28, 2009
 *   copyright            : (C) 2009 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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




/**
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class manages checkbox input fields.
 * @package builder
 * @subpackage form
 */
class FormCheckbox extends FormField
{
	private $options = array();
	
	public function __construct()
	{
		$field_id = func_get_arg(0);
		$field_options = func_get_arg(1);

		parent::__construct($field_id, '', $field_options);
		foreach($field_options as $attribute => $value)
			$this->throw_error(sprintf('Unsupported option %s with field ' . __CLASS__, strtolower($attribute)), E_USER_NOTICE);
		
		$nbr_arg = func_num_args() - 1;		
		for ($i = 2; $i <= $nbr_arg; $i++)
		{
			$option = func_get_arg($i);
			$this->add_errors($option->get_errors());
			$this->options[] = $option;
		}
	}

	/**
	 * @desc Add an option for the radio field.
	 * @param FormRadioChoiceOption option The new option. 
	 */
	public function add_option($option)
	{
		$this->options[] = $option;
	}
	
	/**
	 * @return string The html code for the checkbox input.
	 */
	public function display()
	{
		$Template = new Template('framework/builder/forms/field_box.tpl');
			
		$Template->assign_vars(array(
			'ID' => $this->id,
			'FIELD' => $this->options,
			'L_FIELD_TITLE' => $this->title,
			'L_EXPLAIN' => $this->sub_title,
			'L_REQUIRE' => $this->required ? '* ' : ''
		));	
		
		foreach($this->options as $Option)
		{
			$Option->set_name($this->name); //Set the same field name for each option.
			$Template->assign_block_vars('field_options', array(
				'OPTION' => $Option->display(),
			));	
		}
		
		return $Template->parse(Template::TEMPLATE_PARSER_STRING);
	}
}

?>