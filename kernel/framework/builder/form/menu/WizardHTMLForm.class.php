<?php
/**
 * This class is a child of HTMLForms for a wizard menu and enables you to handle all the operations regarding forms.
 * Indeed, you build a form using object components (fieldsets, fields, buttons) and it's able to display, to retrieve
 * the posted values and also validate the entered data from constraints you define.
 * The validation is done in PHP when the form is received, but also in live thanks to Javascript (each field is
 * validated when it looses the focus and the whole form is validated when the user submits it).
 *
 * @package     Builder
 * @subpackage  Form\menu
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.2 - 2019 07 31
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

class WizardHTMLForm extends HTMLForm
{
	protected $captcha_fieldset_css_class = 'captcha-element wizard-step';

	protected $submit_button_class = 'WizardFormFieldsetSubmit';
}
?>
