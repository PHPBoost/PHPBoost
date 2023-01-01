<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2012 05 05
*/

class SitemapSetup extends DefaultModuleSetup {

	public function uninstall()
	{
		$this->delete_configuration();
	}

	private function delete_configuration()
	{
		ConfigManager::delete('sitemap', 'config');
	}
}
?>
