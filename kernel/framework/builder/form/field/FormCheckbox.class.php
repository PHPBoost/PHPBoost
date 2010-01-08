<?php
/*##################################################
 *                          FormFieldCheckbox.class.php
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
 * @desc This class manages checkbox input fields.
 * @package builder
 * @subpackage form
 */
class FormCheckbox extends AbstractFormField
{
	const CHECKED = true;
	const UNCHECKED = false;

	public function __construct($id, $label, $checked = self::UNCHECKED, array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $checked, $field_options, $constraints);
	}

	/**
	 * @return string The html code for the checkbox input.
	 */
	public function display()
	{
		$template = new Template('framework/builder/form/FormField.tpl');

		$template->assign_vars(array(
			'ID' => $this->get_real_id(),
			'LABEL' => $this->get_label(),
			'DESCRIPTION' => $this->get_description(),
			'C_REQUIRED' => $this->is_required()
		));

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $this->generate_html_code()
		));

		return $template;
	}

	public function is_checked()
	{
		return $this->get_value() == self::CHECKED;
	}

	public function retrieve_value()
	{
		$request = AppContext::get_request();
		if ($request->has_parameter($this->get_real_id()))
		{
			$this->set_value($request->get_value($this->get_real_id()) == 'on' ? true : false);
		}
		else
		{
			$this->set_value(false);
		}
	}

	private function generate_html_code()
	{
		$option = '<input type="checkbox" ';
		$option .= 'name="' . $this->get_real_id() . '" ';
		$option .= 'id="' . $this->get_real_id() . '" ';
		$option .= $this->is_checked() ? 'checked="checked" ' : '';
		$option .= '/>';

		return $option;
	}
}

?>