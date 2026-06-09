<?php
/**
 * @package     Builder
 * @subpackage  Form\fieldset
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2010 02 16
 * @author      mipel <mipel@phpboost.com>
*/

class FormFieldsetVertical extends AbstractFormFieldset
{

    public function __construct($id, $options = [])
    {
        parent::__construct($id, $options);
    }

    /**
     * @return Template
     */
    public function display()
    {
        $template = $this->get_template_to_use();

        $this->assign_template_fields($template);

        return $template;
    }

    protected function get_default_template()
    {
        return new FileTemplate('framework/builder/form/fieldsetelements/FormFieldsetVertical.tpl');
    }
}
?>
