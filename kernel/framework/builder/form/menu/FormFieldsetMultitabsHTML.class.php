<?php
/**
 * @package     Builder
 * @subpackage  Form\menu
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 11
 * @since       PHPBoost 5.2 - 2019 10 11
*/

class FormFieldsetMultitabsHTML extends FormFieldsetHTML
{
	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/menu/FormFieldsetMultitabs.tpl');
	}
}

?>
