<?php
/**
 * This class is a fork of FormFieldActionLinkList and manage action links for a wizard menu.
 *
 * @package     Builder
 * @subpackage  Form\menu
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 10 11
 * @since       PHPBoost 5.2 - 2019 07 31
*/

class WizardActionLinkList extends FormFieldActionLinkList
{
	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/menu/WizardActionLinkList.tpl');
	}
}
?>
