<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 20
 * @since       PHPBoost 6.0 - 2020 12 20
*/

class QuestionCaptchaModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('QuestionCaptcha');

		self::$delete_old_files_list = array(
			'/services/QuestionCaptchaQuestion.class.php',
			'/templates/QuestionCaptcha.css'
		);
	}
}
?>
