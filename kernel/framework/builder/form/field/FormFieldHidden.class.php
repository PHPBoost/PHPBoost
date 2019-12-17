<?php
/**
 * This class manage hidden input fields.
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 03 10
 * @since       PHPBoost 2.0 - 2009 04 28
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class FormFieldHidden extends AbstractFormField
{

    public function __construct($id, $value)
    {
        parent::__construct($id, '', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function display()
    {
        $template = $this->get_template_to_use();

        $this->assign_common_template_variables($template);

        return $template;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve_value()
    {
        $request = AppContext::get_request();
        $this->set_value($request->get_value($this->get_html_id(), ''));
    }

    protected function get_default_template()
    {
        return new FileTemplate('framework/builder/form/fieldelements/FormFieldHidden.tpl');
    }
}
?>
