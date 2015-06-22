<?php
/*##################################################
 *                             FormFieldFilePicker.class.php
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
 * @desc This class manage file input fields.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>size : The size for the field</li>
 * </ul>
 * @package {@package}
 */
class FormFieldFilePicker extends AbstractFormField
{
    private $max_size = 0;
    private static $tpl_src = '<input name="max_file_size" value="{MAX_FILE_SIZE}" type="hidden">
		<input type="file" name="${escape(NAME)}" id="${escape(HTML_ID)}" # IF C_DISABLED # disabled="disabled" # ENDIF #>
		<script>
		<!--
        jQuery("#${escape(HTML_ID)}").parents("form:first")[0].enctype = "multipart/form-data";
		-->
		</script>';

    public function __construct($id, $label, array $field_options = array(), array $constraints = array())
    {
		parent::__construct($id, $label, null, $field_options, $constraints);
        $this->set_css_form_field_class('form-field-file');
    }

    /**
     * @return Template The html code for the file input.
     */
    function display()
    {
        $template = $this->get_template_to_use();

        $file_field_tpl = new StringTemplate(self::$tpl_src);
        $file_field_tpl->put_all(array(
			'MAX_FILE_SIZE' => $this->get_max_file_size(),
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled()
        ));

        $this->assign_common_template_variables($template);

        $template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $file_field_tpl->render()
        ));

        return $template;
    }

    private function get_max_file_size()
    {
        if ($this->max_size > 0)
        {
            return $this->max_size;
        }
        else
        {
            return 10000000000;
        }
    }

    protected function compute_options(array &$field_options)
    {
        foreach($field_options as $attribute => $value)
        {
            $attribute = strtolower($attribute);
            switch ($attribute)
            {
                case 'max_size':
                    $this->max_size = $value;
                    unset($field_options['max_size']);
                    // TODO add max size constraint
                    break;
            }
        }
        parent::compute_options($field_options);
    }

    /**
     * {@inheritdoc}
     */
    public function validate()
    {
        try
        {
            $this->retrieve_value();
            return true;
        }
        catch(Exception $ex)
        {
            if ($this->is_required())
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve_value()
    {
        $request = AppContext::get_request();
        $file = $request->get_file($this->get_html_id());
        $this->set_value($file);
    }

    protected function get_default_template()
    {
        return new FileTemplate('framework/builder/form/FormField.tpl');
    }
}
?>