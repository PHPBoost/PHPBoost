<?php
/*##################################################
 *                             field_input_text.class.php
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
 * It provides you additionnal field options :
 * <ul>
 * 	<li>size : The maximum size for the field</li>
 * 	<li>maxlength : The maximum length for the field</li>
 * 	<li>required_alert : Text displayed if field is empty (javscript only)</li>
 * </ul>
 * @package builder
 * @subpackage form
 */
class FormTextEdit extends AbstractFormField
{
	private $size = '';
	private $maxlength = '';

	public function __construct($id, $label, $value, $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$template = new Template('framework/builder/form/FormField.tpl');
			
		$validations = $this->get_onblur_validations();
		$onblur = !empty($this->on_blur) || !empty($validations);
		
		$field = '<input type="text" ';
		$field .= !empty($this->size) ? 'size="' . $this->size . '" ' : '';
		$field .= !empty($this->maxlength) ? 'maxlength="' . $this->maxlength . '" ' : '';
		$field .= 'name="' . $this->get_real_id() . '" ';
		$field .= !empty($this->id) ? 'id="' . $this->id . '" ' : '';
		$field .= 'value="' . $this->value . '" ';
		$field .= !empty($this->css_class) ? 'class="' . $this->css_class . '" ' : '';
		$field .= $onblur ? 'onblur="' . implode(';', $validations) . $this->on_blur . '" ' : '';
		$field .= '/>';

		$template->assign_vars(array(
			'ID' => $this->get_id(),
			'LABEL' => $this->get_label(),
			'DESCRIPTION' => $this->get_description(),
			'C_REQUIRED' => $this->is_required()
		));
		
		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $field
		));

		return $template;
	}

	protected function compute_fields_options(array $field_options)
	{
		parent::compute_options($field_options);
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
				default :
					throw new FormBuilderException(sprintf('Unsupported option %s with field ' . __CLASS__, $attribute));
			}
		}
	}
}

?>