<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 04 13
 * @since   	PHPBoost 5.0 - 2017 03 26
*/

class GoogleMapsSetup extends DefaultModuleSetup
{
	public function uninstall()
	{
		ConfigManager::delete('google-maps', 'config');
	}
}
?>
