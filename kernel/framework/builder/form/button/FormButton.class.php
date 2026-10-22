<?php
/**
 * @package     Builder
 * @subpackage  Form\button
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 22
 * @since       PHPBoost 3.0 - 2010 02 16
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface FormButton extends FormElement
{
    function set_form_id(string $form_id): void;
}
?>
