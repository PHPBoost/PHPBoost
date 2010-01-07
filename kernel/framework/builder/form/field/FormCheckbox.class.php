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
class FormCheckbox extends FormField
{
	private $checked = false;

	const CHECKED = true;
	const UNCHECKED = false;

	public function __construct($field_id, $title, $checked = self::UNCHECKED, array $field_options = array(), array $options = array(), array $constraints = array())
	{
		parent::__construct(__CLASS__ . $field_id, '', $field_options, $constraints);
		$this->title = $title;
		$this->options = $options;
		$this->checked = $checked;
		$this->constraints = $constraints;
	}

	/**
	 * @return string The html code for the checkbox input.
	 */
	public function display()
	{
		$template = new Template('framework/builder/forms/field_box.tpl');
			
		$template->assign_vars(array(
		'ID' => $this->id,
		'FIELD' => $this->options,
		'L_FIELD_TITLE' => $this->title,
		'L_EXPLAIN' => $this->sub_title,
		'L_REQUIRE' => $this->required ? '* ' : ''
		));
		
		$template->assign_block_vars('field_options', array(
			'OPTION' => $this->generate_html_code()
		));

		return $template->parse(Template::TEMPLATE_PARSER_STRING);
	}

	private function generate_html_code()
	{
		$option = '<input type="checkbox" ';
		$option .= 'name="' . $this->name . '" ';
		$option .= 'id="' . $this->id . '" ';
		$option .= !empty($this->value) ? 'value="' . $this->value . '" ' : '';
		$option .= (boolean)$this->checked ? 'checked="checked" ' : '';
		$option .= '/><br />' . "\n";

		return $option;
	}
}

?>