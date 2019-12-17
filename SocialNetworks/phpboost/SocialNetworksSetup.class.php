<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 02 13
 * @since       PHPBoost 5.2 - 2019 02 13
*/

class SocialNetworksSetup extends DefaultModuleSetup
{
	public function uninstall()
	{
		ConfigManager::delete('social-network', 'config');
	}
}
?>
