<?php
/*##################################################
 *		       FormFieldUploadFileTextEditor.class.php
 *                            -------------------
 *   begin                : July 10, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 * @desc This class manage single-line text fields with a link to access the upload modal form.
 * @package {@package}
 */
class FormFieldUploadFileTextEditor extends AbstractFormField
{
    private $size = 30;
    private $maxlength = 255;
    private static $tpl_src = '<input type="text" size="{SIZE}" maxlength="{MAX_LENGTH}" name="${escape(NAME)}" id="${escape(ID)}" value="{VALUE}"
	class="# IF C_READONLY #low_opacity # ENDIF #${escape(CLASS)}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY # readonly="readonly" # ENDIF # />
	<a title="{L_FILE_ADD}" href="#" onclick="${escape(NAME)}.select();${escape(NAME)}.value=\'\';window.open(\'{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=${escape(NAME)}&amp;parse=true\', \'\', \'height=500,width=720,resizable=yes,scrollbars=yes\');return false;">
	<img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/files_add.png" alt="" /></a>';

    /**
     * @desc Constructs a FormFieldUploadFileTextEditor.
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
        $template = $this->get_template_to_use();

        $field = new StringTemplate(self::$tpl_src);

        $field->put_all(array(
			'SIZE' => $this->size,
			'MAX_LENGTH' => $this->maxlength,
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

    protected function compute_options(array &$field_options)
    {
        foreach ($field_options as $attribute => $value)
        {
            $attribute = strtolower($attribute);
            switch ($attribute)
            {
                case 'size':
                    $this->size = $value;
                    unset($field_options['size']);
                    break;
                case 'maxlength':
                    $this->maxlength = $value;
                    unset($field_options['maxlength']);
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