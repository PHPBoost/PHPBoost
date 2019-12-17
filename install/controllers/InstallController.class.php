<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 10 16
 * @since       PHPBoost 3.0 - 2010 09 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

abstract class InstallController extends AbstractController
{
	protected $lang = array();

	protected function load_lang(HTTPRequestCustom $request)
	{
		$locale = TextHelper::htmlspecialchars($request->get_string('lang', ''));
		$locale = in_array($locale, InstallationServices::get_available_langs()) ? $locale : InstallationServices::get_default_lang();
		LangLoader::set_locale($locale);
		InstallUrlBuilder::set_locale($locale);
		$this->lang = LangLoader::get('install', 'install');
	}
}
?>
