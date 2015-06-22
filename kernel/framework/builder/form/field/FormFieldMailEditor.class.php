<?php
/*##################################################
 *                             FormFieldMailEditor.class.php
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
 * @desc This class manages a mail address.
 * @package {@package}
 */
class FormFieldMailEditor extends FormFieldTextEditor
{
	protected $type = 'email';
	/**
	 * @var boolean
	 */
	private $multiple = false;
	protected static $tpl_src = '<input type="{TYPE}" size="{SIZE}" maxlength="{MAX_LENGTH}" name="${escape(NAME)}" id="${escape(HTML_ID)}" value="{VALUE}" class="# IF C_READONLY #low-opacity # ENDIF #${escape(CLASS)}" # IF C_PATTERN # pattern="{PATTERN}" # ENDIF # # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY # readonly="readonly" # ENDIF # # IF C_MULTIPLE # multiple="multiple" # ENDIF #>';
	
	/**
	 * @desc Constructs a FormFieldMailEditor.
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param string $value Default value
	 * @param string[] $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct($id, $label, $value, $field_options = array(), array $constraints = array())
	{
		if (isset($field_options['multiple']))
		{
			$simple_regex = AppContext::get_mail_service()->get_mail_checking_raw_regex();
			$constraints[] = new FormFieldConstraintRegex('`^' . $simple_regex . '(?:,' . $simple_regex . ')*$`i');
		}
		else
		{
			$constraints[] = new FormFieldConstraintMailAddress();
		}
		parent::__construct($id, $label, $value, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-email');
	}

	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$field = new StringTemplate(self::$tpl_src);

		$field->put_all(array(
			'SIZE' => $this->size,
			'MAX_LENGTH' => $this->maxlength,
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'TYPE' => $this->type,
			'VALUE' => $this->get_value(),
			'CLASS' => $this->get_css_class(),
			'C_DISABLED' => $this->is_disabled(),
			'C_READONLY' => $this->is_readonly(),
			'C_MULTIPLE' => $this->is_multiple()
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
				case 'multiple':
					$this->multiple = (bool)$value;
					unset($field_options['multiple']);
					break;
			}
		}
		parent::compute_options($field_options);
	}

	/**
	 * @desc Tells whether the field is multiple
	 * @return true if it is, false otherwise
	 */
	public function is_multiple()
	{
		return $this->multiple;
	}

	/**
	 * @desc Changes the fact that the field is multiple or not.
	 * @param bool $multiple true if it's multiple, false otherwise
	 */
	public function set_multiple($multiple)
	{
		$this->multiple = $multiple;
	}
}
?>