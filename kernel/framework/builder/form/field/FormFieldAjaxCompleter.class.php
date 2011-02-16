<?php
/*##################################################
 *                          FormFieldAjaxCompleter.class.php
 *                            -------------------
 *   begin                : January 9, 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @desc This class represents a ajax completer field
 * @package {@package}
 */
class FormFieldAjaxCompleter extends FormFieldTextEditor
{
	private $size = 30;
    private $maxlength = 255;
	private $method = 'post';
	private $file;
	private $parameter = 'name';
	private static $tpl_src = '<input type="text" size="{SIZE}" maxlength="{MAX_LENGTH}" name="${escape(NAME)}" id="${escape(ID)}" value="${escape(VALUE)}"
	class="${escape(CLASS)}" # IF C_DISABLED # disabled="disabled" # ENDIF # autocomplete="off" /><div id="${escape(ID)}_completer" class="form_autocompleter"></div>
	<script type="text/javascript">new Ajax.Autocompleter(${escapejs(ID)}, "${escape(ID)}_completer", ${escapejs(FILE)}, { method: ${escapejs(METHOD)}, paramName: ${escapejs(PARAMETER)}});</script>';	

	
    /**
     * @desc Constructs a FormFieldAjaxCompleter.
     * It has these options in addition to the AbstractFormField ones:
     * <ul>
     * 	<li>size: the number of size of the field</li>
     * 	<li>maxlength: the number of maxlength of the field</li>
	 *  <li>method: the string method send request : post or get</li>
	 *  <li>file: the string file url</li>
	 *  <li>parameter: the string parameter name variable send for request</li>
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

		if (empty($this->file))
		{
			throw new Exception('Add file options containing file url');
		}
		
        $field->put_all(array(
			'SIZE' => $this->size,
			'MAX_LENGTH' => $this->maxlength,
			'NAME' => $this->get_html_id(),
			'FILE' =>  $this->file,
			'METHOD' =>  $this->method,
			'PARAMETER' =>  $this->parameter,
			'ID' => $this->get_html_id(),
			'VALUE' => $this->get_value(),
			'CLASS' => $this->get_css_class(),
			'C_DISABLED' => $this->is_disabled()
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
				case 'method' :
                    $this->method = $value;
                    unset($field_options['method']);
                    break;
				case 'file' :
                    $this->file = $value;
                    unset($field_options['file']);
                    break;
				case 'parameter' :
                    $this->parameter = $value;
                    unset($field_options['parameter']);
                    break;
            }
        }
        parent::compute_options($field_options);
    }
}
?>