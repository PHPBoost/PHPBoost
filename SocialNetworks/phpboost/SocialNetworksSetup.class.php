<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 11 14
 * @since       PHPBoost 5.2 - 2019 02 13
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SocialNetworksSetup extends DefaultModuleSetup
{
	public function uninstall()
	{
		ConfigManager::delete('social-networks', 'config');
	}
}
?>
