<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2014 05 09
*/

class QuestionCaptchaExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('QuestionCaptcha');
	}

	public function captcha()
	{
		return new QuestionCaptcha();
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('questioncaptcha.css');
		return $module_css_files;
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/QuestionCaptcha/index.php')));
	}
}
?>
