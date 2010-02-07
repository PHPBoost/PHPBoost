<?php
/*##################################################
 *                             FormFieldTextEditor.class.php
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
 * @desc This class manage single-line text fields.
 * @package builder
 * @subpackage form
 */
class FormFieldTextEditor extends AbstractFormField
{
	private $size = 30;
	private $maxlength = 255;

	/**
	 * @desc Constructs a FormFieldTextEditor.
	 * It has these options in addition to the AbstractFormField ones:
	 * <ul>
	 * 	<li>size: The size (width) of the HTML field</li>
	 * 	<li>maxlength: The maximum length for the field</li>
	 * </ul>
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param string $value Default value
	 * @param string[] $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct($id, $label, $value, $field_options = array(), array $constraints = array())
	{
		$this->css_class = "text";
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$template = new FileTemplate('framework/builder/form/FormField.tpl');

		$field = '<input type="text" ';
		$field .= 'size="' . $this->size . '" ';
		$field .= 'maxlength="' . $this->maxlength . '" ';
		$field .= 'name="' . $this->get_html_id() . '" ';
		$field .= 'id="' . $this->get_html_id() . '" ';
		$field .= 'value="' . htmlspecialchars($this->value) . '" ';
		$field .= 'class="' . $this->css_class . '" ';
		$field .= ($this->get_disabled()) ? 'disabled="disabled" ' : '';
		$field .= 'onblur="' . $this->get_onblur_validation() . $this->get_onblur() . '" ';
		$field .= '/>';

		$this->assign_common_template_variables($template);

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $field
		));

		return $template;
	}

	protected function compute_options(array &$field_options)
	{
		foreach ($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'size' :
					$this->size = $value;
					unset($field_options['size']);
					break;
				case 'maxlength' :
					$this->maxlength = $value;
					unset($field_options['maxlength']);
					break;
			}
		}
		parent::compute_options($field_options);
	}
}

?>