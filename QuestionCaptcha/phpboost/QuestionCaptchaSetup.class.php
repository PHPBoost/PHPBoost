<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2014 05 09
*/

class QuestionCaptchaSetup extends DefaultModuleSetup
{
	public function uninstall()
	{
		ConfigManager::delete('question-captcha', 'config');
		return AppContext::get_captcha_service()->uninstall_captcha('QuestionCaptcha');
	}
}
?>
