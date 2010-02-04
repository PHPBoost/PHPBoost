<?php
/*##################################################
 *                             FormFieldSelect.class.php
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
 * @desc This class manage select fields.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>multiple : Type of select field, mutiple allow you to check several options.</li>
 * </ul>
 * @package builder
 * @subpackage form
 */
class FormFieldSelectChoice extends AbstractFormFieldChoice
{
	/**
	 * @desc Constructs a FormFieldSelectChoice.
	 * @param string $id Field id
	 * @param string $label Field label
	 * @param FormFieldEnumOption Default value
	 * @param FormFieldEnumOption[] $options Enumeration of the possible values
	 * @param string[] $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
	 * @param FormFieldConstraint List of the constraints
	 */
	public function __construct($id, $label, FormFieldEnumOption $value, array $options, array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $options, $field_options, $constraints);
	}

	/**
	 * @return string The html code for the select.
	 */
	public function display()
	{
		$template = new FileTemplate('framework/builder/form/FormField.tpl');

		$this->assign_common_template_variables($template);

		$template->assign_vars(array(
			'C_HAS_CONSTRAINTS' => (bool)$this->has_constraints(),
		));
		
		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $this->get_html_code(),
		));

		return $template;
	}

	private function get_html_code()
	{
		$code = '<select name="' . $this->get_html_id() . '" id="' . $this->get_html_id() . '" class="' . $this->get_css_class() . '">';
		foreach ($this->get_options() as $option)
		{
			$code .= $option->display();
		}
		$code .= '</select>';
		return $code;
	}

	protected function get_option($raw_value)
	{
		foreach ($this->get_options() as $option)
		{
			$result = $option->get_option($raw_value); 
			if ($result !== null)
			{
				return $result;
			}
		}
		return null;
	}
}

?>