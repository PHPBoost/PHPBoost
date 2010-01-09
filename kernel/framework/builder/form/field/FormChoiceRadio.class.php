<?php
/*##################################################
 *                             FormFieldRadio.class.php
 *                            -------------------
 *   begin                : April 28, 2009
 *   copyright            : (C) 2009 Viarre Régis
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
 * @desc This class manage radio input fields.
 * @package builder
 * @subpackage form
 */
class FormFieldRadio implements FormField
{
	private $options = array(); //Array of FormFieldRadioOption

	/**
	 * @desc constructor It takes a variable number of parameters. The first two are required.
	 * @param string $field_id Name of the field.
	 * @param array $field_options Option for the field.
	 * @param FormFieldRadioOption Variable number of FormFieldRadioOption object to add in the FormFieldRadio.
	 */
	public function __construct($field_id, array $field_options = array(), array $options = array(), array $constraints = array())
	{
		parent::__construct($field_id, '', $field_options, $constraints);
		foreach($field_options as $attribute => $value)
		{
			throw new FormBuilderException(sprintf('Unsupported option %s with field ' . __CLASS__, strtolower($attribute)));
		}
		$this->options = $options;
	}

	/**
	 * @desc Add an option for the radio field.
	 * @param FormFieldRadioOption option The new option.
	 */
	public function add_option($option)
	{
		$this->options[] = $option;
	}

	/**
	 * @return string The html code for the radio input.
	 */
	public function display()
	{
		$template = new Template('framework/builder/forms/field_box.tpl');
			
		$template->assign_vars(array(
			'ID' => $this->id,
			'FIELD' => $this->options,
			'L_FIELD_TITLE' => $this->title,
			'L_EXPLAIN' => $this->sub_title,
			'C_REQUIRED' => $this->is_required()
		));

		foreach ($this->options as $option)
		{
			$option->set_name($this->name); //Set the same field name for each option.
			$template->assign_block_vars('field_options', array(
				'OPTION' => $option->display(),
			));
		}

		return $template->parse(Template::TEMPLATE_PARSER_STRING);
	}
}

?>