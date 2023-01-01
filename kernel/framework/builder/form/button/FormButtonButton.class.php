<?php
/**
 * @package     Builder
 * @subpackage  Form
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 11 30
 * @since       PHPBoost 3.0 - 2010 02 16
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormButtonButton extends AbstractFormButton
{
    public function __construct($value, $onclick_action = '', $name = '', $css_class = '', $data_confirmation = '', $form_id = '')
    {
        parent::__construct('button', $value, $name, $onclick_action, $css_class, $data_confirmation, $form_id);
    }
}
?>
