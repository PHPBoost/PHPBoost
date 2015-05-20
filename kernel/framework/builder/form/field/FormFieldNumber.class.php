<?php
/*##################################################
 *                             FormFieldNumber.class.php
 *                            -------------------
 *   begin                : May 20, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 * @desc This class manage number fields.
 * @package {@package}
 */
class FormFieldNumber extends AbstractFormField
{
	private $min = 0;
	private $max = 0;
	private $step = 0;
	private static $tpl_src = '<input type="number"# IF C_MIN # min="{MIN}"# ENDIF ## IF C_MAX # max="{MAX}"# ENDIF ## IF C_STEP # step="{STEP}"# ENDIF # name="${escape(NAME)}" id="${escape(HTML_ID)}" value="{VALUE}"
	class="# IF C_READONLY #low-opacity # ENDIF #${escape(CLASS)}" pattern="[0-9]*" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY # readonly="readonly" # ENDIF #>';

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
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$field = new StringTemplate(self::$tpl_src);

		$field->put_all(array(
			'C_MIN' => $this->min != 0,
			'MIN' => $this->min,
			'C_MAX' => $this->max != 0,
			'MAX' => $this->max,
			'C_STEP' => $this->step > 0,
			'STEP' => $this->step,
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'VALUE' => $this->get_value(),
			'CLASS' => $this->get_css_class(),
			'C_DISABLED' => $this->is_disabled(),
			'C_READONLY' => $this->is_readonly()
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
				case 'min':
					$this->min = $value;
					unset($field_options['min']);
					break;
				case 'max':
					$this->max = $value;
					unset($field_options['max']);
					break;
				case 'step':
					$this->step = $value;
					unset($field_options['step']);
					break;
			}
		}
		parent::compute_options($field_options);
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
}
?>