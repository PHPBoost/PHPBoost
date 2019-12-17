<?php
/**
 * This class manage multi-line text fields, but the text can't be formatted.
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 28
 * @since       PHPBoost 2.0 - 2009 04 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldMultiLineTextEditor extends AbstractFormField
{
    protected $rows = 5;
    protected $cols = 40;

    /**
     * Constructs a multi line text edit.
     * In addition to the FormField parameters, there are these ones:
     * <ul>
     *  <li>rows: the number of rows of the texarea</li>
     *  <li>cols: the number of cols of the textarea</li>
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
            $attribute = TextHelper::strtolower($attribute);
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
