<?php
/*##################################################
 *                       FormFieldMultiLineTextEditor.class.php
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
 * @desc This class manage multi-line text fields, but the text can't be formatted.
 * @package {@package}
 */
class FormFieldMultiLineTextEditor extends AbstractFormField
{
    protected $rows = 5;
    protected $cols = 40;

    /**
     * @desc Constructs a multi line text edit.
     * In addition to the FormField parameters, there are these ones:
     * <ul>
     * 	<li>rows: the number of rows of the texarea</li>
     * 	<li>cols: the number of cols of the textarea</li>
     * </ul>
     * @param string $id Field id
     * @param string $label Field label
     * @param string $value Default value
     * @param string[] $field_options Options
     * @param FormFieldConstraint[] $constraints List of the constraints
     */
    public function __construct($id, $label, $value, array $field_options = array(), array $constraints = array())
    {
        parent::__construct($id, $label, $value, $field_options, $constraints);
    }

    /**
     * {@inheritdoc}
     */
    public function display()
    {
        $template = $this->get_template_to_use();
         
        $this->assign_common_template_variables($template);
        $this->assign_textarea_template_variables($template);

        return $template;
    }

    private function assign_textarea_template_variables(Template $template)
    {
        $template->put_all(array(
			'ROWS' => $this->rows,
			'COLS' => $this->cols
        ));
    }

    protected function compute_options(array &$field_options)
    {
        foreach($field_options as $attribute => $value)
        {
            $attribute = strtolower($attribute);
            switch ($attribute)
            {
                case 'rows':
                    $this->rows = $value;
                    unset($field_options['rows']);
                    break;
                case 'cols':
                    $this->cols = $value;
                    unset($field_options['cols']);
                    break;
            }
        }
        parent::compute_options($field_options);
    }

    protected function get_default_template()
    {
        return new FileTemplate('framework/builder/form/FormFieldMultiLineTextEditor.tpl');
    }
}
?>