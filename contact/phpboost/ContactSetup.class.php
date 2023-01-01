<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 03 01
*/

class ContactSetup extends DefaultModuleSetup
{
	public function uninstall()
	{
		$this->delete_configuration();
	}

	private function delete_configuration()
	{
		ConfigManager::delete('contact', 'config');
	}
}
?>
