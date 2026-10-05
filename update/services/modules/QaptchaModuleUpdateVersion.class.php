<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2020 12 20
*/

class QaptchaModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('qaptcha');

		// Old files from the previous QuestionCaptcha module to delete
		self::$delete_old_files_list = [
			// Old QuestionCaptcha root-level files (module was at /QuestionCaptcha/)
			PATH_TO_ROOT . '/QuestionCaptcha/config.ini',
			PATH_TO_ROOT . '/QuestionCaptcha/index.php',
			PATH_TO_ROOT . '/QuestionCaptcha/controllers/AdminQuestionCaptchaConfig.class.php',
			PATH_TO_ROOT . '/QuestionCaptcha/fields/QuestionCaptchaFormFieldQuestions.class.php',
			PATH_TO_ROOT . '/QuestionCaptcha/lang/english/common.php',
			PATH_TO_ROOT . '/QuestionCaptcha/lang/english/desc.ini',
			PATH_TO_ROOT . '/QuestionCaptcha/lang/english/install.php',
			PATH_TO_ROOT . '/QuestionCaptcha/lang/french/common.php',
			PATH_TO_ROOT . '/QuestionCaptcha/lang/french/desc.ini',
			PATH_TO_ROOT . '/QuestionCaptcha/lang/french/install.php',
			PATH_TO_ROOT . '/QuestionCaptcha/phpboost/QuestionCaptcha.class.php',
			PATH_TO_ROOT . '/QuestionCaptcha/phpboost/QuestionCaptchaConfig.class.php',
			PATH_TO_ROOT . '/QuestionCaptcha/phpboost/QuestionCaptchaExtensionPointProvider.class.php',
			PATH_TO_ROOT . '/QuestionCaptcha/phpboost/QuestionCaptchaOptions.class.php',
			PATH_TO_ROOT . '/QuestionCaptcha/phpboost/QuestionCaptchaSetup.class.php',
			PATH_TO_ROOT . '/QuestionCaptcha/services/QuestionCaptchaItem.class.php',
			PATH_TO_ROOT . '/QuestionCaptcha/templates/QuestionCaptcha.tpl',
			PATH_TO_ROOT . '/QuestionCaptcha/templates/QuestionCaptchaFormFieldQuestions.tpl',
			PATH_TO_ROOT . '/QuestionCaptcha/templates/questioncaptcha.css',
			PATH_TO_ROOT . '/QuestionCaptcha/util/QuestionCaptchaUrlBuilder.class.php',
			// Old files from a previous qaptcha version
			'/services/qaptchaQuestion.class.php',
			'/templates/qaptcha.css',
		];

		self::$delete_old_folders_list = [
			// Delete the entire old QuestionCaptcha root-level directory once files are removed
			PATH_TO_ROOT . '/QuestionCaptcha',
		];
	}

	public function execute()
	{
		parent::execute();
		// Module registration (QuestionCaptcha → qaptcha) is handled by
		// UpdateServices::update_modules() via uninstall_module() + install_module()
		// before this execute() is called. No DB_TABLE_MODULES update needed here.
		$this->migrate_content_management_config();
	}

	/**
	 * If QuestionCaptcha was the active captcha in ContentManagementConfig, replace it with Qaptcha.
	 * QaptchaConfig::load() uses the same DB config key ('question-captcha') as QuestionCaptchaConfig,
	 * so the questions/answers configured by the admin are preserved automatically.
	 */
	private function migrate_content_management_config()
	{
		try {
			$content_management_config = ContentManagementConfig::load();
			if ($content_management_config->get_used_captcha_module() === 'QuestionCaptcha') {
				$content_management_config->set_used_captcha_module('Qaptcha');
				ContentManagementConfig::save();
			}
		} catch (Exception $e) {}
	}
}
?>
