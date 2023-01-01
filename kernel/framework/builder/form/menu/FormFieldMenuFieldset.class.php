<?php
/**
* This class is a fork of FormFieldsetHTML and manage fieldset for form with menu.
*
* @package     Builder
* @subpackage  Form\menu
* @copyright   &copy; 2005-2023 PHPBoost
* @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
* @author      Sebastien LARTIGUE <babsolune@phpboost.com>
* @version     PHPBoost 6.0 - last update: 2019 10 11
* @since       PHPBoost 5.2 - 2019 07 30
*/

class FormFieldMenuFieldset extends FormFieldsetHTML
{
	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/menu/FormFieldMenuFieldset.tpl');
	}
}

?>
