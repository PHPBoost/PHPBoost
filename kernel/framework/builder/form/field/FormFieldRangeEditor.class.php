<?php
/*##################################################
 *                             FormFieldRangeEditor.class.php
 *                            -------------------
 *   begin                : June 1, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 * @desc This class manages a range of numbers (slider).
 * @package {@package}
 */
class FormFieldRangeEditor extends FormFieldNumberEditor
{
	protected $type = 'range';
	/**
	 * @var boolean
	 */
	private $vertical = false;
	protected static $tpl_src = '<input type="{TYPE}"# IF C_MIN # min="{MIN}"# ENDIF ## IF C_MAX # max="{MAX}"# ENDIF ## IF C_STEP # step="{STEP}"# ENDIF # name="${escape(NAME)}" id="${escape(HTML_ID)}" value="{VALUE}"
	class="# IF C_READONLY #low-opacity # ENDIF #${escape(CLASS)}" # IF C_VERTICAL # orient="vertical" style="width: 20px; height: 200px; -webkit-appearance: slider-vertical; writing-mode: bt-lr;"# ENDIF # # IF C_PATTERN # pattern="{PATTERN}" # ENDIF # # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY # readonly="readonly" # ENDIF #>';
	
	/**
	 * @desc Constructs a FormFieldRange.
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param string $value Default value
	 * @param string[] $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct($id, $label, $value, $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-range');
	}

	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$field = new StringTemplate(self::$tpl_src);

		$field->put_all(array(
			'C_MIN' => $this->min !== null,
			'MIN' => $this->min,
			'C_MAX' => $this->max != 0,
			'MAX' => $this->max,
			'C_STEP' => $this->step > 0,
			'STEP' => $this->step,
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'TYPE' => $this->type,
			'VALUE' => $this->get_value(),
			'CLASS' => $this->get_css_class(),
			'C_DISABLED' => $this->is_disabled(),
			'C_READONLY' => $this->is_readonly(),
			'C_DISABLED' => $this->is_disabled(),
			'C_PATTERN' => $this->has_pattern(),
			'PATTERN' => $this->pattern,
			'C_VERTICAL' => $this->is_vertical()
		));

		$this->assign_common_template_variables($template);

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $field->render()
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
				case 'vertical':
					$this->vertical = (bool)$value;
					unset($field_options['vertical']);
					break;
			}
		}
		parent::compute_options($field_options);
	}

	/**
	 * @desc Tells whether the slider is vertical
	 * @return true if it is, false otherwise
	 */
	public function is_vertical()
	{
		return $this->vertical;
	}

	/**
	 * @desc Changes the fact that the field is vertical or not.
	 * @param bool $vertical true if it's vertical, false otherwise
	 */
	public function set_vertical($vertical)
	{
		$this->vertical = $vertical;
	}
}
?>