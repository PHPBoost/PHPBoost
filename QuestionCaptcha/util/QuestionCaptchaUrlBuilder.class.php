<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2017 03 26
 * @since       PHPBoost 5.0 - 2017 03 26
*/

class QuestionCaptchaUrlBuilder
{
	private static $dispatcher = '/QuestionCaptcha';

	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}
}
?>
