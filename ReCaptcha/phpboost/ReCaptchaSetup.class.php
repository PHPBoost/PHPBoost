<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 4.0 - 2013 12 20
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
*/

class ReCaptchaSetup extends DefaultModuleSetup
{
	public function uninstall()
	{
		return AppContext::get_captcha_service()->uninstall_captcha('ReCaptcha');
		$this->delete_configuration();
	}

	private function delete_configuration()
	{
		ConfigManager::delete('recaptcha', 'config');
	}
}
?>
