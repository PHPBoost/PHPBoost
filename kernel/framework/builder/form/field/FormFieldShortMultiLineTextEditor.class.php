<?php
/*##################################################
 *                          FormFieldShortMultiLineTextEditor.class.php
 *                            -------------------
 *   begin                : December 15, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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
 * @desc This class represents a short multi-line text field.
 * @package {@package}
 */
class FormFieldShortMultiLineTextEditor extends FormFieldMultiLineTextEditor
{
	private $width = 0;
    private static $tpl_src = '<textarea id="${escape(HTML_ID)}" name="${escape(NAME)}" rows="{ROWS}" cols="{COLS}" class="# IF C_READONLY #low-opacity # ENDIF #${escape(CLASS)}" style="{WIDTH}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY # readonly="readonly" # ENDIF #>{VALUE}</textarea>';

    /**
     * @desc Constructs a FormFieldShortMultiLineTextEditor.
     * It has these options in addition to the AbstractFormField ones:
     * <ul>
     * 	<li>rows: the number of rows of the texarea</li>
     * 	<li>cols: the number of cols of the textarea</li>
	 *  <li>width: the number pourcent of width of the textarea</li>
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
			'ROWS' => $this->rows,
			'COLS' => $this->cols,
			'WIDTH' => ($this->width > 0) ? 'width: ' . $this->width . '%;' : '',
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
				case 'width' :
                    $this->width = $value;
                    unset($field_options['width']);
                    break;
            }
        }
        parent::compute_options($field_options);
    }

    protected function get_default_template()
    {
        return new FileTemplate('framework/builder/form/FormFieldShortMultiLineTextEditor.tpl');
    }
}
?>