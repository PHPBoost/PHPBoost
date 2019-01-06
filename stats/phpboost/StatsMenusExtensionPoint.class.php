<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2014 12 22
 * @since   	PHPBoost 3.0 - 2011 10 08
*/

class StatsMenusExtensionPoint implements MenusExtensionPoint
{
	public function get_menus()
	{
		return array(
			new StatsModuleMiniMenu()
		);
	}
}
?>
