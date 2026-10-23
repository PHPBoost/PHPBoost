<?php
/**
 * This class manage hidden input fields.
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 22
 * @since       PHPBoost 2.0 - 2009 04 28
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldHidden extends AbstractFormField
{

    public function __construct(string $id, mixed $value)
    {
        parent::__construct($id, '', $value);
    }

    public function display(): Template
    {
        $template = $this->get_template_to_use();

        $this->assign_common_template_variables($template);

        return $template;
    }

    public function retrieve_value(): void
    {
        $request = AppContext::get_request();
        $this->set_value($request->get_value($this->get_html_id(), ''));
    }

    protected function get_default_template(): FileTemplate
    {
        return new FileTemplate('framework/builder/form/fieldelements/FormFieldHidden.tpl');
    }
}
?>
