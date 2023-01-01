<?php
/**
 * @package     Builder
 * @subpackage  Form\menu
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 10 12
 * @since       PHPBoost 5.2 - 2019 10 11
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class FormFieldsetAccordionControls extends FormFieldsetHTML
{
	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/menu/FormFieldsetAccordionControls.tpl');
	}
}

?>
