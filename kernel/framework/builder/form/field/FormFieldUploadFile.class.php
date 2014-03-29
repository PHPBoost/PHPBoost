<?php
/*##################################################
 *		       FormFieldUploadFile.class.php
 *                            -------------------
 *   begin                : December 10, 2013
 *   copyright            : (C) 2013 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
 *
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
 * @author Kévin MASSY <kevin.massy@phpboost.com>
 * @desc This class manage single-line text fields with a link to access the upload modal form.
 * @package {@package}
 */
class FormFieldUploadFile extends AbstractFormField
{
    /**
     * @desc Constructs a FormFieldUploadFileTextEditor.
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

        $field = new FileTemplate('framework/builder/form/FormFieldUploadFile.tpl');

        $field->put_all(array(
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_html_id(),
			'VALUE' => $this->get_value(),
			'CLASS' => $this->get_css_class(),
			'C_DISABLED' => $this->is_disabled(),
			'C_READONLY' => $this->is_readonly(),
			'L_FILE_ADD' => LangLoader::get_message('bb_upload', 'editor-common')
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