<?php
/*##################################################
 *                             FormFieldColorPicker.class.php
 *                            -------------------
 *   begin                : October 21, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @package {@package}
 */
class FormFieldColorPicker extends AbstractFormField
{
	private static $tpl_src = '<input type="color" name="${escape(NAME)}" id="${escape(HTML_ID)}" value="${escape(VALUE)}" pattern="#[A-Fa-f0-9]{6}" placeholder="#000000" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_HIDDEN # style="display:none;" # ENDIF #>';
	
	public function __construct($id, $label, $value, array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-color');
	}

	/**
	 * @return Template The html code for the file input.
	 */
	function display()
	{
		$template = $this->get_template_to_use();
		
		$field = new StringTemplate(self::$tpl_src);
		
		$field->put_all(array(
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
	
	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
}
?>