<?php
/**
* This class is a fork of FormFieldsetHTML and manage fieldset for form with menu.
*
* @package     Builder
* @subpackage  Form\menu
* @copyright   &copy; 2005-2026 PHPBoost
* @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
* @author      Sebastien LARTIGUE <babsolune@phpboost.com>
* @version     PHPBoost 6.1 - last update: 2025 03 07
* @since       PHPBoost 6.0 - 2025 03 07
*/

class TabsNavFieldset extends FormFieldsetHTML
{
	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/menu/TabsNavFieldset.tpl');
	}
}

?>
