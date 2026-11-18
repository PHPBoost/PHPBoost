<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2014 05 09
*/

class QaptchaExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('qaptcha');
	}

	public function captcha()
	{
		return new Qaptcha();
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('qaptcha.css');
		return $module_css_files;
	}

	public function url_mappings()
	{
		return new UrlMappings([new DispatcherUrlMapping('/qaptcha/index.php')]);
	}
}
?>
