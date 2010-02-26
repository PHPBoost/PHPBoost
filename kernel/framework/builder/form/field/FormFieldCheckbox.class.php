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
 * @desc The class FormCheckBox represents a checkbox field in a form. It corresponds to a boolean.
 * @package builder
 * @subpackage form
 */
class FormFieldCheckbox extends AbstractFormField
{
    const CHECKED = true;
    const UNCHECKED = false;

    /**
     * @desc Constructs a FormFieldCheckbox.
     * @param string $id Field identifier
     * @param string $label Field label
     * @param bool $checked FormFieldCheckbox::CHECKED if it's checked by default or FormFieldCheckbox::UNCHECKED if not checked.
     * @param string[] $field_options Map containing the options
     * @param FormFieldConstraint[] $constraints The constraints checked during the validation
     */
    public function __construct($id, $label, $checked = self::UNCHECKED, array $field_options = array(), array $constraints = array())
    {
        parent::__construct($id, $label, $checked, $field_options, $constraints);
    }

    /**
     * {@inheritdoc}
     */
    public function display()
    {
        $template = $this->get_template_to_use();

        $this->assign_common_template_variables($template);

        $template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $this->generate_html_code()->to_string()
        ));

        return $template;
    }

    /**
     * Tells whether the checkbox is checked
     * @return bool
     */
    public function is_checked()
    {
        return $this->get_value() == self::CHECKED;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve_value()
    {
        $request = AppContext::get_request();
        if ($request->has_parameter($this->get_html_id()))
        {
            $this->set_value($request->get_value($this->get_html_id()) == 'on' ? true : false);
        }
        else
        {
            $this->set_value(false);
        }
    }

    private function generate_html_code()
    {
        $tpl_src = '<input type="checkbox" name="{NAME}" id="{ID}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_CHECKED # checked="checked" # ENDIF # />';

        $tpl = new StringTemplate($tpl_src);
        $tpl->assign_vars(array(
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled(),
			'C_CHECKED' => $this->is_checked()
        ));

        return $tpl;
    }

    protected function get_default_template()
    {
        return new FileTemplate('framework/builder/form/FormField.tpl');
    }
}

?>