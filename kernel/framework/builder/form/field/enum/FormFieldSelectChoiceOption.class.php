<?php
/*##################################################
 *                       FormFieldSelectOption.class.php
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
 * @desc This class manage select field options.
 * @package {@package}
 */
class FormFieldSelectChoiceOption extends AbstractFormFieldEnumOption
{
	/**
	 * @param $label string The label
	 * @param $raw_value string The raw value
	 * @param string[] $field_choice_options Map associating the parameters values to the parameters names.
	 */
	public function __construct($label, $raw_value, $field_choice_options = array())
	{
		parent::__construct($label, $raw_value, $field_choice_options);
	}
		
	/**
	 * @return string The html code for the select.
	 */
	public function display()
	{
		$tpl_src = '<option value="${escape(VALUE)}" # IF C_SELECTED # selected="selected" # ENDIF # # IF C_DISABLE # disabled="disabled" # ENDIF # label="{LABEL}">{LABEL}</option>';
		
		$tpl = new StringTemplate($tpl_src);
		$tpl->put_all(array(
			'VALUE' => $this->get_raw_value(),
			'C_SELECTED' => $this->is_active(),
			'C_DISABLE' => $this->is_disable(),
			'LABEL' => $this->get_label()
		));
		
		return $tpl;
	}
}

?>