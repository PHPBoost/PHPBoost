<?php
/**
 * This class represents a short multi-line text field.
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 06 03
 * @since       PHPBoost 3.0 - 2010 12 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class FormFieldShortMultiLineTextEditor extends FormFieldMultiLineTextEditor
{
	private $width = 0;

    /**
     * Constructs a FormFieldShortMultiLineTextEditor.
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
    public function __construct($id, $label, $value, array $field_options = array(), array $constraints = array())
    {
        parent::__construct($id, $label, $value, $field_options, $constraints);
    }

    /**
     * @return string The html code for the input.
     */
    public function display()
    {
        $template = $this->get_template_to_use();

        $field = new FileTemplate('framework/builder/form/fieldelements/FormFieldShortMultiLineTextEditor.tpl');

        $field->put_all(array(
			'ROWS' => $this->rows,
			'COLS' => $this->cols,
			'WIDTH' => ($this->width > 0) ? 'width: ' . $this->width . '%;' : '',
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'VALUE' => $this->get_value(),
            'C_HAS_CSS_CLASS' => $this->get_css_class() != '',
			'CSS_CLASS' => $this->get_css_class(),
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
            $attribute = TextHelper::strtolower($attribute);
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
